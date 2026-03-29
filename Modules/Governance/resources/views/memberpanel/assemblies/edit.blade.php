@extends('memberpanel::components.layouts.master')

@section('page-title', 'Editar assembleia')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Editar assembleia',
        'subtitle' => $assembly->title,
        'badge' => 'Governança',
    ])
        <a href="{{ route('memberpanel.governance.assemblies.show', $assembly) }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Voltar
        </a>

        <form method="post" action="{{ route('memberpanel.governance.assemblies.update', $assembly) }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-2xl">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Tipo</label>
                <select name="type"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="ordinaria" @selected(old('type', $assembly->type) === 'ordinaria')>Ordinária</option>
                    <option value="extraordinaria" @selected(old('type', $assembly->type) === 'extraordinaria')>Extraordinária</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Título</label>
                <input type="text" name="title" value="{{ old('title', $assembly->title) }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Data e hora</label>
                <input type="datetime-local" name="scheduled_at"
                    value="{{ old('scheduled_at', $assembly->scheduled_at?->format('Y-m-d\TH:i')) }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Local</label>
                <input type="text" name="location" value="{{ old('location', $assembly->location) }}"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Notas de convocação</label>
                <textarea name="convocation_notes" rows="4"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('convocation_notes', $assembly->convocation_notes) }}</textarea>
            </div>

            <div class="border-t border-gray-100 dark:border-slate-800 pt-6 mt-2">
                <h2 class="font-bold text-gray-900 dark:text-white mb-3 text-sm">Pauta (substitui ao guardar)</h2>
                @php
                    $items = old('agenda_titles', $assembly->agendaItems->pluck('title')->all());
                    $descs = old('agenda_descriptions', $assembly->agendaItems->pluck('description')->all());
                @endphp
                @if (empty($items))
                    @php $items = ['']; $descs = ['']; @endphp
                @endif
                @foreach ($items as $i => $t)
                    <div class="grid grid-cols-1 gap-2 mb-3">
                        <input type="text" name="agenda_titles[]" value="{{ $t }}" placeholder="Título do ponto"
                            class="rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                        <textarea name="agenda_descriptions[]" rows="2" placeholder="Descrição (opcional)"
                            class="rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">{{ $descs[$i] ?? '' }}</textarea>
                    </div>
                @endforeach
                <p class="text-xs text-gray-500">Guarde e volte a editar para acrescentar mais pontos, se necessário.</p>
            </div>

            <div class="flex flex-wrap gap-3 pt-2">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">Guardar</button>
                <a href="{{ route('memberpanel.governance.assemblies.show', $assembly) }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-sm font-semibold">Cancelar</a>
            </div>
        </form>
    @endcomponent
@endsection
