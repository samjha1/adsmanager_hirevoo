@extends('layouts.app')

@section('title', 'Leads — Ads Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-sm text-neutral-500 uppercase tracking-widest font-semibold">All Leads</p>
                <h1 class="mt-1 text-3xl sm:text-4xl font-extrabold tracking-tight">Leads</h1>
                <p class="mt-1 text-neutral-600">{{ $leads->total() }} {{ \Illuminate\Support\Str::plural('lead', $leads->total()) }} attributed to your ad account</p>
            </div>
            <a href="{{ route('leads.create') }}" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
                <svg class="w-4 h-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                Add Manual Lead
            </a>
        </div>

        <form method="GET" action="{{ route('leads.index') }}" class="mt-6 bg-white border border-black/5 rounded-3xl p-4 grid sm:grid-cols-5 gap-3">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search name, email, company…" class="px-4 py-3 rounded-xl border border-black/10 sm:col-span-2 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            <select name="campaign_id" class="px-4 py-3 rounded-xl border border-black/10 bg-white">
                <option value="">All campaigns</option>
                @foreach ($campaigns as $c)
                    <option value="{{ $c->id }}" @selected((int) $currentCampaign === $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="status" class="px-4 py-3 rounded-xl border border-black/10 bg-white">
                <option value="">All statuses</option>
                @foreach (\App\Models\Lead::STATUSES as $s)
                    <option value="{{ $s }}" @selected($currentStatus === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="source" class="px-4 py-3 rounded-xl border border-black/10 bg-white">
                <option value="">All sources</option>
                @foreach (\App\Models\Lead::SOURCES as $s)
                    <option value="{{ $s }}" @selected($currentSource === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                @endforeach
            </select>

            <div class="sm:col-span-5 flex flex-wrap items-center gap-2 pt-1">
                <button type="submit" class="inline-flex items-center px-4 py-2.5 rounded-full bg-brand-ink text-white text-sm font-semibold hover:opacity-90">
                    Apply filters
                </button>
                <button type="submit" name="export" value="csv" class="inline-flex items-center px-4 py-2.5 rounded-full border border-black/10 text-sm font-semibold hover:bg-brand-soft/40">
                    Download CSV
                </button>
                <button type="submit" name="export" value="xlsx" class="inline-flex items-center px-4 py-2.5 rounded-full border border-black/10 text-sm font-semibold hover:bg-brand-soft/40">
                    Download XLSX
                </button>
            </div>
        </form>

        <div class="mt-6 bg-white rounded-3xl border border-black/5 overflow-hidden">
            @if ($leads->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-neutral-500">No leads match your filters.</p>
                    <p class="mt-1 text-sm text-neutral-400">Tip: copy your ad's embed snippet from <a href="{{ route('integrations.index') }}" class="font-semibold underline">Integrations</a> and place it on Hirevo to start collecting leads.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-widest text-neutral-500 font-bold bg-brand-soft/60">
                        <tr>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3">Contact</th>
                            <th class="px-6 py-3">Source</th>
                            <th class="px-6 py-3">Campaign / Ad</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Captured</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($leads as $lead)
                            <tr class="border-t border-black/5 hover:bg-brand-soft/40">
                                <td class="px-6 py-3 font-semibold">
                                    <a href="{{ route('leads.show', $lead) }}" class="hover:underline">{{ $lead->name }}</a>
                                    @if ($lead->company)
                                        <p class="text-xs text-neutral-500 font-normal">{{ $lead->company }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-neutral-700">
                                    @if ($lead->email)<p>{{ $lead->email }}</p>@endif
                                    @if ($lead->phone)<p class="text-xs text-neutral-500">{{ $lead->phone }}</p>@endif
                                    @unless ($lead->email || $lead->phone)<span class="text-neutral-400">—</span>@endunless
                                </td>
                                <td class="px-6 py-3 text-neutral-700 capitalize">
                                    {{ str_replace('_', ' ', $lead->source) }}
                                    @if ($lead->placement)
                                        <p class="text-xs text-neutral-500 normal-case">{{ \App\Models\Ad::PLACEMENTS[$lead->placement] ?? $lead->placement }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-neutral-700">
                                    @if ($lead->campaign)
                                        <a href="{{ route('campaigns.show', $lead->campaign) }}" class="hover:underline">{{ $lead->campaign->name }}</a>
                                    @else
                                        <span class="text-neutral-400">—</span>
                                    @endif
                                    @if ($lead->ad)
                                        <p class="text-xs text-neutral-500"><a href="{{ route('ads.show', $lead->ad) }}" class="hover:underline">{{ $lead->ad->name }}</a></p>
                                    @endif
                                </td>
                                <td class="px-6 py-3"><span class="badge {{ $lead->statusBadgeClass() }}">{{ $lead->status }}</span></td>
                                <td class="px-6 py-3 text-neutral-500">{{ $lead->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('leads.show', $lead) }}" class="text-sm font-semibold text-brand-ink hover:underline">View</a>
                                    <span class="text-neutral-300 mx-1">|</span>
                                    <a href="{{ route('leads.edit', $lead) }}" class="text-sm font-semibold text-brand-ink hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-black/5">
                    {{ $leads->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
