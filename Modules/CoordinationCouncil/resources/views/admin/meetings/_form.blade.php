@php $mtg = $meeting; @endphp
<div>
    <label class="block text-sm font-medium mb-1">Data e hora</label>
    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $mtg?->scheduled_at?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Local</label>
    <input type="text" name="location" value="{{ old('location', $mtg?->location) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Tipo</label>
    <select name="meeting_type" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
        <option value="ordinary" @selected(old('meeting_type', $mtg?->meeting_type) === 'ordinary')>Ordinária</option>
        <option value="extraordinary" @selected(old('meeting_type', $mtg?->meeting_type) === 'extraordinary')>Extraordinária</option>
    </select>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Quórum necessário</label>
    <input type="number" name="quorum_required" min="1" value="{{ old('quorum_required', $mtg?->quorum_required ?? 1) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
@if($mtg)
<div>
    <label class="block text-sm font-medium mb-1">Quórum efectivo (após reunião)</label>
    <input type="number" name="quorum_actual" min="0" value="{{ old('quorum_actual', $mtg->quorum_actual) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
@endif
<div>
    <label class="block text-sm font-medium mb-1">Notas / ata resumida</label>
    <textarea name="minutes_notes" rows="4" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('minutes_notes', $mtg?->minutes_notes) }}</textarea>
</div>
