@extends('layouts.app')

@section('title', 'Ads — Ads Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Ads</h1>
                <p class="mt-1 text-neutral-600">Every creative running on Hirevo.</p>
            </div>
            <a href="{{ route('ads.create') }}" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">+ New Ad</a>
        </div>

        <form method="GET" class="mt-6 bg-white rounded-3xl border border-black/5 p-4 grid sm:grid-cols-4 gap-3">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search ads…" class="px-4 py-3 rounded-xl border border-black/10 sm:col-span-2 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            <select name="campaign_id" class="px-4 py-3 rounded-xl border border-black/10 bg-white">
                <option value="">All campaigns</option>
                @foreach ($campaigns as $c)
                    <option value="{{ $c->id }}" @selected((int) $currentCampaign === $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-3 rounded-xl border border-black/10 bg-white">
                <option value="">All statuses</option>
                @foreach (\App\Models\Ad::statusLabels() as $value => $label)
                    <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <div class="mt-6 bg-white rounded-3xl border border-black/5 overflow-hidden">
            @if ($ads->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-neutral-500">No ads yet.</p>
                    <a href="{{ route('ads.create') }}" class="mt-4 inline-flex items-center px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold">Create your first ad</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-widest text-neutral-500 font-bold bg-brand-soft/40">
                        <tr>
                            <th class="px-6 py-3">Ad</th>
                            <th class="px-6 py-3">Campaign</th>
                            <th class="px-6 py-3">Placement</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Impr.</th>
                            <th class="px-6 py-3">Clicks</th>
                            <th class="px-6 py-3">Leads</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($ads as $ad)
                            <tr class="border-t border-black/5 hover:bg-brand-soft/40">
                                <td class="px-6 py-3 font-semibold">
                                    <a href="{{ route('ads.show', $ad) }}" class="hover:underline">{{ $ad->name }}</a>
                                    <p class="text-xs text-neutral-500 font-normal mt-0.5 line-clamp-1">{{ $ad->headline }}</p>
                                </td>
                                <td class="px-6 py-3 text-neutral-700">
                                    <a href="{{ route('campaigns.show', $ad->campaign) }}" class="hover:underline">{{ $ad->campaign?->name }}</a>
                                </td>
                                <td class="px-6 py-3 text-neutral-700">{{ $ad->placementLabel() }}</td>
                                <td class="px-6 py-3">
                                    <span class="badge {{ $ad->statusBadgeClass() }}">{{ $ad->statusLabel() }}</span>
                                    @if($ad->status === 'active')
                                        <span class="block text-[10px] text-green-700 font-bold mt-0.5">Hirevo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">{{ number_format($ad->impressions_count) }}</td>
                                <td class="px-6 py-3">{{ number_format($ad->clicks_count) }}</td>
                                <td class="px-6 py-3">{{ number_format($ad->leads_count) }}</td>
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

        <div class="mt-6">{{ $ads->links() }}</div>
    </div>
@endsection
