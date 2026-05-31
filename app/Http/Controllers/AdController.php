<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::where('user_id', Auth::id())
            ->with('campaign');

        if ($campaignId = $request->query('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        if ($status = $request->query('status')) {
            if (in_array($status, Ad::STATUSES, true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('headline', 'like', "%{$search}%");
            });
        }

        $ads = $query->with('campaign')->latest()->paginate(15)->withQueryString();
        $campaigns = Campaign::where('user_id', Auth::id())->orderBy('name')->get();

        return view('ads.index', [
            'ads' => $ads,
            'campaigns' => $campaigns,
            'q' => $search,
            'currentStatus' => $status,
            'currentCampaign' => $campaignId,
        ]);
    }

    public function create(Request $request)
    {
        $campaigns = Campaign::where('user_id', Auth::id())->orderBy('name')->get();

        if ($campaigns->isEmpty()) {
            return redirect()
                ->route('campaigns.create')
                ->with('status', 'Create a campaign first, then you can add ads to it.');
        }

        $ad = new Ad([
            'campaign_id' => $request->query('campaign_id') ?: $campaigns->first()->id,
            'placement' => 'hirevo_homepage',
            'status' => 'pending_review',
            'cta_label' => 'Learn More',
        ]);

        return view('ads.create', compact('ad', 'campaigns'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAd($request);
        $this->normalizeTargetingData($data);

        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($data['campaign_id']);

        $data['user_id'] = Auth::id();
        $data['campaign_id'] = $campaign->id;
        $data['destination_url'] = $this->resolveDestinationUrl($data['destination_url'] ?? null);
        $this->handleImageUpload($request, $data);

        $data['status'] = 'pending_review';

        $ad = Ad::create($data);

        return redirect()
            ->route('ads.show', $ad)
            ->with('status', 'Ad submitted for review. Approve it in the Hirevo admin panel (Sponsored ads) to go live.');
    }

    public function show(Ad $ad)
    {
        $this->authorize_($ad);

        $ad->load(['campaign', 'leads' => fn ($q) => $q->latest()->limit(10)]);

        return view('ads.show', compact('ad'));
    }

    public function edit(Ad $ad)
    {
        $this->authorize_($ad);

        $ad->load('campaign');
        $campaigns = Campaign::where('user_id', Auth::id())->orderBy('name')->get();

        return view('ads.edit', compact('ad', 'campaigns'));
    }

    public function update(Request $request, Ad $ad)
    {
        $this->authorize_($ad);

        $data = $this->validateAd($request);
        $this->normalizeTargetingData($data);
        $campaign = Campaign::where('user_id', Auth::id())->findOrFail($data['campaign_id']);
        $data['campaign_id'] = $campaign->id;
        $data['destination_url'] = $this->resolveDestinationUrl($data['destination_url'] ?? $ad->destination_url);
        $this->handleImageUpload($request, $data, $ad);

        if (! Auth::user()->isAdmin()) {
            $data['status'] = $this->resolveAdvertiserStatus($ad, (string) ($data['status'] ?? $ad->status));
        }

        $ad->update($data);

        return redirect()
            ->route('ads.show', $ad)
            ->with('status', 'Ad updated.');
    }

    public function submitForReview(Ad $ad)
    {
        $this->authorize_($ad);

        if ($ad->status === 'pending_review') {
            return redirect()
                ->route('ads.show', $ad)
                ->with('status', 'This ad is already in review.');
        }

        if ($ad->status === 'active') {
            return redirect()
                ->route('ads.show', $ad)
                ->with('status', 'This ad is already live on Hirevo. Pause it first if you need to change creative.');
        }

        $ad->update(['status' => 'pending_review']);

        return redirect()
            ->route('ads.show', $ad)
            ->with('status', 'Ad submitted for review. You will see it on Hirevo after approval.');
    }

    public function destroy(Ad $ad)
    {
        $this->authorize_($ad);

        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()
            ->route('ads.index')
            ->with('status', 'Ad deleted.');
    }

    private function validateAd(Request $request): array
    {
        return $request->validate([
            'campaign_id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:160'],
            'headline' => ['required', 'string', 'max:160'],
            'body' => ['nullable', 'string', 'max:1000'],
            'image_file' => ['nullable', 'image', 'max:5120'],
            'cta_label' => ['required', 'string', 'max:64'],
            'destination_url' => ['nullable', 'string', 'max:500'],
            'placement' => ['required', 'string', 'in:'.implode(',', array_keys(Ad::PLACEMENTS))],
            'status' => ['nullable', 'string', 'in:'.implode(',', Ad::STATUSES)],
            'target_area' => ['required', 'array', 'min:1'],
            'target_area.*' => ['required', 'string', 'max:60'],
            'target_age_group' => ['required', 'array', 'min:1'],
            'target_age_group.*' => ['required', 'string', 'max:30'],
            'target_audience' => ['nullable', 'array'],
            'target_audience.*' => ['required', 'string', 'max:80'],
        ]);
    }

    private function normalizeTargetingData(array &$data): void
    {
        $data['target_area'] = $this->csvFromArray($data['target_area'] ?? []);
        $data['target_age_group'] = $this->csvFromArray($data['target_age_group'] ?? []);
        $data['target_audience'] = $this->csvFromArray($data['target_audience'] ?? []);
    }

    private function csvFromArray(array $values): ?string
    {
        $normalized = collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return $normalized ? implode(', ', $normalized) : null;
    }

    private function resolveDestinationUrl(?string $url): string
    {
        $fallback = rtrim((string) config('app.url'), '/');
        $candidate = trim((string) $url);

        return $candidate !== '' ? $candidate : ($fallback !== '' ? $fallback : '/');
    }

    private function handleImageUpload(Request $request, array &$data, ?Ad $ad = null): void
    {
        unset($data['image_file']);

        if (! $request->hasFile('image_file')) {
            return;
        }

        $storedPath = $request->file('image_file')->store('ads', 'public');

        if ($ad && $ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $data['image_path'] = $storedPath;
        $data['image_url'] = Ad::storagePublicUrl($storedPath);
    }

    private function resolveAdvertiserStatus(Ad $ad, string $requested): string
    {
        $allowed = array_keys($ad->advertiserStatusOptions());

        if (in_array($requested, $allowed, true)) {
            return $requested;
        }

        return $ad->status;
    }

    private function authorize_(Ad $ad): void
    {
        if ($ad->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            throw new NotFoundHttpException();
        }
    }
}
