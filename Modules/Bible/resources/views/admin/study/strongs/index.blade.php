@extends('admin::layouts.app')

@section('title', 'Léxico Strong (estudo)')

@section('content')
    <x-bible::admin.layout
        title="Léxico Strong (BSRTB)"
        subtitle="Equivalente semântico, glossário e notas para o estudo interlinear. Importação: php artisan bible:import-strongs">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('index') }}"
                class="inline-flex items-center px-4 py-2.5 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 border border-amber-200/80 dark:border-amber-900/50 rounded-xl hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                Versões da Bíblia
            </a>
        </x-slot>

        <form method="get" action="{{ bible_admin_route('study.strongs.index') }}" class="flex flex-wrap gap-3 items-end mb-6">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Buscar (nº Strong, lemma…)</label>
                <input type="text" name="q" value="{{ $q }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white"
                    placeholder="Ex: H7225 ou בְּרֵאשִׁית">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-linear-to-r from-amber-800 to-stone-900 text-amber-50 rounded-xl text-sm font-semibold hover:from-amber-900 hover:to-stone-950 shadow-md">Buscar</button>
        </form>

        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-amber-200/70 dark:border-amber-900/45 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-left text-gray-600 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Strong</th>
                            <th class="px-4 py-3 font-semibold">Lemma</th>
                            <th class="px-4 py-3 font-semibold">lemma_br</th>
                            <th class="px-4 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($entries as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $row->strong_number }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-200 max-w-xs truncate" dir="rtl">{{ $row->lemma }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $row->lemma_br }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ bible_admin_route('study.strongs.edit', $row->strong_number) }}"
                                        class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhuma entrada. Importe com <code>bible:import-strongs</code>.</td>
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
