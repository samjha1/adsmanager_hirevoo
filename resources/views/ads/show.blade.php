@extends('layouts.app')

@section('title', $ad->name.' — Ads Manager')

@section('content')
    @php
        $base = rtrim(config('app.url'), '/');
        $areas = $ad->target_area ? array_map('trim', explode(',', $ad->target_area)) : [];
        $ageGroups = $ad->target_age_group ? array_map('trim', explode(',', $ad->target_age_group)) : [];
        $audiences = $ad->target_audience ? array_map('trim', explode(',', $ad->target_audience)) : [];
    @endphp
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('ads.index') }}" class="text-sm text-neutral-500 hover:text-brand-ink">← Ads</a>

        <div class="mt-2 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">{{ $ad->name }}</h1>
                <p class="mt-1 text-neutral-600">
                    <span class="badge {{ $ad->statusBadgeClass() }}">{{ $ad->statusLabel() }}</span>
                    @if($ad->isLive())
                        <span class="inline-flex items-center gap-1 text-green-700 text-sm font-bold">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span> Live
                        </span>
                    @elseif($ad->status === 'active' && $ad->campaign && $ad->campaign->status !== 'active')
                        <span class="text-amber-700 text-sm font-semibold">Approved — campaign inactive</span>
                    @endif
                    · {{ $ad->placementLabel() }}
                    · Campaign:
                    <a href="{{ route('campaigns.show', $ad->campaign) }}" class="font-semibold hover:underline">{{ $ad->campaign->name }}</a>
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                @if($ad->isLive())
                    <span class="px-5 py-3 rounded-full bg-green-100 text-green-900 text-sm font-bold">Live on Hirevo</span>
                @elseif($ad->status === 'pending_review')
                    <span class="px-5 py-3 rounded-full bg-orange-100 text-orange-900 text-sm font-bold">In review</span>
                @elseif(in_array($ad->status, ['draft', 'paused'], true))
                    <form method="POST" action="{{ route('ads.submit-review', $ad) }}">
                        @csrf
                        <button type="submit" class="px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold">Submit for review</button>
                    </form>
                @elseif($ad->status === 'active')
                    <span class="px-5 py-3 rounded-full bg-amber-100 text-amber-900 text-sm font-bold">Approved — activate campaign to show</span>
                @endif
                <a href="{{ route('integrations.index') }}#ad-{{ $ad->id }}" class="px-5 py-3 rounded-full border border-black/10 font-bold">Embed snippet</a>
                <a href="{{ route('ads.edit', $ad) }}" class="px-5 py-3 rounded-full bg-brand-ink text-white font-bold">Edit</a>
            </div>
        </div>

        @if($ad->status === 'active')
            <p class="mt-4 text-sm text-neutral-600 max-w-2xl">Approved by the Hirevo admin panel — live on <strong>{{ $ad->placementLabel() }}</strong>.</p>
        @elseif($ad->status === 'pending_review')
            <p class="mt-4 text-sm text-neutral-600 max-w-2xl">Waiting for approval in the <strong>Hirevo admin panel</strong> (Sponsored ads). No manual embed needed after approval.</p>
        @endif

        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Impressions</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($ad->impressions_count) }}</p>
            </div>
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Clicks</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($ad->clicks_count) }}</p>
                <p class="text-xs text-neutral-500">CTR {{ $ad->ctr() }}%</p>
            </div>
            <div class="bg-brand-yellow rounded-3xl p-5">
                <p class="text-xs uppercase tracking-widest text-brand-ink/70 font-bold">Leads</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($ad->leads_count) }}</p>
                <p class="text-xs text-brand-ink/70">{{ $ad->conversionRate() }}% conv.</p>
            </div>
            <div class="bg-brand-ink text-white rounded-3xl p-5">
                <p class="text-xs uppercase tracking-widest text-brand-yellow font-bold">Public Key</p>
                <p class="mt-2 font-mono text-xs break-all">{{ $ad->public_key }}</p>
            </div>
        </div>

        <div class="mt-4 bg-white rounded-3xl p-5 border border-black/5">
            <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Audience targeting</p>
            <div class="mt-3 grid sm:grid-cols-3 gap-3 text-sm">
                <div class="rounded-xl bg-brand-soft/50 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-widest text-neutral-500 font-bold">Area</p>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @forelse($areas as $item)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-black/10">{{ $item }}</span>
                        @empty
                            <span class="text-sm font-semibold text-brand-ink">Not set</span>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-xl bg-brand-soft/50 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-widest text-neutral-500 font-bold">Age group</p>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @forelse($ageGroups as $item)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-black/10">{{ $item }}</span>
                        @empty
                            <span class="text-sm font-semibold text-brand-ink">Not set</span>
                        @endforelse
                    </div>
                </div>
                <div class="rounded-xl bg-brand-soft/50 px-4 py-3">
                    <p class="text-[11px] uppercase tracking-widest text-neutral-500 font-bold">Audience</p>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @forelse($audiences as $item)
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-white border border-black/10">{{ $item }}</span>
                        @empty
                            <span class="text-sm font-semibold text-brand-ink">Not set</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-1 bg-white rounded-3xl p-6 border border-black/5">
                <p class="text-xs uppercase tracking-widest font-bold text-neutral-500 mb-3">Creative</p>
                <div class="rounded-2xl bg-white border border-black/10 overflow-hidden">
                    <div class="aspect-[16/9] bg-brand-soft">
                        @if ($ad->image_url)
                            <img src="{{ $ad->image_url }}" alt="" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-neutral-400 text-xs uppercase tracking-widest">No image</div>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-[10px] uppercase tracking-widest text-neutral-500 font-bold">Sponsored</p>
                        <h4 class="mt-1 font-extrabold leading-tight">{{ $ad->headline }}</h4>
                        <p class="mt-1 text-sm text-neutral-600">{{ $ad->body }}</p>
                        <a href="{{ $base }}/api/track/click/{{ $ad->public_key }}" target="_blank" rel="noopener" class="mt-3 inline-block px-4 py-2 rounded-full bg-brand-ink text-white text-xs font-bold">{{ $ad->cta_label }}</a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl border border-black/5">
                <div class="flex items-center justify-between p-6 border-b border-black/5">
                    <h3 class="font-extrabold text-lg">Recent leads from this ad</h3>
                    <a href="{{ route('leads.index', ['ad_id' => $ad->id]) }}" class="text-sm font-semibold text-brand-ink hover:underline">View all →</a>
                </div>

                @if ($ad->leads->isEmpty())
                    <div class="p-10 text-center text-neutral-500">No leads yet from this ad.</div>
                @else
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-widest text-neutral-500 font-bold bg-brand-soft/40">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Company</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Captured</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($ad->leads as $lead)
                            <tr class="border-t border-black/5 hover:bg-brand-soft/40">
                                <td class="px-6 py-3 font-semibold">
                                    <a href="{{ route('leads.show', $lead) }}" class="hover:underline">{{ $lead->name }}</a>
                                </td>
                                <td class="px-6 py-3 text-neutral-700">{{ $lead->email }}</td>
                                <td class="px-6 py-3 text-neutral-700">{{ $lead->company }}</td>
                                <td class="px-6 py-3"><span class="badge {{ $lead->statusBadgeClass() }}">{{ $lead->status }}</span></td>
                                <td class="px-6 py-3 text-neutral-500">{{ $lead->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
