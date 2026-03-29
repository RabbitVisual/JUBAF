@extends('memberpanel::components.layouts.master')

@section('page-title', 'Nova assembleia')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Nova assembleia',
        'subtitle' => 'Registar convocação e dados básicos.',
        'badge' => 'Governança',
    ])
        <a href="{{ route('memberpanel.governance.assemblies.index') }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Assembleias
        </a>

        <form method="post" action="{{ route('memberpanel.governance.assemblies.store') }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-2xl">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Tipo</label>
                <select name="type"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="ordinaria">Ordinária</option>
                    <option value="extraordinaria">Extraordinária</option>
                </select>
                @error('type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Título</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Data e hora</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                @error('scheduled_at')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Local</label>
                <input type="text" name="location" value="{{ old('location') }}"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Notas de convocação</label>
                <textarea name="convocation_notes" rows="4"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('convocation_notes') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">Guardar</button>
                <a href="{{ route('memberpanel.governance.assemblies.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-sm font-semibold text-gray-700 dark:text-gray-300">Cancelar</a>
            </div>
        </form>
    @endcomponent
@endsection
