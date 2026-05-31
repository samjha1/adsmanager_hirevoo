<footer class="bg-brand-ink text-neutral-300">
    <div class="max-w-7xl mx-auto px-5 lg:px-8 py-16 grid gap-10 sm:grid-cols-2 lg:grid-cols-5">
        <div class="lg:col-span-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-brand-yellow text-brand-ink font-black text-lg">A</span>
                <span class="font-extrabold text-white text-lg">Ads Manager</span>
            </a>
            <p class="mt-4 text-sm text-neutral-400 max-w-sm">Run high-performance ad campaigns on Hirevo. Capture qualified leads, measure every dollar, and scale what's working.</p>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Product</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#features" class="hover:text-white">Ad Products</a></li>
                <li><a href="#audience" class="hover:text-white">Reach</a></li>
                <li><a href="#stories" class="hover:text-white">Success Stories</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-white">Pricing</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Goals</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">Lead Generation</a></li>
                <li><a href="#" class="hover:text-white">Brand Awareness</a></li>
                <li><a href="#" class="hover:text-white">Traffic</a></li>
                <li><a href="#" class="hover:text-white">Conversions</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Legal</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                <li><a href="#" class="hover:text-white">Terms of Service</a></li>
                <li><a href="#" class="hover:text-white">Advertising Policies</a></li>
                <li><a href="#" class="hover:text-white">Cookie Policy</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-5 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
            <p>© {{ date('Y') }} Ads Manager. All rights reserved.</p>
            <p>Targeted advertising platform for Hirevo.</p>
        </div>
    </div>
</footer>
