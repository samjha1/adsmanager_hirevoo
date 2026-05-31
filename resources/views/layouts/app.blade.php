<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ads Manager')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { brand: { yellow: '#FFFC00', ink: '#0A0A0A', soft: '#F4F4F2' } },
                    fontFamily: { sans: ['Inter','ui-sans-serif','system-ui','Segoe UI','Helvetica','Arial'] },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .badge { display:inline-flex; align-items:center; padding:.18rem .55rem; border-radius:999px; font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; }
        .badge-blue   { background:#DBEAFE; color:#1E40AF; }
        .badge-purple { background:#EDE9FE; color:#5B21B6; }
        .badge-amber  { background:#FEF3C7; color:#92400E; }
        .badge-orange { background:#FFEDD5; color:#9A3412; }
        .badge-green  { background:#D1FAE5; color:#065F46; }
        .badge-gray   { background:#E5E7EB; color:#374151; }
    </style>
    @stack('head')
</head>
<body class="min-h-screen bg-brand-soft text-brand-ink">
    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="hidden lg:flex flex-col w-64 bg-brand-ink text-neutral-200 p-5 sticky top-0 h-screen">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 mb-10">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-yellow text-brand-ink font-black text-lg">A</span>
                <span class="font-extrabold tracking-tight text-lg text-white">Ads Manager</span>
            </a>

            @php
                $isDashboard = request()->routeIs('dashboard');
                $isCampaigns = request()->routeIs('campaigns.*');
                $isAds = request()->routeIs('ads.*');
                $isLeads = request()->routeIs('leads.*');
                $isIntegrations = request()->routeIs('integrations.*');
            @endphp

            <nav class="space-y-1 text-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $isDashboard ? 'bg-white/10 text-white font-semibold' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3v-6h6v6h3a1 1 0 001-1V10" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('campaigns.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $isCampaigns ? 'bg-white/10 text-white font-semibold' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7h18M3 12h18M3 17h12" stroke-linecap="round"/></svg>
                    Campaigns
                </a>
                <a href="{{ route('ads.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $isAds ? 'bg-white/10 text-white font-semibold' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l18-7-7 18-2-8-9-3z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Ads
                </a>
                <a href="{{ route('leads.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $isLeads ? 'bg-white/10 text-white font-semibold' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75M9 11a4 4 0 100-8 4 4 0 000 8z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Leads
                </a>
                <a href="{{ route('integrations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ $isIntegrations ? 'bg-white/10 text-white font-semibold' : 'text-neutral-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Integrations
                </a>
            </nav>

            <div class="mt-auto pt-6 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 rounded-full bg-brand-yellow text-brand-ink items-center justify-center font-black text-sm">{{ auth()->user()?->initials() }}</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-white text-sm truncate">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-neutral-400 truncate">{{ auth()->user()?->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-left text-sm text-neutral-300 hover:text-white px-3 py-2 rounded-lg hover:bg-white/5">
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="lg:hidden bg-brand-ink text-white px-5 h-14 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-brand-yellow text-brand-ink font-black">A</span>
                    <span class="font-extrabold tracking-tight">Ads Manager</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-neutral-300">Log Out</button>
                </form>
            </header>

            <main class="flex-1">
                @if (session('status'))
                    <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-6">
                        <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm border border-emerald-200">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
