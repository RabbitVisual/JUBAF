@extends('layouts.app')

@section('title', 'Entradas de comentário')

@section('content')
    <x-bible::admin.layout title="Entradas de comentário"
        subtitle="Intervalos de versículos (livro / capítulo / versículo).">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.commentary.sources.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                Fontes
            </a>
        </x-slot>

        <form method="get" class="flex flex-wrap gap-3 items-end mb-6">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Nº do livro (1–66)</label>
                <input type="number" name="book_number" value="{{ $book }}" min="1" max="66"
                    class="w-40 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-linear-to-r from-amber-800 to-stone-900 text-amber-50 rounded-xl text-sm font-semibold">Filtrar</button>
        </form>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-left text-gray-600 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">ID</th>
                            <th class="px-4 py-3 font-semibold">Fonte</th>
                            <th class="px-4 py-3 font-semibold">Ref.</th>
                            <th class="px-4 py-3 font-semibold">Ativa</th>
                            <th class="px-4 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($entries as $entry)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 font-mono">{{ $entry->id }}</td>
                                <td class="px-4 py-3">{{ $entry->source?->title }}</td>
                                <td class="px-4 py-3 font-mono text-xs">
                                    {{ $entry->book_number }}:{{ $entry->chapter_from }}:{{ $entry->verse_from }}
                                    — {{ $entry->chapter_to }}:{{ $entry->verse_to }}
                                </td>
                                <td class="px-4 py-3">{{ $entry->is_active ? 'Sim' : 'Não' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ bible_admin_route('study.commentary.entries.edit', $entry->id) }}"
                                        class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-500">Nenhuma entrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $entries->links() }}
            </div>
        </div>
    </x-bible::admin.layout>
@endsection
