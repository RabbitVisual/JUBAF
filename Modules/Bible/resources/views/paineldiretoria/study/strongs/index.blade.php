@extends('paineldiretoria::components.layouts.app')

@section('title', 'Bíblia digital — Léxico Strong')

@section('content')
    <x-bible::admin.layout
        title="Léxico Strong (BSRTB)"
        subtitle="Equivalente semântico, glossário e notas para o estudo interlinear. Dados em massa: php artisan bible:import-strongs">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 shrink-0" />
                Versões da Bíblia
            </a>
        </x-slot>

        <form method="get" action="{{ bible_admin_route('study.strongs.index') }}" class="flex flex-wrap gap-3 items-end mb-6">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Pesquisar (n.º Strong, lemma…)</label>
                <input type="text" name="q" value="{{ $q }}"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg dark:bg-slate-800 dark:text-white"
                    placeholder="Ex: H7225 ou בְּרֵאשִׁית">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">Pesquisar</button>
        </form>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-slate-900/50 text-left text-xs font-bold uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Strong</th>
                            <th class="px-4 py-3">Lemma</th>
                            <th class="px-4 py-3">lemma_br</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        @forelse ($entries as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-900/40">
                                <td class="px-4 py-3 font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $row->strong_number }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-200 max-w-xs truncate" dir="rtl">{{ $row->lemma }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300 max-w-xs truncate">{{ $row->lemma_br }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ bible_admin_route('study.strongs.edit', $row->strong_number) }}"
                                        class="text-amber-700 dark:text-amber-400 font-semibold hover:underline">Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhuma entrada. Importe com <code class="text-xs bg-gray-100 dark:bg-slate-700 px-1 rounded">bible:import-strongs</code>.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-700">
                {{ $entries->links() }}
            </div>
        </div>
    </x-bible::admin.layout>
@endsection
