@php
    $m = $member;
@endphp
<div>
    <label class="block text-sm font-medium mb-1">Nome completo</label>
    <input type="text" name="full_name" value="{{ old('full_name', $m?->full_name) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Email</label>
    <input type="email" name="email" value="{{ old('email', $m?->email) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Telefone</label>
    <input type="text" name="phone" value="{{ old('phone', $m?->phone) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<div>
    <label class="block text-sm font-medium mb-1">Tipo</label>
    <select name="kind" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
        <option value="effective" @selected(old('kind', $m?->kind) === 'effective')>Efetivo</option>
        <option value="supplement" @selected(old('kind', $m?->kind) === 'supplement')>Suplente</option>
    </select>
</div>
<div class="grid grid-cols-2 gap-3">
    <div>
        <label class="block text-sm font-medium mb-1">Início mandato</label>
        <input type="date" name="term_started_at" value="{{ old('term_started_at', $m?->term_started_at?->format('Y-m-d')) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Fim mandato</label>
        <input type="date" name="term_ended_at" value="{{ old('term_ended_at', $m?->term_ended_at?->format('Y-m-d')) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
    </div>
</div>
<div>
    <label class="block text-sm font-medium mb-1">Terço (1–3)</label>
    <input type="number" name="mandate_third" min="1" max="3" value="{{ old('mandate_third', $m?->mandate_third) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
</div>
<label class="inline-flex items-center gap-2 text-sm">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $m?->is_active ?? true)) class="rounded border-gray-300">
    Ativo
</label>
