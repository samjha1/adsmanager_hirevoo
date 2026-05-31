<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ads Manager — Spend Smarter on Hirevo')</title>
    <meta name="description" content="Run targeted ad campaigns on Hirevo. Capture qualified leads, track every impression and click, and scale what's working — all from one dashboard.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            yellow: '#FFFC00',
                            yellow2: '#FCEB2D',
                            ink: '#0A0A0A',
                            soft: '#F4F4F2',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'Segoe UI', 'Helvetica', 'Arial'],
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; }
        .display-headline { letter-spacing: -0.04em; line-height: 0.95; }
        .ghost-btn { transition: all .2s ease; }
        .ghost-btn:hover { transform: translateY(-1px); }
        .pill { letter-spacing: 0.02em; }
    </style>
    @stack('head')
</head>
<body class="bg-white text-brand-ink antialiased">
    @include('partials.marketing-nav')

    <main>
        @yield('content')
    </main>

    @include('partials.marketing-footer')
</body>
</html>
