@extends('layouts.app')

@section('title', 'Editar fonte — ' . $source->title)

@section('content')
    <x-bible::admin.layout title="Editar fonte de comentário" :subtitle="$source->slug">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.commentary.sources.index') }}"
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

        <form method="post" action="{{ bible_admin_route('study.commentary.sources.update', $source->id) }}"
            class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 p-6 space-y-4 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Slug (só leitura)</label>
                <input type="text" value="{{ $source->slug }}" readonly
                    class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Título</label>
                <input type="text" name="title" value="{{ old('title', $source->title) }}" required
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Idioma</label>
                <input type="text" name="language" value="{{ old('language', $source->language) }}" required
                    maxlength="8"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                @error('language')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nota legal /
                    licença</label>
                <textarea name="license_note" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">{{ old('license_note', $source->license_note) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">URL template
                    (opcional)</label>
                <input type="text" name="url_template" value="{{ old('url_template', $source->url_template) }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $source->is_active))
                    class="rounded border-gray-300 text-blue-600">
                <label for="is_active" class="text-sm text-gray-700 dark:text-gray-300">Fonte Ativa</label>
            </div>
            <button type="submit"
                class="px-6 py-3 bg-linear-to-r from-amber-800 to-stone-900 text-amber-50 rounded-xl font-semibold hover:from-amber-900 hover:to-stone-950 shadow-md">Guardar</button>
        </form>
    </x-bible::admin.layout>
@endsection
