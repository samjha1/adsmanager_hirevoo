@extends('layouts.app')

@section('title', 'Integrations — Ads Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <h1 class="text-3xl font-extrabold tracking-tight">Integrations</h1>
        <p class="mt-1 text-neutral-600 max-w-3xl">
            After you submit an ad, approve it in the <span class="font-semibold">Hirevo admin panel</span> (Sponsored ads). Live ads appear automatically on Hirevo screens. Snippets below are optional for other sites.
        </p>

        <div class="mt-6 bg-white rounded-3xl border border-black/5 p-6">
            <h3 class="font-extrabold text-lg">How attribution works</h3>
            <ol class="mt-3 space-y-2 text-sm text-neutral-700 list-decimal list-inside">
                <li>Pick an ad below and copy the embed block.</li>
                <li>Paste it into a Hirevo template, partial, or any page where you want the ad rendered.</li>
                <li>When users see, click, or fill the form on Hirevo, this app's API increments counters and creates a Lead in your dashboard.</li>
                <li>The Lead is owned by you (the advertiser) and is tagged with the campaign + ad + placement that generated it.</li>
            </ol>
        </div>

        @if ($ads->isEmpty())
            <div class="mt-6 p-12 text-center bg-white rounded-3xl border border-black/5">
                <p class="text-neutral-500">You don't have any ads yet.</p>
                <a href="{{ route('ads.create') }}" class="mt-4 inline-flex items-center px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold">Create your first ad</a>
            </div>
        @else
            <div class="mt-6 space-y-6">
                @foreach ($ads as $ad)
                    @php
                        $leadUrl = "{$base}/api/track/lead/{$ad->public_key}";
                        $clickUrl = "{$base}/api/track/click/{$ad->public_key}";
                        $impressionUrl = "{$base}/api/track/impression/{$ad->public_key}";

                        $formSnippet = <<<HTML
<!-- Ads Manager — Hirevo embed for ad: {$ad->name} -->
<form action="{$leadUrl}" method="POST" class="ads-manager-leadform">
  <input type="hidden" name="referrer" value="">
  <label>Name <input type="text" name="name" required></label>
  <label>Email <input type="email" name="email"></label>
  <label>Phone <input type="tel" name="phone"></label>
  <label>Company <input type="text" name="company"></label>
  <label>Message <textarea name="message"></textarea></label>
  <button type="submit">{$ad->cta_label}</button>
</form>
<!-- 1x1 impression pixel -->
<img src="{$impressionUrl}" width="1" height="1" alt="" style="display:none">
<script>
  (function(){
    var f = document.currentScript.previousElementSibling.previousElementSibling;
    if (f && f.querySelector) {
      var ref = f.querySelector('input[name="referrer"]');
      if (ref) ref.value = location.href;
    }
  })();
</script>
HTML;

                        $cardSnippet = <<<HTML
<!-- Ads Manager — Hirevo display ad: {$ad->name} -->
<a href="{$clickUrl}" class="ads-manager-card" rel="noopener">
  <img src="{$ad->image_url}" alt="{$ad->headline}">
  <div>
    <small>Sponsored</small>
    <h4>{$ad->headline}</h4>
    <p>{$ad->body}</p>
    <span class="cta">{$ad->cta_label}</span>
  </div>
</a>
<img src="{$impressionUrl}" width="1" height="1" alt="" style="display:none">
HTML;
                    @endphp

                    <div id="ad-{{ $ad->id }}" class="bg-white rounded-3xl border border-black/5 overflow-hidden">
                        <div class="p-6 border-b border-black/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h3 class="font-extrabold text-lg">{{ $ad->name }}</h3>
                                <p class="text-sm text-neutral-500">
                                    <span class="badge {{ $ad->statusBadgeClass() }}">{{ $ad->status }}</span>
                                    · {{ $ad->placementLabel() }}
                                    · Campaign: <a href="{{ route('campaigns.show', $ad->campaign) }}" class="hover:underline">{{ $ad->campaign?->name }}</a>
                                </p>
                            </div>
                            <div class="text-xs text-neutral-500 font-mono break-all sm:max-w-xs">key: {{ $ad->public_key }}</div>
                        </div>

                        <div class="p-6 grid lg:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs uppercase tracking-widest font-bold text-neutral-500">Lead-form snippet</p>
                                <p class="text-xs text-neutral-500 mt-1">For Lead Generation ads. Submissions create a Lead.</p>
                                <div class="mt-3 relative">
                                    <pre class="bg-neutral-950 text-neutral-100 text-xs rounded-2xl p-4 overflow-x-auto leading-relaxed"><code>{{ $formSnippet }}</code></pre>
                                    <button type="button" onclick="navigator.clipboard.writeText(this.previousElementSibling.innerText); this.textContent='Copied!'" class="absolute top-3 right-3 text-[11px] font-semibold px-3 py-1.5 rounded-full bg-brand-yellow text-brand-ink">Copy</button>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs uppercase tracking-widest font-bold text-neutral-500">Display-ad snippet</p>
                                <p class="text-xs text-neutral-500 mt-1">For traffic / awareness ads. Clicks redirect via tracker.</p>
                                <div class="mt-3 relative">
                                    <pre class="bg-neutral-950 text-neutral-100 text-xs rounded-2xl p-4 overflow-x-auto leading-relaxed"><code>{{ $cardSnippet }}</code></pre>
                                    <button type="button" onclick="navigator.clipboard.writeText(this.previousElementSibling.innerText); this.textContent='Copied!'" class="absolute top-3 right-3 text-[11px] font-semibold px-3 py-1.5 rounded-full bg-brand-yellow text-brand-ink">Copy</button>
                                </div>
                            </div>

                            <div class="lg:col-span-2 grid sm:grid-cols-3 gap-3 pt-4 border-t border-black/5">
                                <div>
                                    <p class="text-xs uppercase tracking-widest font-bold text-neutral-500">Lead endpoint (POST)</p>
                                    <p class="font-mono text-xs break-all mt-1">{{ $leadUrl }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest font-bold text-neutral-500">Click tracker</p>
                                    <p class="font-mono text-xs break-all mt-1">{{ $clickUrl }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-widest font-bold text-neutral-500">Impression pixel</p>
                                    <p class="font-mono text-xs break-all mt-1">{{ $impressionUrl }}</p>
                                </div>
                            </div>

                            <div class="lg:col-span-2 pt-4 border-t border-black/5 flex flex-wrap gap-3">
                                <form method="POST" action="{{ $leadUrl }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="name" value="Test Lead from Integrations">
                                    <input type="hidden" name="email" value="test@example.com">
                                    <input type="hidden" name="message" value="Manual test submission">
                                    <input type="hidden" name="_redirect" value="{{ url()->current() }}#ad-{{ $ad->id }}">
                                    <button type="submit" class="px-4 py-2 rounded-full bg-brand-ink text-white text-sm font-bold">Send test lead</button>
                                </form>
                                <a href="{{ $impressionUrl }}" target="_blank" rel="noopener" class="px-4 py-2 rounded-full border border-black/10 text-sm font-semibold">Fire test impression</a>
                                <a href="{{ $clickUrl }}" target="_blank" rel="noopener" class="px-4 py-2 rounded-full border border-black/10 text-sm font-semibold">Fire test click</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
