<header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-black/5">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-5 lg:px-8 h-16">
        <a href="{{ route('home') }}" class="flex items-center gap-2 group">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-yellow text-brand-ink font-black text-lg shadow-sm group-hover:rotate-[-3deg] transition">A</span>
            <span class="font-extrabold tracking-tight text-lg">Ads Manager</span>
        </a>

        <nav class="hidden lg:flex items-center gap-8 text-[15px] font-medium text-neutral-700">
            <a href="#why" class="hover:text-brand-ink">Why Ads Manager</a>
            <a href="#audience" class="hover:text-brand-ink">Reach</a>
            <a href="#stories" class="hover:text-brand-ink">Success Stories</a>
            <a href="#features" class="hover:text-brand-ink">Ad Products</a>
        </nav>

        <div class="flex items-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-full bg-brand-ink text-white text-sm font-semibold hover:opacity-90">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center text-sm font-semibold text-neutral-800 hover:text-brand-ink">Log In</a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 rounded-full bg-brand-ink text-white text-sm font-semibold hover:opacity-90">Get Started</a>
            @endauth
        </div>
    </div>
</header>
