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
    <style> body { font-family: 'Inter', system-ui, sans-serif; } </style>
</head>
<body class="min-h-screen bg-brand-soft">
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex flex-col justify-between bg-brand-yellow p-10 relative overflow-hidden">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-ink text-brand-yellow font-black text-lg">A</span>
                <span class="font-extrabold tracking-tight text-lg text-brand-ink">Ads Manager</span>
            </a>

            <div class="relative z-10">
                <p class="text-xs uppercase tracking-widest font-bold text-brand-ink/70">For advertisers running on Hirevo</p>
                <h1 class="mt-4 text-5xl xl:text-6xl font-black text-brand-ink tracking-tight" style="letter-spacing:-0.04em;line-height:0.95;">
                    Spend&nbsp;Smarter.<br>Convert&nbsp;Faster.
                </h1>
                <p class="mt-5 text-brand-ink/80 max-w-md">Launch targeted ads, track every impression and click, and capture qualified leads directly from Hirevo into your dashboard.</p>

                <div class="mt-8 grid grid-cols-3 gap-4">
                    @foreach ([['3.8x', 'Avg ROAS'], ['#1', 'CPA'], ['90d', 'Attribution']] as $stat)
                        <div class="bg-brand-ink/95 text-white rounded-2xl p-4">
                            <p class="text-2xl font-black">{{ $stat[0] }}</p>
                            <p class="text-[10px] uppercase tracking-widest text-brand-yellow/90 mt-1">{{ $stat[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <p class="text-xs text-brand-ink/60 relative z-10">© {{ date('Y') }} Ads Manager. All rights reserved.</p>

            <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-brand-ink/10 blur-3xl"></div>
        </div>

        <div class="flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="lg:hidden flex items-center gap-2 mb-8">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-yellow text-brand-ink font-black text-lg">A</span>
                    <span class="font-extrabold tracking-tight text-lg">Ads Manager</span>
                </a>
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
