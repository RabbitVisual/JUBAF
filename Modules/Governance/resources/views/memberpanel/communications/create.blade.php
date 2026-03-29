@extends('memberpanel::components.layouts.master')

@section('page-title', 'Novo comunicado')

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Novo comunicado',
        'subtitle' => 'Texto oficial para transparência e painel.',
        'badge' => 'Governança',
    ])
        <a href="{{ route('memberpanel.governance.communications.index') }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-4">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Comunicados
        </a>

        <form method="post" action="{{ route('memberpanel.governance.communications.store') }}"
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 space-y-4 shadow-sm max-w-2xl">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Título</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Resumo</label>
                <textarea name="summary" rows="2"
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('summary') }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Corpo</label>
                <textarea name="body" rows="12" required
                    class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">{{ old('body') }}</textarea>
            </div>
            <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))
                    class="rounded border-gray-300 dark:border-slate-600">
                Publicar no site
            </label>
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold">Guardar</button>
                <a href="{{ route('memberpanel.governance.communications.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-sm font-semibold">Cancelar</a>
            </div>
        </form>
    @endcomponent
@endsection
