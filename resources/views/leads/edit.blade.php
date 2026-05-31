@extends('layouts.app')

@section('title', 'Edit Lead — Ads Manager')

@section('content')
    <div class="max-w-3xl mx-auto px-5 lg:px-8 py-8">
        <a href="{{ route('leads.show', $lead) }}" class="text-sm font-semibold text-neutral-500 hover:text-brand-ink">← Back to lead</a>
        <h1 class="mt-3 text-3xl sm:text-4xl font-extrabold tracking-tight">Edit lead</h1>

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
            <form method="POST" action="{{ route('leads.update', $lead) }}">
                @method('PUT')
                @include('leads._form')
                <div class="mt-8 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
                            Save changes
                        </button>
                        <a href="{{ route('leads.show', $lead) }}" class="text-sm font-semibold text-neutral-500 hover:text-brand-ink">Cancel</a>
                    </div>
                </div>
            </form>

            <form method="POST" action="{{ route('leads.destroy', $lead) }}" class="mt-6 pt-6 border-t border-black/5"
                  onsubmit="return confirm('Delete this lead permanently?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">Delete this lead</button>
            </form>
        </div>
    </div>
@endsection
