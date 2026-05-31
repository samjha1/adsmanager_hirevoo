@extends('layouts.app')

@section('title', 'Dashboard — Ads Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-sm text-neutral-500 uppercase tracking-widest font-semibold">Ad Account</p>
                <h1 class="mt-1 text-3xl sm:text-4xl font-extrabold tracking-tight">Hello, {{ auth()->user()->name }}.</h1>
                <p class="mt-1 text-neutral-600">Here's how your Hirevo ads are performing today.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('campaigns.create') }}" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
                    <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    New Campaign
                </a>
                <a href="{{ route('ads.create') }}" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold hover:opacity-90">
                    Create Ad
                </a>
            </div>
        </div>

        {{-- Top KPIs (Facebook Ads Manager style) --}}
        <div class="mt-8 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl p-6 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Impressions</p>
                <p class="mt-3 text-4xl font-black">{{ number_format($metrics['impressions']) }}</p>
                <p class="mt-1 text-xs text-neutral-500">Across {{ $metrics['active_ads'] }} active ads</p>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Clicks</p>
                <p class="mt-3 text-4xl font-black">{{ number_format($metrics['clicks']) }}</p>
                <p class="mt-1 text-xs text-neutral-500">CTR {{ $metrics['ctr'] }}%</p>
            </div>
            <div class="bg-brand-yellow rounded-3xl p-6">
                <p class="text-xs uppercase tracking-widest text-brand-ink/70 font-bold">Leads from Ads</p>
                <p class="mt-3 text-4xl font-black">{{ number_format($metrics['leads']) }}</p>
                <p class="mt-1 text-xs text-brand-ink/70">{{ $metrics['conv_rate'] }}% conversion</p>
            </div>
            <div class="bg-brand-ink text-white rounded-3xl p-6">
                <p class="text-xs uppercase tracking-widest text-brand-yellow font-bold">Total Spend</p>
                <p class="mt-3 text-4xl font-black">${{ number_format($metrics['spend'], 2) }}</p>
                <p class="mt-1 text-xs text-white/60">CPL ${{ number_format($metrics['cpl'], 2) }} · CPC ${{ number_format($metrics['cpc'], 2) }}</p>
            </div>
        </div>

        {{-- Secondary metrics row --}}
        <div class="mt-4 grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Active Campaigns</p>
                <p class="mt-2 text-2xl font-black">{{ $metrics['active_campaigns'] }} <span class="text-sm font-medium text-neutral-400">/ {{ $metrics['total_campaigns'] }}</span></p>
            </div>
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Active Ads</p>
                <p class="mt-2 text-2xl font-black">{{ $metrics['active_ads'] }} <span class="text-sm font-medium text-neutral-400">/ {{ $metrics['total_ads'] }}</span></p>
            </div>
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">All Leads (incl. manual)</p>
                <p class="mt-2 text-2xl font-black">{{ number_format($metrics['all_leads']) }}</p>
            </div>
            <div class="bg-white rounded-3xl p-5 border border-black/5">
                <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Click-through-rate</p>
                <p class="mt-2 text-2xl font-black">{{ $metrics['ctr'] }}%</p>
            </div>
        </div>

        {{-- Top campaigns + recent leads --}}
        <div class="mt-6 grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white rounded-3xl border border-black/5 p-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-extrabold text-lg">Top campaigns by leads</h3>
                    <a href="{{ route('campaigns.index') }}" class="text-sm font-semibold text-brand-ink hover:underline">All campaigns →</a>
                </div>

                @if ($topCampaigns->isEmpty())
                    <div class="mt-6 p-8 text-center text-sm text-neutral-500 border-2 border-dashed border-neutral-200 rounded-2xl">
                        <p>No campaigns yet.</p>
                        <a href="{{ route('campaigns.create') }}" class="mt-3 inline-flex items-center px-5 py-2.5 rounded-full bg-brand-yellow text-brand-ink font-semibold">Launch your first campaign</a>
                    </div>
                @else
                    <div class="mt-5 divide-y divide-black/5">
                        @foreach ($topCampaigns as $c)
                            <div class="py-3 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold truncate">
                                        <a href="{{ route('campaigns.show', $c) }}" class="hover:underline">{{ $c->name }}</a>
                                    </p>
                                    <p class="text-xs text-neutral-500">{{ $c->objectiveLabel() }} · {{ $c->ads_count }} {{ \Illuminate\Support\Str::plural('ad', $c->ads_count) }}</p>
                                </div>
                                <div class="flex items-center gap-4 text-right">
                                    <div>
                                        <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Leads</p>
                                        <p class="font-black">{{ $c->leads_count }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Spend</p>
                                        <p class="font-black">${{ number_format((float) $c->spend, 0) }}</p>
                                    </div>
                                    <span class="badge {{ $c->statusBadgeClass() }}">{{ $c->status }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="bg-brand-ink text-white rounded-3xl p-6">
                    <p class="text-xs uppercase tracking-widest text-brand-yellow font-bold">Cost per lead</p>
                    <p class="mt-3 text-4xl font-black">${{ number_format($metrics['cpl'], 2) }}</p>
                    <p class="mt-1 text-sm text-white/70">{{ $metrics['leads'] }} ad-leads · ${{ number_format($metrics['spend'], 2) }} spend</p>
                </div>
                <div class="bg-brand-yellow rounded-3xl p-6">
                    <p class="text-xs uppercase tracking-widest text-brand-ink/70 font-bold">Cost per click</p>
                    <p class="mt-3 text-4xl font-black">${{ number_format($metrics['cpc'], 2) }}</p>
                    <p class="mt-1 text-sm text-brand-ink/70">{{ number_format($metrics['clicks']) }} clicks tracked</p>
                </div>
            </div>
        </div>

        {{-- Recent leads from ads --}}
        <div class="mt-6 bg-white rounded-3xl border border-black/5">
            <div class="flex items-center justify-between p-6 border-b border-black/5">
                <h3 class="font-extrabold text-lg">Recent leads</h3>
                <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-brand-ink hover:underline">View all →</a>
            </div>

            @if ($recentLeads->isEmpty())
                <div class="p-10 text-center">
                    <p class="text-neutral-500">No leads yet — they'll appear here as soon as your ads start running on Hirevo.</p>
                    <a href="{{ route('integrations.index') }}" class="mt-4 inline-flex items-center px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold">Get the Hirevo embed snippet</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-widest text-neutral-500 font-bold">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Source</th>
                            <th class="px-6 py-3">Campaign</th>
                            <th class="px-6 py-3">Ad</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($recentLeads as $lead)
                            <tr class="border-t border-black/5 hover:bg-brand-soft/40">
                                <td class="px-6 py-3 font-semibold">{{ $lead->name }}</td>
                                <td class="px-6 py-3 text-neutral-700 capitalize">{{ str_replace('_', ' ', $lead->source) }}</td>
                                <td class="px-6 py-3 text-neutral-700">{{ $lead->campaign?->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-neutral-700">{{ $lead->ad?->name ?? '—' }}</td>
                                <td class="px-6 py-3"><span class="badge {{ $lead->statusBadgeClass() }}">{{ $lead->status }}</span></td>
                                <td class="px-6 py-3 text-neutral-500">{{ $lead->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('leads.show', $lead) }}" class="text-sm font-semibold text-brand-ink hover:underline">View →</a>
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
