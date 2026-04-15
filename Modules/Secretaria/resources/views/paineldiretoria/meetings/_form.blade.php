@php
    $ic = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $lc = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $types = [
        'assembleia_ordinaria' => 'Assembleia ordinária',
        'assembleia_extraordinaria' => 'Assembleia extraordinária',
        'diretoria' => 'Diretoria',
        'conselho_coordenacao' => 'Conselho de coordenação',
    ];
@endphp
<div>
    <label class="{{ $lc }}">Tipo *</label>
    <select name="type" required class="{{ $ic }}">
        @foreach($types as $v => $l)
            <option value="{{ $v }}" @selected(old('type', $meeting->type) === $v)>{{ $l }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="{{ $lc }}">Título</label>
    <input type="text" name="title" value="{{ old('title', $meeting->title) }}" class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Início *</label>
    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', $meeting->starts_at?->format('Y-m-d\TH:i')) }}" required class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Fim</label>
    <input type="datetime-local" name="ends_at" value="{{ old('ends_at', $meeting->ends_at?->format('Y-m-d\TH:i')) }}" class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Local</label>
    <input type="text" name="location" value="{{ old('location', $meeting->location) }}" class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Estado *</label>
    <select name="status" class="{{ $ic }}">
        @foreach(['scheduled' => 'Agendada', 'held' => 'Realizada', 'cancelled' => 'Cancelada'] as $v => $l)
            <option value="{{ $v }}" @selected(old('status', $meeting->status ?? 'scheduled') === $v)>{{ $l }}</option>
        @endforeach
    </select>
</div>
<div>
    <label class="{{ $lc }}">Notas</label>
    <textarea name="notes" rows="3" class="{{ $ic }}">{{ old('notes', $meeting->notes) }}</textarea>
</div>
