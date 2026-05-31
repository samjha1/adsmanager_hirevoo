@extends('layouts.app')

@section('title', 'New Lead — Ads Manager')

@section('content')
    <div class="max-w-3xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-neutral-500 hover:text-brand-ink">← Back to leads</a>
        <h1 class="mt-3 text-3xl sm:text-4xl font-extrabold tracking-tight">Capture a new lead</h1>
        <p class="mt-1 text-neutral-600">Add the details, set a follow-up, and start qualifying.</p>

        @if ($errors->any())
            <div class="mt-6 rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm border border-rose-200">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-6 bg-white rounded-3xl border border-black/5 p-6 sm:p-8">
            <form method="POST" action="{{ route('leads.store') }}">
                @include('leads._form')
                <div class="mt-8 flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
                        Save lead
                    </button>
                    <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-neutral-500 hover:text-brand-ink">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
