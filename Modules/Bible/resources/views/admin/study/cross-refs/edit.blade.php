@extends('admin::layouts.app')

@section('title', 'Editar ref. cruzada #'.$row->id)

@section('content')
    <x-bible::admin.layout title="Editar referência cruzada" subtitle="Coordenadas canónicas (número do livro 1–66).">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.cross-refs.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                Lista
            </a>
        </x-slot>

        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form method="post" action="{{ bible_admin_route('study.cross-refs.update', $row->id) }}"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 p-6 space-y-4 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold mb-1">Testamento</label>
                <select name="testament" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="old" @selected(old('testament', $row->testament) === 'old')>Antigo</option>
                    <option value="new" @selected(old('testament', $row->testament) === 'new')>Novo</option>
                </select>
            </div>
            <p class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Origem</p>
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Livro</label>
                    <input type="number" name="from_book_number" value="{{ old('from_book_number', $row->from_book_number) }}" required min="1" max="66"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Capítulo</label>
                    <input type="number" name="from_chapter" value="{{ old('from_chapter', $row->from_chapter) }}" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Versículo</label>
                    <input type="number" name="from_verse" value="{{ old('from_verse', $row->from_verse) }}" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <p class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider">Destino</p>
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-semibold mb-1">Livro</label>
                    <input type="number" name="to_book_number" value="{{ old('to_book_number', $row->to_book_number) }}" required min="1" max="66"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Capítulo</label>
                    <input type="number" name="to_chapter" value="{{ old('to_chapter', $row->to_chapter) }}" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Versículo</label>
                    <input type="number" name="to_verse" value="{{ old('to_verse', $row->to_verse) }}" required min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold mb-1">Tipo (opcional)</label>
                    <input type="text" name="kind" value="{{ old('kind', $row->kind) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Peso / ordem</label>
                    <input type="number" name="weight" value="{{ old('weight', $row->weight) }}" min="0"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Fonte (slug)</label>
                <input type="text" name="source_slug" value="{{ old('source_slug', $row->source_slug) }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Nota (PT, curta)</label>
                <input type="text" name="note_pt" value="{{ old('note_pt', $row->note_pt) }}" maxlength="512"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit"
                class="px-6 py-3 bg-linear-to-r from-amber-800 to-stone-900 text-amber-50 rounded-xl font-semibold hover:from-amber-900 hover:to-stone-950 shadow-md">Guardar</button>
        </form>
    </x-bible::admin.layout>
@endsection
