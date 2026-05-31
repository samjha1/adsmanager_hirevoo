<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AdsTrackingController extends Controller
{
    /**
     * GET /api/ads/serve?placement=hirevo_homepage
     * Returns the highest-priority active ad for a placement (round-robin would need more work).
     * Lets hirevo fetch a creative to display.
     */
    public function serve(Request $request)
    {
        $placement = (string) $request->query('placement', 'hirevo_homepage');

        $ad = Ad::where('placement', $placement)
            ->where('status', 'active')
            ->whereHas('campaign', fn ($q) => $q->where('status', 'active'))
            ->inRandomOrder()
            ->first();

        if (! $ad) {
            return response()->json(['ad' => null], 200);
        }

        $base = rtrim(config('app.url'), '/');

        return response()->json([
            'ad' => [
                'public_key' => $ad->public_key,
                'headline' => $ad->headline,
                'body' => $ad->body,
                'image_url' => $ad->image_url,
                'cta_label' => $ad->cta_label,
                'destination_url' => $ad->destination_url,
                'target_area' => $ad->target_area,
                'target_age_group' => $ad->target_age_group,
                'target_audience' => $ad->target_audience,
                'click_url' => "{$base}/api/track/click/{$ad->public_key}",
                'impression_url' => "{$base}/api/track/impression/{$ad->public_key}",
                'lead_url' => "{$base}/api/track/lead/{$ad->public_key}",
            ],
        ]);
    }

    /**
     * GET /api/track/impression/{publicKey}.gif
     * Returns a 1x1 transparent GIF and increments impressions_count.
     */
    public function impression(string $publicKey)
    {
        $ad = Ad::where('public_key', $publicKey)->first();
        if ($ad) {
            DB::table('leadsmanager_ads')->where('id', $ad->id)->increment('impressions_count');
        }

        $gif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($gif, 200, [
            'Content-Type' => 'image/gif',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    /**
     * GET /api/track/click/{publicKey}
     * Increments clicks_count and 302-redirects to the ad's destination URL.
     */
    public function click(string $publicKey)
    {
        $ad = Ad::where('public_key', $publicKey)->first();
        if (! $ad) {
            return redirect('/');
        }

        DB::table('leadsmanager_ads')->where('id', $ad->id)->increment('clicks_count');
        $destination = trim((string) $ad->destination_url) !== '' ? $ad->destination_url : '/';

        return redirect()->away($destination);
    }

    /**
     * POST /api/track/lead/{publicKey}
     * Body: name (required), email, phone, company, job_title, message, referrer
     * Creates a Lead attributed to the ad's owner, ad, and campaign.
     */
    public function lead(Request $request, string $publicKey)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['nullable', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:32'],
            'company' => ['nullable', 'string', 'max:160'],
            'job_title' => ['nullable', 'string', 'max:160'],
            'message' => ['nullable', 'string', 'max:5000'],
            'referrer' => ['nullable', 'string', 'max:500'],
        ]);

        $ad = Ad::where('public_key', $publicKey)->first();
        if (! $ad) {
            return response()->json(['ok' => false, 'error' => 'invalid_ad'], 404);
        }

        $extraMeta = $request->except(['_token', 'name', 'email', 'phone', 'company', 'job_title', 'message', 'referrer']);

        $lead = Lead::create([
            'user_id' => $ad->user_id,
            'campaign_id' => $ad->campaign_id,
            'ad_id' => $ad->id,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'job_title' => $data['job_title'] ?? null,
            'source' => 'hirevo_ad',
            'placement' => $ad->placement,
            'referrer_url' => $data['referrer'] ?? $request->headers->get('referer'),
            'status' => 'new',
            'value' => 0,
            'notes' => $data['message'] ?? null,
            'meta' => $extraMeta ?: null,
        ]);

        DB::table('leadsmanager_ads')->where('id', $ad->id)->increment('leads_count');

        if ($request->wantsJson() || $request->isXmlHttpRequest() || $request->query('json')) {
            return response()->json([
                'ok' => true,
                'lead_id' => $lead->id,
                'thank_you' => 'Thanks! We will be in touch shortly.',
            ]);
        }

        $redirect = $request->input('_redirect') ?: (trim((string) $ad->destination_url) !== '' ? $ad->destination_url : '/');

        return redirect()->away($redirect);
    }
}
