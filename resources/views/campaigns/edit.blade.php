@extends('layouts.app')

@section('title', 'Edit Campaign — Ads Manager')

@section('content')
    <div class="max-w-3xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('campaigns.show', $campaign) }}" class="text-sm text-neutral-500 hover:text-brand-ink">← Back to campaign</a>

        <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Edit campaign</h1>

        <form method="POST" action="{{ route('campaigns.update', $campaign) }}" class="mt-6 bg-white rounded-3xl border border-black/5 p-6">
            @csrf
            @method('PUT')
            @include('campaigns._form')
        </form>
    </div>
@endsection
