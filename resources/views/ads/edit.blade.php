@extends('layouts.app')

@section('title', 'Edit Ad — Ads Manager')

@section('content')
    <div class="max-w-6xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('ads.show', $ad) }}" class="text-sm text-neutral-500 hover:text-brand-ink">← Back to ad</a>

        <div class="mt-3 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="mt-1 text-3xl font-extrabold tracking-tight">Edit ad</h1>
                <p class="mt-1 text-neutral-600">{{ $ad->name }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="badge {{ $ad->statusBadgeClass() }}">{{ $ad->statusLabel() }}</span>
                @if($ad->isLive())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-bold">
                        <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span> Visible on Hirevo
                    </span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('ads.update', $ad) }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')
            @include('ads._form', ['isCreating' => false])
        </form>
    </div>
@endsection
