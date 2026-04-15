@php
    $ic = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $lc = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
@endphp
<div>
    <label class="{{ $lc }}">Título *</label>
    <input type="text" name="title" value="{{ old('title', $convocation->title) }}" required class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Data da assembleia *</label>
    <input type="datetime-local" name="assembly_at" value="{{ old('assembly_at', $convocation->assembly_at?->format('Y-m-d\TH:i')) }}" required class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Dias de antecedência mínima *</label>
    <input type="number" name="notice_days" value="{{ old('notice_days', $convocation->notice_days ?? 30) }}" min="1" max="120" required class="{{ $ic }}">
    <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Assembleias extraordinárias: 30 dias (estatuto JUBAF).</p>
</div>
<div>
    <label class="{{ $lc }}">Reunião vinculada (ID opcional)</label>
    <input type="number" name="meeting_id" value="{{ old('meeting_id', $convocation->meeting_id) }}" class="{{ $ic }}">
</div>
<div>
    <label class="{{ $lc }}">Texto</label>
    <textarea name="body" rows="8" class="{{ $ic }}">{{ old('body', $convocation->body) }}</textarea>
</div>
