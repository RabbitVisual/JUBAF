@extends('admin::layouts.app')

@section('title', 'Fontes de comentário')

@section('content')
    <x-bible::admin.layout title="Fontes de comentário"
        subtitle="Obras em domínio público ou com licença explícita. Texto protegido não deve ser importado sem autorização.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.commentary.entries.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                Entradas
            </a>
            <a href="{{ bible_admin_route('study.strongs.index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                Léxico Strong
            </a>
        </x-slot>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-left text-gray-600 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Título</th>
                            <th class="px-4 py-3 font-semibold">Slug</th>
                            <th class="px-4 py-3 font-semibold">Entradas</th>
                            <th class="px-4 py-3 font-semibold">Ativa</th>
                            <th class="px-4 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($sources as $source)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $source->title }}</td>
                                <td class="px-4 py-3 font-mono text-gray-600 dark:text-gray-300">{{ $source->slug }}</td>
                                <td class="px-4 py-3">{{ $source->entries_count }}</td>
                                <td class="px-4 py-3">{{ $source->is_active ? 'Sim' : 'Não' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ bible_admin_route('study.commentary.sources.edit', $source->id) }}"
                                        class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-gray-500">Nenhuma fonte. Importe com
                                    <code>php artisan bible:import-commentary</code>.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $sources->links() }}
            </div>
        </div>
    </x-bible::admin.layout>
@endsection
