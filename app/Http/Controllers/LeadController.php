<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Campaign;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilteredLeadsQuery($request);

        $export = (string) $request->query('export');
        if (in_array($export, ['csv', 'xlsx'], true)) {
            return $this->exportFilteredLeads($query, $export);
        }

        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $source = $request->query('source');
        $campaignId = $request->query('campaign_id');
        $adId = $request->query('ad_id');

        $leads = $query->latest()->paginate(15)->withQueryString();

        return view('leads.index', [
            'leads' => $leads,
            'q' => $search,
            'currentStatus' => $status,
            'currentSource' => $source,
            'currentCampaign' => $campaignId,
            'currentAd' => $adId,
            'campaigns' => Campaign::where('user_id', Auth::id())->orderBy('name')->get(),
        ]);
    }

    private function buildFilteredLeadsQuery(Request $request)
    {
        $query = Lead::where('user_id', Auth::id())
            ->with(['campaign', 'ad']);

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            if (in_array($status, Lead::STATUSES, true)) {
                $query->where('status', $status);
            }
        }

        if ($source = $request->query('source')) {
            if (in_array($source, Lead::SOURCES, true)) {
                $query->where('source', $source);
            }
        }

        if ($campaignId = $request->query('campaign_id')) {
            $query->where('campaign_id', $campaignId);
        }

        if ($adId = $request->query('ad_id')) {
            $query->where('ad_id', $adId);
        }

        return $query;
    }

    private function exportFilteredLeads($query, string $format)
    {
        $rows = $query->latest()->get();

        $headers = [
            'Lead ID',
            'Name',
            'Email',
            'Phone',
            'Company',
            'Job Title',
            'Source',
            'Status',
            'Campaign',
            'Ad',
            'Placement',
            'Value',
            'Notes',
            'Created At',
        ];

        $filenameBase = 'leads-'.now()->format('Ymd-His');
        $exportRows = $rows->map(function (Lead $lead) {
            return [
                $lead->id,
                $lead->name,
                $lead->email,
                $lead->phone,
                $lead->company,
                $lead->job_title,
                $lead->source,
                $lead->status,
                optional($lead->campaign)->name,
                optional($lead->ad)->name,
                $lead->placement,
                $lead->value,
                $lead->notes,
                optional($lead->created_at)->toDateTimeString(),
            ];
        });

        if ($format === 'xlsx') {
            // Native XLSX creation is unavailable without ext-zip/ext-gd.
            // Smooth fallback: provide same filtered export as CSV.
            $format = 'csv';
        }

        return response()->streamDownload(function () use ($headers, $exportRows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers);
            foreach ($exportRows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filenameBase.'.'.$format, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function create()
    {
        return view('leads.create', [
            'lead' => new Lead(['status' => 'new', 'source' => 'website']),
            'campaigns' => Campaign::where('user_id', Auth::id())->orderBy('name')->get(),
            'ads' => Ad::where('user_id', Auth::id())->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateLead($request);
        $data['user_id'] = Auth::id();

        $this->scopeCampaignAd($data);

        $lead = Lead::create($data);

        return redirect()
            ->route('leads.show', $lead)
            ->with('status', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $this->authorize_($lead);

        $lead->load(['campaign', 'ad']);

        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $this->authorize_($lead);

        return view('leads.edit', [
            'lead' => $lead,
            'campaigns' => Campaign::where('user_id', Auth::id())->orderBy('name')->get(),
            'ads' => Ad::where('user_id', Auth::id())->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Lead $lead)
    {
        $this->authorize_($lead);

        $data = $this->validateLead($request);
        $this->scopeCampaignAd($data);

        $lead->update($data);

        return redirect()
            ->route('leads.show', $lead)
            ->with('status', 'Lead updated.');
    }

    public function destroy(Lead $lead)
    {
        $this->authorize_($lead);

        $lead->delete();

        return redirect()
            ->route('leads.index')
            ->with('status', 'Lead deleted.');
    }

    private function validateLead(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:32'],
            'company' => ['nullable', 'string', 'max:160'],
            'job_title' => ['nullable', 'string', 'max:160'],
            'campaign_id' => ['nullable', 'integer'],
            'ad_id' => ['nullable', 'integer'],
            'source' => ['required', 'string', 'in:'.implode(',', Lead::SOURCES)],
            'status' => ['required', 'string', 'in:'.implode(',', Lead::STATUSES)],
            'value' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'next_followup_at' => ['nullable', 'date'],
        ]);
    }

    /**
     * Drop campaign_id / ad_id if they don't belong to the current user.
     */
    private function scopeCampaignAd(array &$data): void
    {
        if (! empty($data['campaign_id'])) {
            $ok = Campaign::where('user_id', Auth::id())->whereKey($data['campaign_id'])->exists();
            if (! $ok) {
                $data['campaign_id'] = null;
            }
        } else {
            $data['campaign_id'] = null;
        }

        if (! empty($data['ad_id'])) {
            $ok = Ad::where('user_id', Auth::id())->whereKey($data['ad_id'])->exists();
            if (! $ok) {
                $data['ad_id'] = null;
            }
        } else {
            $data['ad_id'] = null;
        }
    }

    private function authorize_(Lead $lead): void
    {
        if ($lead->user_id !== Auth::id()) {
            throw new NotFoundHttpException();
        }
    }
}
