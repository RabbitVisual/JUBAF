@extends('paineldiretoria::components.layouts.app')

@section('title', 'Bíblia digital — comentários (fontes)')

@section('content')
    <x-bible::admin.layout title="Fontes de comentário"
        subtitle="Catálogo de obras (domínio público ou licenciadas). Cada fonte agrupa entradas por passagem bíblica.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.commentary.entries.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                Entradas
            </a>
            <a href="{{ bible_admin_route('study.strongs.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                Léxico Strong
            </a>
        </x-slot>

        <div
            class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden shadow-sm">
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
