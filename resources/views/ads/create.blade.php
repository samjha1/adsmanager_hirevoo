@extends('layouts.app')

@section('title', 'New Ad — Ads Manager')

@section('content')
    <div class="max-w-6xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('ads.index') }}" class="text-sm text-neutral-500 hover:text-brand-ink">← Back to ads</a>

        <div class="mt-3 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Create ad</h1>
                <p class="mt-1 text-neutral-600 max-w-xl">Build your creative, choose where it runs on Hirevo, then submit for review. It goes <strong>live</strong> after approval in the Hirevo admin panel.</p>
            </div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-neutral-500">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-yellow text-brand-ink">1</span>
                <span class="text-brand-ink">Creative</span>
                <span class="text-neutral-300">→</span>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-soft text-neutral-500">2</span>
                <span>Review</span>
                <span class="text-neutral-300">→</span>
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-soft text-neutral-500">3</span>
                <span>Live</span>
            </div>
        </div>

        <div class="mt-5 rounded-2xl border border-brand-yellow/40 bg-brand-yellow/15 px-4 py-3 text-sm text-brand-ink">
            <strong>New ads start as “In review”.</strong> You cannot set “Live on Hirevo” yourself — an admin approves sponsored ads, then this status updates automatically.
        </div>

        <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            <input type="hidden" name="status" value="pending_review">
            @include('ads._form', ['isCreating' => true])
        </form>
    </div>
@endsection
