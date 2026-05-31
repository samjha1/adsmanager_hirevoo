@php
    $isCreating = $isCreating ?? ! $ad->exists;
    $areaOptions = ['All India', 'Delhi NCR', 'Mumbai', 'Bangalore', 'Hyderabad', 'Pune', 'Chennai', 'Kolkata', 'Ahmedabad'];
    $ageGroupOptions = ['18-24', '25-34', '35-44', '45-54', '55-64', '65+'];
    $audienceOptions = ['Students', 'Freshers', 'Working Professionals', 'Job Seekers', 'Career Switchers', 'Tech Audience', 'HR & Recruiters'];

    $selectedAreas = old('target_area', $ad->target_area ? array_map('trim', explode(',', $ad->target_area)) : []);
    $selectedAgeGroups = old('target_age_group', $ad->target_age_group ? array_map('trim', explode(',', $ad->target_age_group)) : []);
    $selectedAudiences = old('target_audience', $ad->target_audience ? array_map('trim', explode(',', $ad->target_audience)) : []);

    $statusOptions = auth()->user()->isAdmin()
        ? collect(\App\Models\Ad::statusLabels())->mapWithKeys(fn ($label, $key) => [$key => $label])->all()
        : ($isCreating ? [] : $ad->advertiserStatusOptions());
@endphp

@if ($errors->any())
    <div class="rounded-2xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 mb-6 text-sm">
        <p class="font-semibold mb-1">Please fix the following:</p>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid lg:grid-cols-5 gap-6 lg:gap-8">
    <div class="lg:col-span-3 space-y-6">
        {{-- Setup --}}
        <section class="bg-white rounded-3xl border border-black/5 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold uppercase tracking-widest text-neutral-500 mb-4">Setup</h2>

            <label class="block mb-4">
                <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Ad name (internal)</span>
                <input type="text" name="name" id="ad-field-name" value="{{ old('name', $ad->name) }}" required
                       placeholder="e.g. Summer hiring push"
                       class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            </label>

            <div class="grid sm:grid-cols-2 gap-4 mb-4">
                <label class="block">
                    <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Campaign</span>
                    <select name="campaign_id" required class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 bg-white">
                        @foreach ($campaigns as $c)
                            <option value="{{ $c->id }}" @selected((int) old('campaign_id', $ad->campaign_id) === $c->id)>
                                {{ $c->name }}
                                @if($c->status !== 'active') ({{ $c->status }}) @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-neutral-500">Campaign must be <strong>active</strong> for the ad to go live on Hirevo.</p>
                </label>

                <label class="block">
                    <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Hirevo screen</span>
                    <select name="placement" id="ad-field-placement" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 bg-white">
                        @foreach (\App\Models\Ad::PLACEMENTS as $key => $label)
                            <option value="{{ $key }}" @selected(old('placement', $ad->placement) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            @if($isCreating)
                <div class="rounded-xl bg-brand-soft/60 border border-black/5 px-4 py-3 flex items-start gap-3">
                    <span class="badge badge-orange shrink-0 mt-0.5">In review</span>
                    <p class="text-sm text-neutral-700 mb-0">After you save, this ad is sent for approval. Status becomes <strong>Live on Hirevo</strong> only when an admin approves it.</p>
                </div>
            @else
                <div class="rounded-xl border border-black/5 px-4 py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Status</span>
                        <span class="badge {{ $ad->statusBadgeClass() }}">{{ $ad->statusLabel() }}</span>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">{{ $ad->statusHelpText() }}</p>
                    @if($ad->status === 'active' && $ad->campaign && $ad->campaign->status !== 'active')
                        <p class="text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 mb-3">
                            Ad is approved but the campaign is not active — it may not show on Hirevo until the campaign is active.
                        </p>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-black/10 bg-white text-sm">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $ad->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @elseif(count($statusOptions) > 0)
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-black/10 bg-white text-sm">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $ad->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="status" value="{{ $ad->status }}">
                    @endif
                </div>
            @endif
        </section>

        {{-- Creative --}}
        <section class="bg-white rounded-3xl border border-black/5 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold uppercase tracking-widest text-neutral-500 mb-4">Creative</h2>

            <label class="block mb-4">
                <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Headline</span>
                <input type="text" name="headline" id="ad-field-headline" maxlength="160" value="{{ old('headline', $ad->headline) }}" required
                       placeholder="Short, clear offer"
                       class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
            </label>

            <label class="block mb-4">
                <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Body copy</span>
                <textarea name="body" id="ad-field-body" rows="3" maxlength="1000" placeholder="One or two sentences about your offer"
                          class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:ring-2 focus:ring-brand-yellow">{{ old('body', $ad->body) }}</textarea>
            </label>

            <label class="block mb-4">
                <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Ad image</span>
                <input type="file" name="image_file" id="image-file-input" accept="image/jpeg,image/png,image/webp"
                       class="mt-2 block w-full text-sm text-neutral-700 file:mr-3 file:px-4 file:py-2.5 file:rounded-full file:border-0 file:font-semibold file:bg-brand-soft file:text-brand-ink hover:file:bg-brand-yellow/40">
                <p class="mt-1 text-xs text-neutral-500">JPG, PNG or WEBP · max 5MB · 16:9 works best on Hirevo.</p>
            </label>

            <div class="grid sm:grid-cols-2 gap-4">
                <label class="block">
                    <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Button label (CTA)</span>
                    <input type="text" name="cta_label" id="ad-field-cta" value="{{ old('cta_label', $ad->cta_label) }}" required
                           placeholder="Learn more"
                           class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
                </label>
                <label class="block">
                    <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Destination URL</span>
                    <input type="url" name="destination_url" value="{{ old('destination_url', $ad->destination_url) }}"
                           placeholder="https://yoursite.com/offer"
                           class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
                    <p class="mt-1 text-xs text-neutral-500">Where users go when they click the ad.</p>
                </label>
            </div>
        </section>

        {{-- Targeting --}}
        <section class="bg-white rounded-3xl border border-black/5 p-5 sm:p-6 space-y-4">
            <h2 class="text-sm font-extrabold uppercase tracking-widest text-neutral-500">Audience targeting</h2>

            <div class="rounded-2xl border border-black/10 p-4">
                <p class="text-xs uppercase tracking-widest font-bold text-neutral-500 mb-3">Target area <span class="text-red-500">*</span></p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($areaOptions as $area)
                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 text-sm cursor-pointer hover:bg-brand-soft/40 has-[:checked]:bg-brand-yellow/30 has-[:checked]:border-brand-yellow">
                            <input type="checkbox" name="target_area[]" value="{{ $area }}" class="rounded border-black/20 text-brand-ink focus:ring-brand-yellow" @checked(in_array($area, $selectedAreas, true))>
                            <span>{{ $area }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-black/10 p-4">
                <p class="text-xs uppercase tracking-widest font-bold text-neutral-500 mb-3">Age group <span class="text-red-500">*</span></p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($ageGroupOptions as $ageGroup)
                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 text-sm cursor-pointer hover:bg-brand-soft/40 has-[:checked]:bg-brand-yellow/30 has-[:checked]:border-brand-yellow">
                            <input type="checkbox" name="target_age_group[]" value="{{ $ageGroup }}" class="rounded border-black/20 text-brand-ink focus:ring-brand-yellow" @checked(in_array($ageGroup, $selectedAgeGroups, true))>
                            <span>{{ $ageGroup }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-black/10 p-4">
                <p class="text-xs uppercase tracking-widest font-bold text-neutral-500 mb-3">Target audience <span class="font-normal text-neutral-400">(optional)</span></p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($audienceOptions as $audience)
                        <label class="inline-flex items-center gap-2 px-3 py-2 rounded-full border border-black/10 text-sm cursor-pointer hover:bg-brand-soft/40 has-[:checked]:bg-brand-soft">
                            <input type="checkbox" name="target_audience[]" value="{{ $audience }}" class="rounded border-black/20 text-brand-ink focus:ring-brand-yellow" @checked(in_array($audience, $selectedAudiences, true))>
                            <span>{{ $audience }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </section>

        <div class="flex flex-wrap items-center gap-3 pb-4">
            <button type="submit" class="inline-flex items-center px-6 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
                {{ $isCreating ? 'Create & submit for review' : 'Save changes' }}
            </button>
            <a href="{{ $isCreating ? route('ads.index') : route('ads.show', $ad) }}" class="px-6 py-3 rounded-full border border-black/10 font-semibold">Cancel</a>
            @if (! $isCreating)
                <form method="POST" action="{{ route('ads.destroy', $ad) }}" class="ml-auto" onsubmit="return confirm('Delete this ad?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">Delete ad</button>
                </form>
            @endif
        </div>
    </div>

    {{-- Preview --}}
    <div class="lg:col-span-2">
        <div class="lg:sticky lg:top-6 space-y-4">
            <p class="text-xs uppercase tracking-widest font-bold text-neutral-500">Hirevo preview</p>
            <div class="rounded-2xl bg-white border border-black/10 overflow-hidden shadow-sm" id="ad-preview-card">
                <div class="aspect-[16/9] bg-gradient-to-br from-indigo-50 to-slate-100 relative" id="ad-preview-image">
                    @if ($ad->image_url)
                        <img id="ad-preview-image-tag" src="{{ $ad->image_url }}" class="w-full h-full object-cover" alt="">
                    @else
                        <img id="ad-preview-image-tag" src="" class="w-full h-full object-cover hidden" alt="">
                        <div id="ad-preview-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-neutral-400 text-xs gap-1">
                            <svg class="w-8 h-8 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Image preview
                        </div>
                    @endif
                </div>
                <div class="p-4 border-t border-black/5">
                    <p class="text-[10px] uppercase tracking-widest text-neutral-500 font-bold">Sponsored</p>
                    <p class="text-[10px] text-neutral-400 mt-0.5" id="ad-preview-placement">{{ $ad->placementLabel() }}</p>
                    <h4 class="mt-2 font-extrabold leading-tight text-base" id="ad-preview-headline">{{ old('headline', $ad->headline) ?: 'Your headline goes here' }}</h4>
                    <p class="mt-1 text-sm text-neutral-600 line-clamp-2" id="ad-preview-body">{{ old('body', $ad->body) ?: 'Your body copy describes the offer.' }}</p>
                    <span class="mt-3 inline-flex px-4 py-2 rounded-full bg-brand-ink text-white text-xs font-bold" id="ad-preview-cta">{{ old('cta_label', $ad->cta_label) ?: 'Learn More' }}</span>
                </div>
            </div>
            <p class="text-xs text-neutral-500 leading-relaxed">This is how your ad roughly appears on Hirevo after approval. Exact layout varies by screen (home, jobs, dashboard).</p>
        </div>
    </div>
</div>

<script>
(function () {
    const placements = @json(\App\Models\Ad::PLACEMENTS);

    const bind = (id, targetId, fallback) => {
        const el = document.getElementById(id);
        const target = document.getElementById(targetId);
        if (!el || !target) return;
        const update = () => { target.textContent = el.value.trim() || fallback; };
        el.addEventListener('input', update);
        update();
    };

    bind('ad-field-headline', 'ad-preview-headline', 'Your headline goes here');
    bind('ad-field-body', 'ad-preview-body', 'Your body copy describes the offer.');
    bind('ad-field-cta', 'ad-preview-cta', 'Learn More');

    const placementSelect = document.getElementById('ad-field-placement');
    const placementPreview = document.getElementById('ad-preview-placement');
    if (placementSelect && placementPreview) {
        const updatePlacement = () => {
            placementPreview.textContent = placements[placementSelect.value] || placementSelect.value;
        };
        placementSelect.addEventListener('change', updatePlacement);
        updatePlacement();
    }

    const input = document.getElementById('image-file-input');
    const previewImage = document.getElementById('ad-preview-image-tag');
    const previewPlaceholder = document.getElementById('ad-preview-placeholder');
    if (input && previewImage) {
        if (previewImage.getAttribute('src')) {
            previewImage.classList.remove('hidden');
            if (previewPlaceholder) previewPlaceholder.classList.add('hidden');
        }
        input.addEventListener('change', function (event) {
            const file = event.target.files && event.target.files[0];
            if (!file) return;
            previewImage.src = URL.createObjectURL(file);
            previewImage.classList.remove('hidden');
            if (previewPlaceholder) previewPlaceholder.classList.add('hidden');
        });
    }
})();
</script>
