@extends('layouts.auth')

@section('title', 'Create Account — Ads Manager')

@section('content')
    <h2 class="text-3xl font-extrabold tracking-tight">Create your ad account</h2>
    <p class="mt-2 text-neutral-600">Launch your first Hirevo campaign in under 60 seconds.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm border border-rose-200">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-sm font-semibold text-neutral-700 mb-1.5">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
        </div>
        <div>
            <label for="email" class="block text-sm font-semibold text-neutral-700 mb-1.5">Work email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
        </div>
        <div>
            <label for="company" class="block text-sm font-semibold text-neutral-700 mb-1.5">Company <span class="text-neutral-400 font-normal">(optional)</span></label>
            <input id="company" name="company" type="text" value="{{ old('company') }}"
                   class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-semibold text-neutral-700 mb-1.5">Password</label>
                <input id="password" name="password" type="password" required minlength="8"
                       class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-neutral-700 mb-1.5">Confirm</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8"
                       class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
            </div>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90 transition">
            Create my workspace
        </button>
    </form>

    <p class="mt-8 text-sm text-neutral-600">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-brand-ink hover:underline">Log in</a>
    </p>
@endsection
