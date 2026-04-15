@extends('layouts.app')

@section('title', 'Bíblia digital — refs. cruzadas')

@section('content')
    <x-bible::admin.layout
        title="Referências cruzadas"
        subtitle="Ligações entre versículos no leitor. Importação em massa: php artisan bible:import-cross-refs">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.strongs.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                Léxico Strong
            </a>
        </x-slot>

        <form method="get" class="flex flex-wrap gap-3 items-end mb-6">
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Livro origem</label>
                <input type="number" name="from_book_number" value="{{ $fromBook }}" min="1" max="66"
                    class="w-36 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Capítulo origem</label>
                <input type="number" name="from_chapter" value="{{ $fromChapter }}" min="1"
                    class="w-36 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">Filtrar</button>
        </form>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 text-left text-gray-600 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3 font-semibold">De</th>
                            <th class="px-4 py-3 font-semibold">Para</th>
                            <th class="px-4 py-3 font-semibold">Tipo</th>
                            <th class="px-4 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($rows as $row)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 font-mono text-xs">
                                    {{ $row->from_book_number }}:{{ $row->from_chapter }}:{{ $row->from_verse }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs">
                                    {{ $row->to_book_number }}:{{ $row->to_chapter }}:{{ $row->to_verse }}
                                </td>
                                <td class="px-4 py-3">{{ $row->kind ?? '—' }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <a href="{{ bible_admin_route('study.cross-refs.edit', $row->id) }}"
                                        class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">Editar</a>
                                    <form action="{{ bible_admin_route('study.cross-refs.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Remover esta referência?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 font-semibold hover:underline">Apagar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-gray-500">Nenhum registo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-700">
                {{ $rows->links() }}
            </div>
        </div>
    </x-bible::admin.layout>
@endsection
