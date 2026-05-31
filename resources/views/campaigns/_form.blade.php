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

<div class="grid lg:grid-cols-2 gap-5">
    <label class="block lg:col-span-2">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Campaign name</span>
        <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 focus:outline-none focus:ring-2 focus:ring-brand-yellow">
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Objective</span>
        <input type="hidden" name="objective" value="leads">
        <input type="text" value="Lead Generation" readonly class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 bg-neutral-100 text-neutral-700">
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Status</span>
        <select name="status" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10 bg-white">
            @foreach (\App\Models\Campaign::STATUSES as $s)
                <option value="{{ $s }}" @selected(old('status', $campaign->status) === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Daily budget ($)</span>
        <input type="number" step="0.01" min="0" name="daily_budget" value="{{ old('daily_budget', $campaign->daily_budget) }}" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Total budget ($)</span>
        <input type="number" step="0.01" min="0" name="total_budget" value="{{ old('total_budget', $campaign->total_budget) }}" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Spend so far ($)</span>
        <input type="number" step="0.01" min="0" name="spend" value="{{ old('spend', $campaign->spend) }}" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Start date</span>
        <input type="date" name="start_date" value="{{ old('start_date', optional($campaign->start_date)->toDateString()) }}" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
    </label>

    <label class="block">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">End date</span>
        <input type="date" name="end_date" value="{{ old('end_date', optional($campaign->end_date)->toDateString()) }}" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">
    </label>

    <label class="block lg:col-span-2">
        <span class="text-xs uppercase tracking-widest font-bold text-neutral-500">Description</span>
        <textarea name="description" rows="3" class="mt-2 w-full px-4 py-3 rounded-xl border border-black/10">{{ old('description', $campaign->description) }}</textarea>
    </label>
</div>

<div class="mt-7 flex items-center gap-3">
    <button type="submit" class="inline-flex items-center px-6 py-3 rounded-full bg-brand-ink text-white font-bold hover:opacity-90">
        {{ $campaign->exists ? 'Save Changes' : 'Create Campaign' }}
    </button>
    <a href="{{ route('campaigns.index') }}" class="px-6 py-3 rounded-full border border-black/10 font-semibold">Cancel</a>
    @if ($campaign->exists)
        <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}" class="ml-auto" onsubmit="return confirm('Delete this campaign and all its ads?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 font-semibold hover:underline">Delete campaign</button>
        </form>
    @endif
</div>
