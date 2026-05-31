@extends('layouts.auth')

@section('title', 'Log In — Ads Manager')

@section('content')
    <h2 class="text-3xl font-extrabold tracking-tight">Welcome back</h2>
    <p class="mt-2 text-neutral-600">Log in to your Ads Manager workspace.</p>

    @if (session('status'))
        <div class="mt-6 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-6 rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm border border-rose-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-neutral-700 mb-1.5">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
        </div>
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-neutral-700">Password</label>
            </div>
            <input id="password" name="password" type="password" required
                   class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
        </div>

        <label class="flex items-center gap-2 text-sm text-neutral-700">
            <input type="checkbox" name="remember" class="rounded border-neutral-300 text-brand-ink focus:ring-brand-ink/20">
            Remember me on this device
        </label>

        <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90 transition">
            Log In
        </button>
    </form>

    <p class="mt-8 text-sm text-neutral-600">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-semibold text-brand-ink hover:underline">Create one — it's free</a>
    </p>
@endsection
