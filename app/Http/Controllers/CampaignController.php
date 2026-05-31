<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::where('user_id', Auth::id())
            ->withCount(['ads', 'leads']);

        if ($status = $request->query('status')) {
            if (in_array($status, Campaign::STATUSES, true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where('name', 'like', "%{$search}%");
        }

        $campaigns = $query->latest()->paginate(15)->withQueryString();

        return view('campaigns.index', [
            'campaigns' => $campaigns,
            'q' => $search,
            'currentStatus' => $status,
        ]);
    }

    public function create()
    {
        return view('campaigns.create', [
            'campaign' => new Campaign([
                'objective' => 'leads',
                'status' => 'draft',
                'start_date' => now()->toDateString(),
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateCampaign($request);
        $data['objective'] = 'leads';
        $data['user_id'] = Auth::id();

        $campaign = Campaign::create($data);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('status', 'Campaign created. Now create your first ad.');
    }

    public function show(Campaign $campaign)
    {
        $this->authorize_($campaign);

        $campaign->load(['ads' => fn ($q) => $q->latest()]);

        $stats = [
            'impressions' => (int) $campaign->ads->sum('impressions_count'),
            'clicks' => (int) $campaign->ads->sum('clicks_count'),
            'leads' => (int) $campaign->ads->sum('leads_count'),
            'spend' => (float) $campaign->spend,
        ];
        $stats['ctr'] = $stats['impressions'] > 0
            ? round(($stats['clicks'] / $stats['impressions']) * 100, 2) : 0;
        $stats['cpl'] = $stats['leads'] > 0
            ? round($stats['spend'] / $stats['leads'], 2) : 0;

        return view('campaigns.show', compact('campaign', 'stats'));
    }

    public function edit(Campaign $campaign)
    {
        $this->authorize_($campaign);

        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize_($campaign);

        $data = $this->validateCampaign($request);
        $data['objective'] = 'leads';
        $campaign->update($data);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('status', 'Campaign updated.');
    }

    public function destroy(Campaign $campaign)
    {
        $this->authorize_($campaign);

        $campaign->delete();

        return redirect()
            ->route('campaigns.index')
            ->with('status', 'Campaign deleted.');
    }

    private function validateCampaign(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'objective' => ['required', 'string', 'in:leads'],
            'status' => ['required', 'string', 'in:'.implode(',', Campaign::STATUSES)],
            'daily_budget' => ['nullable', 'numeric', 'min:0'],
            'total_budget' => ['nullable', 'numeric', 'min:0'],
            'spend' => ['nullable', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);
    }

    private function authorize_(Campaign $campaign): void
    {
        if ($campaign->user_id !== Auth::id()) {
            throw new NotFoundHttpException();
        }
    }
}
