@extends('layouts.app')

@section('title', $lead->name.' — Ads Manager')

@section('content')
    <div class="max-w-4xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-neutral-500 hover:text-brand-ink">← Back to leads</a>

        <div class="mt-3 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $lead->name }}</h1>
                <p class="mt-1 text-neutral-600">
                    @if($lead->job_title){{ $lead->job_title }}@endif
                    @if($lead->job_title && $lead->company) · @endif
                    @if($lead->company){{ $lead->company }}@endif
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-5 py-2.5 rounded-full bg-brand-ink text-white font-semibold text-sm hover:opacity-90">Edit</a>
            </div>
        </div>

        <div class="mt-6 grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 bg-white rounded-3xl border border-black/5 p-6">
                <h3 class="font-extrabold text-lg">Contact details</h3>
                <dl class="mt-4 grid sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Email</dt>
                        <dd class="mt-1">{{ $lead->email ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Phone</dt>
                        <dd class="mt-1">{{ $lead->phone ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Company</dt>
                        <dd class="mt-1">{{ $lead->company ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Job title</dt>
                        <dd class="mt-1">{{ $lead->job_title ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Source</dt>
                        <dd class="mt-1 capitalize">{{ str_replace('_', ' ', $lead->source) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Status</dt>
                        <dd class="mt-1"><span class="badge {{ $lead->statusBadgeClass() }}">{{ $lead->status }}</span></dd>
                    </div>
                </dl>

                @if ($lead->campaign || $lead->ad || $lead->placement || $lead->referrer_url)
                    <div class="mt-6 pt-6 border-t border-black/5">
                        <h4 class="text-xs uppercase tracking-widest text-neutral-500 font-bold mb-3">Ad attribution</h4>
                        <dl class="grid sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-xs text-neutral-500 font-bold uppercase tracking-widest">Campaign</dt>
                                <dd class="mt-1">
                                    @if ($lead->campaign)
                                        <a href="{{ route('campaigns.show', $lead->campaign) }}" class="font-semibold hover:underline">{{ $lead->campaign->name }}</a>
                                    @else — @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-neutral-500 font-bold uppercase tracking-widest">Ad</dt>
                                <dd class="mt-1">
                                    @if ($lead->ad)
                                        <a href="{{ route('ads.show', $lead->ad) }}" class="font-semibold hover:underline">{{ $lead->ad->name }}</a>
                                    @else — @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-neutral-500 font-bold uppercase tracking-widest">Placement</dt>
                                <dd class="mt-1">{{ $lead->placement ? (\App\Models\Ad::PLACEMENTS[$lead->placement] ?? $lead->placement) : '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-neutral-500 font-bold uppercase tracking-widest">Referrer URL</dt>
                                <dd class="mt-1 break-all">
                                    @if ($lead->referrer_url)
                                        <a href="{{ $lead->referrer_url }}" target="_blank" rel="noopener" class="text-xs font-mono hover:underline">{{ $lead->referrer_url }}</a>
                                    @else — @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                @endif

                @if ($lead->notes)
                    <div class="mt-6 pt-6 border-t border-black/5">
                        <h4 class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Notes</h4>
                        <p class="mt-2 text-sm whitespace-pre-line">{{ $lead->notes }}</p>
                    </div>
                @endif

                @if ($lead->meta && is_array($lead->meta) && count($lead->meta))
                    <div class="mt-6 pt-6 border-t border-black/5">
                        <h4 class="text-xs uppercase tracking-widest text-neutral-500 font-bold">Extra fields</h4>
                        <pre class="mt-2 text-xs bg-brand-soft rounded-xl p-3 overflow-x-auto">{{ json_encode($lead->meta, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="bg-brand-ink text-white rounded-3xl p-6">
                    <p class="text-xs uppercase tracking-widest text-brand-yellow font-bold">Deal value</p>
                    <p class="mt-3 text-4xl font-black">${{ number_format((float) $lead->value, 0) }}</p>
                </div>
                <div class="bg-brand-yellow rounded-3xl p-6">
                    <p class="text-xs uppercase tracking-widest text-brand-ink/70 font-bold">Next follow-up</p>
                    <p class="mt-3 text-xl font-extrabold">
                        {{ $lead->next_followup_at ? $lead->next_followup_at->format('M j, Y g:i a') : 'Not scheduled' }}
                    </p>
                </div>
                <div class="bg-white rounded-3xl border border-black/5 p-6 text-sm text-neutral-600">
                    <p>Created {{ $lead->created_at->diffForHumans() }}</p>
                    <p class="mt-1">Last updated {{ $lead->updated_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
