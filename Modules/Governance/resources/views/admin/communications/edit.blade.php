@extends('admin::components.layouts.master')

@section('title', 'Editar comunicado')

@section('content')
    <div class="max-w-2xl space-y-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar comunicado</h1>
        <form method="post" action="{{ route('admin.governance.communications.update', $communication) }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium mb-1">Título</label>
                <input type="text" name="title" value="{{ old('title', $communication->title) }}" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Resumo</label>
                <textarea name="summary" rows="2" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('summary', $communication->summary) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Corpo</label>
                <textarea name="body" rows="12" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('body', $communication->body) }}</textarea>
            </div>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $communication->is_published)) class="rounded border-gray-300">
                Publicar no site
            </label>
            <div class="flex flex-wrap gap-3">
                <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium">Guardar</button>
                <a href="{{ route('admin.governance.communications.index') }}" class="px-4 py-2 rounded-xl border text-sm">Cancelar</a>
            </div>
        </form>
        <form method="post" action="{{ route('admin.governance.communications.destroy', $communication) }}" onsubmit="return confirm('Remover?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm text-red-600 hover:underline">Eliminar</button>
        </form>
    </div>
@endsection
