@extends('admin::layouts.app')

@section('title', 'Editar comentário #' . $entry->id)

@section('content')
    <x-bible::admin.layout title="Editar entrada de comentário" :subtitle="$entry->source?->title">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.commentary.entries.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                Lista
            </a>
        </x-slot>

        @if (session('success'))
            <div
                class="mb-4 p-4 text-sm text-green-800 bg-green-50 border border-green-200 rounded-lg dark:bg-green-900/20 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <form method="post" action="{{ bible_admin_route('study.commentary.entries.update', $entry->id) }}"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 p-6 space-y-4 shadow-sm">
            @csrf
            @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold mb-1">Livro (nº)</label>
                    <input type="number" name="book_number" value="{{ old('book_number', $entry->book_number) }}" required
                        min="1" max="66"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    @error('book_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Ordem</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $entry->sort_order) }}"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold mb-1">De — capítulo</label>
                    <input type="number" name="chapter_from" value="{{ old('chapter_from', $entry->chapter_from) }}"
                        required min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">De — versículo</label>
                    <input type="number" name="verse_from" value="{{ old('verse_from', $entry->verse_from) }}" required
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold mb-1">Até — capítulo</label>
                    <input type="number" name="chapter_to" value="{{ old('chapter_to', $entry->chapter_to) }}" required
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Até — versículo</label>
                    <input type="number" name="verse_to" value="{{ old('verse_to', $entry->verse_to) }}" required
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Texto</label>
                <textarea name="body" rows="10" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white font-mono text-sm">{{ old('body', $entry->body) }}</textarea>
                @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $entry->is_active))
                    class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Entrada Ativa</label>
            </div>
            <button type="submit"
                class="px-6 py-3 bg-linear-to-r from-amber-800 to-stone-900 text-amber-50 rounded-xl font-semibold hover:from-amber-900 hover:to-stone-950 shadow-md">Guardar</button>
        </form>
    </x-bible::admin.layout>
@endsection
