@extends('admin::components.layouts.master')

@section('title', 'Nova assembleia')

@section('content')
    <div class="max-w-2xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova assembleia</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Registar convocação e dados básicos.</p>
        </div>

        <form method="post" action="{{ route('admin.governance.assemblies.store') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4 shadow-sm">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="ordinaria">Ordinária</option>
                    <option value="extraordinaria">Extraordinária</option>
                </select>
                @error('type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data e hora</label>
                <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                @error('scheduled_at')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Local</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notas de convocação</label>
                <textarea name="convocation_notes" rows="4" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('convocation_notes') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Guardar</button>
                <a href="{{ route('admin.governance.assemblies.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
