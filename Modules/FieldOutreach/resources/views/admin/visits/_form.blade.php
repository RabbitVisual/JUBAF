@php $v = $visit; @endphp
<div>
    <label class="block text-sm font-medium mb-1">Igreja</label>
    <select name="church_id" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
        @foreach($churches as $c)
            <option value="{{ $c->id }}" @selected(old('church_id', $v?->church_id) == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Data da visita</label>
    <input type="date" name="visited_at" value="{{ old('visited_at', $v?->visited_at?->format('Y-m-d')) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Notas</label>
    <textarea name="notes" rows="5" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('notes', $v?->notes) }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Próximos passos</label>
    <textarea name="next_steps" rows="3" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('next_steps', $v?->next_steps) }}</textarea>
</div>
<div>
    <label class="block text-sm font-medium mb-2">Participantes (utilizadores)</label>
    <select name="attendee_ids[]" multiple class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white min-h-[120px]">
        @php $sel = collect(old('attendee_ids', $v ? $v->attendees->pluck('id')->all() : [])); @endphp
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected($sel->contains($u->id))>{{ $u->name }}</option>
        @endforeach
    </select>
    <p class="text-xs text-gray-500 mt-1">Ctrl+clique para vários.</p>
</div>
