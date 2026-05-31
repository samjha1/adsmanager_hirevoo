@extends('layouts.app')

@section('title', 'New Campaign — Ads Manager')

@section('content')
    <div class="max-w-3xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('campaigns.index') }}" class="text-sm text-neutral-500 hover:text-brand-ink">← Back to campaigns</a>

        <h1 class="mt-2 text-3xl font-extrabold tracking-tight">New campaign</h1>
        <p class="mt-1 text-neutral-600">Objective is set to Lead Generation for now. Set your budget and you'll add ads next.</p>

        <form method="POST" action="{{ route('campaigns.store') }}" class="mt-6 bg-white rounded-3xl border border-black/5 p-6">
            @csrf
            @include('campaigns._form')
        </form>
    </div>
@endsection
