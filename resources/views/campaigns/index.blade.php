@extends('layouts.app')

@section('title', 'Campaigns — Ads Manager')

@section('content')
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Campaigns</h1>
                <p class="mt-1 text-neutral-600">Group your ads by objective and budget.</p>
            </div>
            <a href="{{ route('campaigns.create') }}" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
                + New Campaign
            </a>
        </div>

        <form method="GET" class="mt-6 bg-white rounded-3xl border border-black/5 p-4 grid sm:grid-cols-3 gap-3">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search campaigns…" class="px-4 py-3 rounded-xl border border-black/10 sm:col-span-2 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            <select name="status" class="px-4 py-3 rounded-xl border border-black/10 bg-white">
                <option value="">All statuses</option>
                @foreach (\App\Models\Campaign::STATUSES as $s)
                    <option value="{{ $s }}" @selected($currentStatus === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>

        <div class="mt-6 bg-white rounded-3xl border border-black/5 overflow-hidden">
            @if ($campaigns->isEmpty())
                <div class="p-12 text-center">
                    <p class="text-neutral-500">No campaigns yet.</p>
                    <a href="{{ route('campaigns.create') }}" class="mt-4 inline-flex items-center px-5 py-3 rounded-full bg-brand-yellow text-brand-ink font-bold">Launch your first campaign</a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-left text-xs uppercase tracking-widest text-neutral-500 font-bold bg-brand-soft/40">
                        <tr>
                            <th class="px-6 py-3">Campaign</th>
                            <th class="px-6 py-3">Objective</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Ads</th>
                            <th class="px-6 py-3">Leads</th>
                            <th class="px-6 py-3">Budget / Spend</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($campaigns as $c)
                            <tr class="border-t border-black/5 hover:bg-brand-soft/40">
                                <td class="px-6 py-3 font-semibold">
                                    <a href="{{ route('campaigns.show', $c) }}" class="hover:underline">{{ $c->name }}</a>
                                    @if ($c->start_date || $c->end_date)
                                        <p class="text-xs text-neutral-500 font-normal mt-0.5">
                                            {{ $c->start_date?->format('M d') ?? '—' }} → {{ $c->end_date?->format('M d, Y') ?? 'ongoing' }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-neutral-700">{{ $c->objectiveLabel() }}</td>
                                <td class="px-6 py-3"><span class="badge {{ $c->statusBadgeClass() }}">{{ $c->status }}</span></td>
                                <td class="px-6 py-3">{{ $c->ads_count }}</td>
                                <td class="px-6 py-3">{{ $c->leads_count }}</td>
                                <td class="px-6 py-3">
                                    <p class="font-semibold">${{ number_format((float) $c->spend, 0) }} / ${{ number_format((float) $c->total_budget, 0) }}</p>
                                    <p class="text-xs text-neutral-500">${{ number_format((float) $c->daily_budget, 0) }}/day</p>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('campaigns.edit', $c) }}" class="text-sm font-semibold text-brand-ink hover:underline">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="mt-6">{{ $campaigns->links() }}</div>
    </div>
@endsection
