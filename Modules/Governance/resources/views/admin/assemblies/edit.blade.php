@extends('admin::components.layouts.master')

@section('title', 'Editar assembleia')

@section('content')
    <div class="max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar assembleia</h1>
            <a href="{{ route('admin.governance.assemblies.show', $assembly) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">← Voltar</a>
        </div>

        <form method="post" action="{{ route('admin.governance.assemblies.update', $assembly) }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="ordinaria" @selected(old('type', $assembly->type) === 'ordinaria')>Ordinária</option>
                    <option value="extraordinaria" @selected(old('type', $assembly->type) === 'extraordinaria')>Extraordinária</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                <input type="text" name="title" value="{{ old('title', $assembly->title) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data e hora</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', $assembly->scheduled_at?->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Local</label>
                <input type="text" name="location" value="{{ old('location', $assembly->location) }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas de convocação</label>
                <textarea name="convocation_notes" rows="4" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('convocation_notes', $assembly->convocation_notes) }}</textarea>
            </div>

            <div class="border-t border-gray-200 dark:border-slate-800 pt-4 mt-4">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Pauta (substitui lista atual ao guardar)</h2>
                @php $items = old('agenda_titles', $assembly->agendaItems->pluck('title')->all()); $descs = old('agenda_descriptions', $assembly->agendaItems->pluck('description')->all()); @endphp
                @if(empty($items))
                    @php $items = ['']; $descs = ['']; @endphp
                @endif
                @foreach($items as $i => $t)
                    <div class="grid grid-cols-1 gap-2 mb-3">
                        <input type="text" name="agenda_titles[]" value="{{ $t }}" placeholder="Título do ponto" class="rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">
                        <textarea name="agenda_descriptions[]" rows="2" placeholder="Descrição (opcional)" class="rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white text-sm">{{ $descs[$i] ?? '' }}</textarea>
                    </div>
                @endforeach
                <p class="text-xs text-gray-500">Para adicionar linhas, guarde e volte a editar com mais campos ou use o mesmo padrão no HTML.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Guardar</button>
                <a href="{{ route('admin.governance.assemblies.show', $assembly) }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-slate-600 text-sm">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
