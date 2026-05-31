<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Campaign;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $adAggregates = Ad::where('user_id', $userId)
            ->selectRaw('
                COALESCE(SUM(impressions_count),0) AS impressions,
                COALESCE(SUM(clicks_count),0) AS clicks,
                COALESCE(SUM(leads_count),0) AS leads,
                COUNT(*) AS total_ads,
                SUM(CASE WHEN status="active" THEN 1 ELSE 0 END) AS active_ads
            ')
            ->first();

        $totalSpend = (float) Campaign::where('user_id', $userId)->sum('spend');
        $impressions = (int) $adAggregates->impressions;
        $clicks = (int) $adAggregates->clicks;
        $adLeads = (int) $adAggregates->leads;

        $metrics = [
            'spend' => $totalSpend,
            'impressions' => $impressions,
            'clicks' => $clicks,
            'leads' => $adLeads,
            'ctr' => $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0,
            'cpl' => $adLeads > 0 ? round($totalSpend / $adLeads, 2) : 0,
            'cpc' => $clicks > 0 ? round($totalSpend / $clicks, 2) : 0,
            'conv_rate' => $clicks > 0 ? round(($adLeads / $clicks) * 100, 2) : 0,
            'active_campaigns' => Campaign::where('user_id', $userId)->where('status', 'active')->count(),
            'total_campaigns' => Campaign::where('user_id', $userId)->count(),
            'active_ads' => (int) $adAggregates->active_ads,
            'total_ads' => (int) $adAggregates->total_ads,
            'all_leads' => Lead::where('user_id', $userId)->count(),
        ];

        $topCampaigns = Campaign::where('user_id', $userId)
            ->withCount(['leads', 'ads'])
            ->orderByDesc('leads_count')
            ->orderByDesc('spend')
            ->limit(5)
            ->get();

        $recentLeads = Lead::where('user_id', $userId)
            ->with(['campaign', 'ad'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard', compact('metrics', 'topCampaigns', 'recentLeads'));
    }
}
