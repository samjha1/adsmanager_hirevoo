@extends('layouts.app')

@section('title', $campaign->name.' — Ads Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('campaigns.index') }}" class="text-sm text-neutral-500 hover:text-brand-ink">← Campaigns</a>

        <div class="mt-2 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">{{ $campaign->name }}</h1>
                <p class="mt-1 text-neutral-600">
                    <span class="badge {{ $campaign->statusBadgeClass() }}">{{ $campaign->status }}</span>
                    · {{ $campaign->objectiveLabel() }}
                    · {{ $campaign->start_date?->format('M d, Y') ?? 'no start' }} → {{ $campaign->end_date?->format('M d, Y') ?? 'ongoing' }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('ads.create', ['campaign_id' => $campaign->id]) }}" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold">+ New Ad</a>
                <a href="{{ route('campaigns.edit', $campaign) }}" class="px-5 py-3 rounded-full bg-brand-ink text-white font-bold">Edit</a>
            </div>
        </div>

        @if ($campaign->description)
            <p class="mt-4 max-w-3xl text-neutral-700">{{ $campaign->description }}</p>
        @endif

        <div class="mt-6 grid grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Impressions</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($stats['impressions']) }}</p>
            </div>
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Clicks</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($stats['clicks']) }}</p>
            </div>
            <div class="bg-brand-yellow rounded-3xl p-5">
                <p class="text-xs uppercase tracking-widest text-brand-ink/70 font-bold">Leads</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($stats['leads']) }}</p>
            </div>
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">CTR</p>
                <p class="mt-2 text-2xl font-black">{{ $stats['ctr'] }}%</p>
            </div>
            <div class="bg-brand-ink text-white rounded-3xl p-5">
                <p class="text-xs uppercase tracking-widest text-brand-yellow font-bold">CPL</p>
                <p class="mt-2 text-2xl font-black">${{ number_format($stats['cpl'], 2) }}</p>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-3xl border border-black/5">
            <div class="flex items-center justify-between p-6 border-b border-black/5">
                <h3 class="font-extrabold text-lg">Ads in this campaign</h3>
                <a href="{{ route('ads.create', ['campaign_id' => $campaign->id]) }}" class="text-sm font-semibold text-brand-ink hover:underline">+ New Ad</a>
            </div>

            @if ($campaign->ads->isEmpty())
                <div class="p-10 text-center text-neutral-500">No ads yet. Create one to start running on Hirevo.</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-widest text-neutral-500 font-bold bg-brand-soft/40">
                        <tr>
                            <th class="px-6 py-3">Ad</th>
                            <th class="px-6 py-3">Placement</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Impr.</th>
                            <th class="px-6 py-3">Clicks</th>
                            <th class="px-6 py-3">Leads</th>
                            <th class="px-6 py-3">CTR</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($campaign->ads as $ad)
                            <tr class="border-t border-black/5 hover:bg-brand-soft/40">
                                <td class="px-6 py-3 font-semibold">
                                    <a href="{{ route('ads.show', $ad) }}" class="hover:underline">{{ $ad->name }}</a>
                                    <p class="text-xs text-neutral-500 font-normal mt-0.5 line-clamp-1">{{ $ad->headline }}</p>
                                </td>
                                <td class="px-6 py-3 text-neutral-700">{{ $ad->placementLabel() }}</td>
                                <td class="px-6 py-3"><span class="badge {{ $ad->statusBadgeClass() }}">{{ $ad->status }}</span></td>
                                <td class="px-6 py-3">{{ number_format($ad->impressions_count) }}</td>
                                <td class="px-6 py-3">{{ number_format($ad->clicks_count) }}</td>
                                <td class="px-6 py-3">{{ number_format($ad->leads_count) }}</td>
                                <td class="px-6 py-3">{{ $ad->ctr() }}%</td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('ads.edit', $ad) }}" class="text-sm font-semibold text-brand-ink hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
