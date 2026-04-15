@php
    $ic = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $lc = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
@endphp
<div>
    <label class="{{ $lc }}">Título da ata *</label>
    <input type="text" name="title" value="{{ old('title', $minute->title) }}" required class="{{ $ic }}" placeholder="Ex.: Ata da reunião de diretoria — 05/04/2026">
</div>
@isset($meetings)
<div>
    <label class="{{ $lc }}">Reunião associada (opcional)</label>
    <select name="meeting_id" class="{{ $ic }}">
        <option value="">— Nenhuma —</option>
        @foreach($meetings as $meet)
            <option value="{{ $meet->id }}" @selected(old('meeting_id', $minute->meeting_id) == $meet->id)>
                {{ $meet->starts_at->format('d/m/Y H:i') }} — {{ $meet->title ?: $meet->type }}
            </option>
        @endforeach
    </select>
</div>
@else
<div>
    <label class="{{ $lc }}">Reunião (ID)</label>
    <input type="number" name="meeting_id" value="{{ old('meeting_id', $minute->meeting_id) }}" placeholder="Opcional" class="{{ $ic }}">
</div>
@endisset
<div>
    <label class="{{ $lc }}">Igreja / âmbito (opcional)</label>
    <select name="church_id" class="{{ $ic }}">
        <option value="">— Federação (geral) —</option>
        @foreach($churches as $ch)
            <option value="{{ $ch->id }}" @selected(old('church_id', $minute->church_id) == $ch->id)>{{ $ch->name }}</option>
        @endforeach
    </select>
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Deixe em branco para atas da JUBAF em geral; escolha uma igreja se a ata for só dessa congregação.</p>
</div>

<div>
    <label class="{{ $lc }}">Resumo executivo (comunicações / ERP)</label>
    <textarea name="executive_summary" rows="3" class="{{ $ic }}" placeholder="Breve resumo para notificações e relatórios (opcional).">{{ old('executive_summary', $minute->executive_summary ?? '') }}</textarea>
    @error('executive_summary')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

<div>
    <label class="{{ $lc }}">Corpo da ata</label>
    <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">
        Use o editor abaixo como no Word: <strong>títulos</strong>, <strong>listas</strong> e <strong>negrito</strong>.
        Não é necessário escrever código — os botões formatam o texto por si.
    </p>
    <div class="rounded-2xl border border-amber-200/90 bg-amber-50/40 p-3 shadow-inner dark:border-amber-900/40 dark:bg-slate-900">
        <div id="quill-minute-body" class="quill-editor-wrapper rounded-xl" aria-label="Editor do corpo da ata"></div>
    </div>
    <textarea name="content" id="minute-body" class="hidden" rows="1" cols="1">{{ old('content', $minute->content ?? $minute->body) }}</textarea>
</div>

@once
    @push('scripts')
        @vite(['resources/js/secretaria-minute-editor.js'])
    @endpush
@endonce
