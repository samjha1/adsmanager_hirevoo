@csrf
<div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label for="name" class="block text-sm font-semibold text-neutral-700 mb-1.5">Lead name <span class="text-rose-500">*</span></label>
        <input id="name" name="name" type="text" value="{{ old('name', $lead->name) }}" required
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div>
        <label for="email" class="block text-sm font-semibold text-neutral-700 mb-1.5">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $lead->email) }}"
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div>
        <label for="phone" class="block text-sm font-semibold text-neutral-700 mb-1.5">Phone</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $lead->phone) }}"
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div>
        <label for="company" class="block text-sm font-semibold text-neutral-700 mb-1.5">Company</label>
        <input id="company" name="company" type="text" value="{{ old('company', $lead->company) }}"
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div>
        <label for="job_title" class="block text-sm font-semibold text-neutral-700 mb-1.5">Job title</label>
        <input id="job_title" name="job_title" type="text" value="{{ old('job_title', $lead->job_title) }}"
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div>
        <label for="source" class="block text-sm font-semibold text-neutral-700 mb-1.5">Source</label>
        <select id="source" name="source" class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm">
            @foreach (\App\Models\Lead::SOURCES as $s)
                <option value="{{ $s }}" @selected(old('source', $lead->source)===$s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="campaign_id" class="block text-sm font-semibold text-neutral-700 mb-1.5">Campaign</label>
        <select id="campaign_id" name="campaign_id" class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm">
            <option value="">— None —</option>
            @foreach ($campaigns as $c)
                <option value="{{ $c->id }}" @selected((int) old('campaign_id', $lead->campaign_id) === $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="ad_id" class="block text-sm font-semibold text-neutral-700 mb-1.5">Ad</label>
        <select id="ad_id" name="ad_id" class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm">
            <option value="">— None —</option>
            @foreach ($ads as $a)
                <option value="{{ $a->id }}" @selected((int) old('ad_id', $lead->ad_id) === $a->id)>{{ $a->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="status" class="block text-sm font-semibold text-neutral-700 mb-1.5">Status</label>
        <select id="status" name="status" class="w-full rounded-xl border border-neutral-200 bg-white px-3 py-2.5 text-sm">
            @foreach (\App\Models\Lead::STATUSES as $s)
                <option value="{{ $s }}" @selected(old('status', $lead->status)===$s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="value" class="block text-sm font-semibold text-neutral-700 mb-1.5">Deal value (USD)</label>
        <input id="value" name="value" type="number" min="0" step="0.01" value="{{ old('value', $lead->value) }}"
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div>
        <label for="next_followup_at" class="block text-sm font-semibold text-neutral-700 mb-1.5">Next follow-up</label>
        <input id="next_followup_at" name="next_followup_at" type="datetime-local"
               value="{{ old('next_followup_at', optional($lead->next_followup_at)->format('Y-m-d\TH:i')) }}"
               class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">
    </div>
    <div class="sm:col-span-2">
        <label for="notes" class="block text-sm font-semibold text-neutral-700 mb-1.5">Notes</label>
        <textarea id="notes" name="notes" rows="5"
                  class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm focus:border-brand-ink focus:ring-2 focus:ring-brand-ink/10 outline-none">{{ old('notes', $lead->notes) }}</textarea>
    </div>
</div>
