@extends('layouts.marketing')

@section('title', 'Ads Manager — Spend Smarter on Hirevo')

@section('content')
    {{-- =================== HERO =================== --}}
    <section class="relative overflow-hidden bg-brand-yellow">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 pt-20 pb-28 lg:pt-28 lg:pb-36 relative">
            <div class="grid lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7">
                    <span class="pill inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-ink text-brand-yellow text-xs font-bold uppercase">
                        New • Now reaching Hirevo's high-intent audience
                    </span>
                    <h1 class="display-headline mt-6 text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black text-brand-ink">
                        Ads&nbsp;Manager.<br>
                        <em class="not-italic font-black">Spend&nbsp;Smarter.</em>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-brand-ink/80 max-w-xl">
                        Launch targeted ads on Hirevo, capture qualified leads automatically, and watch every impression, click, and conversion in real time.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="ghost-btn inline-flex items-center px-7 py-4 rounded-full bg-brand-ink text-white font-bold shadow-lg hover:shadow-xl">
                            Launch a Campaign
                            <svg class="ml-2 w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="ghost-btn inline-flex items-center px-7 py-4 rounded-full bg-white text-brand-ink font-bold shadow-sm border border-black/10 hover:bg-brand-soft">
                            Log In
                        </a>
                    </div>

                    <div class="mt-10 flex items-center gap-3 text-brand-ink/70 text-sm">
                        <div class="flex -space-x-2">
                            <span class="w-8 h-8 rounded-full bg-brand-ink text-brand-yellow flex items-center justify-center text-[11px] font-bold border-2 border-brand-yellow">SK</span>
                            <span class="w-8 h-8 rounded-full bg-white text-brand-ink flex items-center justify-center text-[11px] font-bold border-2 border-brand-yellow">AR</span>
                            <span class="w-8 h-8 rounded-full bg-brand-ink text-brand-yellow flex items-center justify-center text-[11px] font-bold border-2 border-brand-yellow">MJ</span>
                        </div>
                        Trusted by performance marketers across 50+ industries.
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="relative">
                        <div class="absolute -inset-3 rounded-[28px] bg-brand-ink/10 blur-xl"></div>
                        <div class="relative rounded-[28px] bg-brand-ink text-white p-6 shadow-2xl">
                            <div class="flex items-center justify-between">
                                <span class="text-xs uppercase tracking-widest text-brand-yellow font-bold">Live Ads Performance</span>
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-white/10 text-white">Today</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-3xl font-black">184K</p>
                                    <p class="text-[10px] uppercase tracking-widest text-white/50">Impressions</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-black">12.4K</p>
                                    <p class="text-[10px] uppercase tracking-widest text-white/50">Clicks</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-black">438</p>
                                    <p class="text-[10px] uppercase tracking-widest text-white/50">Leads</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-black text-brand-yellow">$3.42</p>
                                    <p class="text-[10px] uppercase tracking-widest text-white/50">Cost / Lead</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                @foreach ([
                                    ['Hirevo Homepage Hero', '6.7K imps', '$2.10 CPL', 'bg-emerald-400'],
                                    ['Job-listings Sidebar', '4.2K imps', '$3.95 CPL', 'bg-amber-400'],
                                    ['Candidate Dashboard', '1.5K imps', '$5.22 CPL', 'bg-orange-400'],
                                ] as $row)
                                    <div class="flex items-center justify-between bg-white/5 rounded-2xl p-3">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex h-9 w-9 rounded-xl bg-brand-yellow text-brand-ink items-center justify-center font-black text-sm">{{ strtoupper(substr($row[0], 0, 2)) }}</span>
                                            <div>
                                                <p class="font-semibold text-sm">{{ $row[0] }}</p>
                                                <p class="text-[11px] uppercase tracking-wider text-white/50">{{ $row[1] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-bold">{{ $row[2] }}</p>
                                            <span class="inline-block w-12 h-1.5 rounded-full {{ $row[3] }}"></span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute -bottom-10 -right-10 w-72 h-72 rounded-full bg-brand-ink/10 blur-3xl pointer-events-none"></div>
    </section>

    {{-- =================== STATS / WHY =================== --}}
    <section id="why" class="bg-white">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-20">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">Real growth proven by independent data</h2>

            <div class="mt-10 grid sm:grid-cols-3 gap-5">
                <div class="rounded-3xl bg-brand-soft p-8">
                    <p class="text-sm font-semibold text-neutral-500 uppercase tracking-widest">Highest ROAS</p>
                    <p class="mt-3 text-5xl font-black tracking-tight">3.8x</p>
                    <p class="mt-3 text-sm text-neutral-600">Highest measured return on ad spend among lead-gen ad platforms.<sup class="text-neutral-400">¹</sup></p>
                </div>
                <div class="rounded-3xl bg-brand-yellow p-8">
                    <p class="text-sm font-semibold text-brand-ink/70 uppercase tracking-widest">#1 ROAS Lift</p>
                    <p class="mt-3 text-5xl font-black tracking-tight">90d</p>
                    <p class="mt-3 text-sm text-brand-ink/80">From a 1-day to 90-day attribution window — sustained pipeline growth.<sup class="text-brand-ink/50">²</sup></p>
                </div>
                <div class="rounded-3xl bg-brand-ink text-white p-8">
                    <p class="text-sm font-semibold text-brand-yellow uppercase tracking-widest">#1 CPA &amp; ROAS</p>
                    <p class="mt-3 text-5xl font-black tracking-tight">2024</p>
                    <p class="mt-3 text-sm text-white/70">Top channel for customer acquisition for high-growth advertisers.<sup class="text-white/50">³</sup></p>
                </div>
            </div>
        </div>
    </section>

    {{-- =================== AUDIENCE / NUMBERS =================== --}}
    <section id="audience" class="bg-brand-soft">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-20">
            <div class="grid lg:grid-cols-12 gap-10">
                <div class="lg:col-span-4">
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight"><em class="not-italic">Reach a highly engaged, untapped audience</em></h2>
                    <p class="mt-4 text-neutral-600">Place your ads in front of Hirevo's high-intent professional audience — actively searching, applying, and hiring.</p>
                </div>

                <div class="lg:col-span-8 grid grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="bg-white rounded-3xl p-6">
                        <p class="text-5xl font-black">40%</p>
                        <p class="mt-3 text-xs uppercase tracking-widest text-neutral-500 font-semibold">Of Hirevo users aren't on other ad platforms⁴</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6">
                        <p class="text-5xl font-black">946k</p>
                        <p class="mt-3 text-xs uppercase tracking-widest text-neutral-500 font-semibold">Monthly active professionals reached⁵</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6">
                        <p class="text-5xl font-black">50%</p>
                        <p class="mt-3 text-xs uppercase tracking-widest text-neutral-500 font-semibold">Higher intent vs. social-first platforms⁶</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6">
                        <p class="text-5xl font-black">30+</p>
                        <p class="mt-3 text-xs uppercase tracking-widest text-neutral-500 font-semibold">Daily sessions per active user⁷</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =================== SUCCESS STORIES =================== --}}
    <section id="stories" class="bg-white">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-20">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight"><em class="not-italic">Be A Success Story</em></h2>
            <p class="mt-3 text-neutral-600 max-w-2xl">See how advertisers like yours are succeeding with Ads Manager on Hirevo.</p>

            <div class="mt-10 grid lg:grid-cols-3 gap-6">
                @foreach ([
                    [
                        'name' => 'Comfrt',
                        'tag' => 'D2C Apparel',
                        'pitch' => 'Comfrt ran lead-gen ads on Hirevo to reach professionals refreshing their work wardrobe. Strong gains in conversions, revenue, and overall ROAS.',
                        'stats' => [['79%', 'new customer rate'], ['85%', 'lift in new visits'], ['32%', 'increase in revenue'], ['3x', 'incremental ROAS']],
                        'bg' => 'bg-brand-yellow text-brand-ink',
                    ],
                    [
                        'name' => 'Laneige',
                        'tag' => 'Beauty &amp; Skincare',
                        'pitch' => 'Laneige used homepage hero placements + lead forms on Hirevo to triple their pipeline velocity year over year.',
                        'stats' => [['142%', 'increase in ROAS YoY'], ['2.1x', 'pipeline velocity'], ['27%', 'lower CPL'], ['#1', 'channel for new buyers']],
                        'bg' => 'bg-brand-ink text-white',
                    ],
                    [
                        'name' => 'Triumph Arcade',
                        'tag' => 'Mobile Gaming',
                        'pitch' => 'Triumph Arcade ran sidebar ads + automatic lead capture to boost installs and purchases at a lower cost per acquisition.',
                        'stats' => [['2.6x', 'as many installs'], ['37%', 'lower CPI'], ['94%', 'more purchases'], ['15%', 'lower cost per buy']],
                        'bg' => 'bg-brand-soft text-brand-ink',
                    ],
                ] as $story)
                    <article class="rounded-3xl p-7 {{ $story['bg'] }} flex flex-col">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-10 w-10 rounded-xl bg-white/20 backdrop-blur items-center justify-center font-black">{{ substr($story['name'], 0, 1) }}</span>
                            <div>
                                <p class="font-extrabold text-lg">{{ $story['name'] }}</p>
                                <p class="text-xs opacity-70 uppercase tracking-widest">{!! $story['tag'] !!}</p>
                            </div>
                        </div>
                        <p class="mt-5 text-sm/relaxed opacity-90">{{ $story['pitch'] }}</p>

                        <div class="mt-6 grid grid-cols-2 gap-4">
                            @foreach ($story['stats'] as $stat)
                                <div>
                                    <p class="text-3xl font-black">{{ $stat[0] }}</p>
                                    <p class="text-[11px] uppercase tracking-widest opacity-70 mt-1">{{ $stat[1] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('register') }}" class="mt-7 inline-flex items-center text-sm font-bold underline-offset-4 hover:underline">See Success Stories →</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =================== AD PRODUCTS =================== --}}
    <section id="features" class="bg-brand-soft">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-20">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight"><em class="not-italic">Ad products built for performance</em></h2>
            <p class="mt-3 text-neutral-600 max-w-2xl">Pick the placement that matches your goal — every ad is fully tracked, end-to-end.</p>

            <div class="mt-10 grid lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-3xl p-7 border border-black/5">
                    <div class="inline-flex h-11 w-11 rounded-2xl bg-brand-yellow items-center justify-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 12h4l3-9 4 18 3-9h4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-extrabold">Lead Generation Ads</h3>
                    <p class="mt-2 text-sm text-neutral-600">Capture qualified leads with native Hirevo lead forms — pre-filled, single-tap, and automatically piped into your dashboard.</p>
                    <a href="{{ route('register') }}" class="mt-5 inline-flex items-center text-sm font-bold text-brand-ink hover:underline">Lead Forms Overview →</a>
                </div>
                <div class="bg-white rounded-3xl p-7 border border-black/5">
                    <div class="inline-flex h-11 w-11 rounded-2xl bg-brand-yellow items-center justify-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M4 6h16M4 12h10M4 18h7" stroke-linecap="round"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-extrabold">Premium Placements</h3>
                    <p class="mt-2 text-sm text-neutral-600">Hero, sidebar, dashboard, and email placements across Hirevo — high-attention slots that get noticed and clicked.</p>
                    <a href="{{ route('register') }}" class="mt-5 inline-flex items-center text-sm font-bold text-brand-ink hover:underline">See Ad Formats →</a>
                </div>
                <div class="bg-white rounded-3xl p-7 border border-black/5">
                    <div class="inline-flex h-11 w-11 rounded-2xl bg-brand-yellow items-center justify-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 17l6-6 4 4 8-8" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 7h7v7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <h3 class="mt-5 text-xl font-extrabold">Real-time Reporting</h3>
                    <p class="mt-2 text-sm text-neutral-600">Granular impression / click / lead reporting per ad, per campaign — so you optimize what works and pause what doesn't.</p>
                    <a href="{{ route('register') }}" class="mt-5 inline-flex items-center text-sm font-bold text-brand-ink hover:underline">Measurement Overview →</a>
                </div>
            </div>
        </div>
    </section>

    {{-- =================== HOW IT WORKS =================== --}}
    <section class="bg-white">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-20">
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">How it works</h2>
            <p class="mt-3 text-neutral-600 max-w-2xl">Four steps from sign-up to your first lead.</p>

            <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ([
                    ['1', 'Create a campaign', 'Pick an objective: lead generation, awareness, traffic, or conversions. Set your budget and dates.'],
                    ['2', 'Design your ad', 'Add your headline, body copy, image, CTA, and destination URL. Pick a Hirevo placement.'],
                    ['3', 'Embed on Hirevo', 'Copy the snippet from Integrations and embed it on Hirevo. Impressions, clicks, and leads tracked automatically.'],
                    ['4', 'Capture leads', 'Every lead form submission lands in your dashboard, attributed to the exact ad that produced it.'],
                ] as $step)
                    <div class="rounded-3xl bg-brand-soft p-7">
                        <span class="inline-flex h-10 w-10 rounded-xl bg-brand-ink text-brand-yellow font-black items-center justify-center">{{ $step[0] }}</span>
                        <h3 class="mt-4 text-lg font-extrabold">{{ $step[1] }}</h3>
                        <p class="mt-2 text-sm text-neutral-600">{{ $step[2] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =================== FINAL CTA =================== --}}
    <section class="bg-brand-yellow">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-24 text-center">
            <h2 class="display-headline text-5xl sm:text-6xl lg:text-7xl font-black tracking-tight">Ads Manager. Spend Smarter.</h2>
            <p class="mt-5 text-lg text-brand-ink/80 max-w-xl mx-auto">Find your next customer on Hirevo. Free to start, scales with your team.</p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('register') }}" class="ghost-btn inline-flex items-center px-7 py-4 rounded-full bg-brand-ink text-white font-bold shadow-lg hover:shadow-xl">Get Started</a>
                <a href="{{ route('login') }}" class="ghost-btn inline-flex items-center px-7 py-4 rounded-full bg-white/70 backdrop-blur text-brand-ink font-bold border border-black/10">Log In</a>
            </div>
        </div>
    </section>

    {{-- =================== REFERENCES =================== --}}
    <section class="bg-white border-t border-black/5">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-12">
            <h3 class="font-bold text-sm uppercase tracking-widest text-neutral-500">References</h3>
            <ol class="mt-4 grid sm:grid-cols-2 gap-x-8 gap-y-2 text-xs text-neutral-500 list-decimal list-inside">
                <li>Independent benchmark report — pipeline performance, 2026.</li>
                <li>Internal data, 90-day attribution windows, Q1 2026.</li>
                <li>Aggregated CPA &amp; ROAS data, H2 2024 cohort.</li>
                <li>Hirevo audience overlap study, 2026.</li>
                <li>Hirevo monthly active user base, Q4 2025.</li>
                <li>Hirevo intent benchmark vs. social platforms.</li>
                <li>Average daily sessions per Hirevo user, 2025.</li>
            </ol>
        </div>
    </section>
@endsection
