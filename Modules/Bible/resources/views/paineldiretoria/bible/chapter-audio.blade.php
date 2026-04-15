@extends('paineldiretoria::components.layouts.app')

@section('title', 'Áudios por capítulo: ' . $version->name)

@section('content')
    <x-bible::admin.layout
        title="Áudios por capítulo"
        subtitle="{{ $version->name }} ({{ $version->abbreviation }}) — CSV com URLs por livro e capítulo.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('edit', $version) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 shrink-0" />
                Voltar à versão
            </a>
        </x-slot>

        <div class="rounded-xl border border-amber-200 dark:border-amber-800/60 bg-amber-50/80 dark:bg-amber-950/25 p-4 text-sm text-amber-900 dark:text-amber-100">
            <p class="font-semibold mb-1">Google Drive</p>
            <p>Use o link de visualização do arquivo (ex.: <code class="bg-amber-100 dark:bg-amber-800/50 px-1 rounded">https://drive.google.com/file/d/FILE_ID/view</code>). O sistema converte automaticamente em link de download para o player. Cada linha do CSV deve ter: <code class="bg-amber-100 dark:bg-amber-800/50 px-1 rounded">book_number</code>, <code class="bg-amber-100 dark:bg-amber-800/50 px-1 rounded">chapter_number</code>, <code class="bg-amber-100 dark:bg-amber-800/50 px-1 rounded">audio_url</code>.</p>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <x-icon name="file-arrow-up" style="duotone" class="w-5 h-5 mr-2 text-amber-800 dark:text-amber-300" />
                    Importar CSV
                </h2>
            </div>
            <form action="{{ bible_admin_route('chapter-audio.store', $version) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="csv" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Arquivo CSV</label>
                        <input type="file" id="csv" name="csv" accept=".csv,.txt" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white text-sm">
                        @error('csv')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-colors">
                        Importar
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Primeira linha = cabeçalho: book_number, chapter_number, audio_url.
                    <a href="{{ bible_admin_route('chapter-audio.template', $version) }}" class="text-blue-600 dark:text-blue-400 hover:underline ml-1">Baixar modelo CSV</a>
                </p>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between bg-gray-50 dark:bg-slate-900/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <x-icon name="list" style="duotone" class="w-5 h-5 mr-2 text-gray-500" />
                    Capítulos com áudio ({{ $audios->total() }})
                </h2>
            </div>
            @if ($audios->isEmpty())
                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                    Nenhum áudio por capítulo cadastrado. Importe um CSV com as colunas book_number, chapter_number, audio_url.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Livro</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">Cap.</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300">URL</th>
                                <th class="px-4 py-3 w-20"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($audios as $audio)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $audio->book_number }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $audio->chapter_number }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 font-mono text-xs max-w-md truncate" title="{{ $audio->audio_url }}">{{ $audio->audio_url }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ bible_admin_route('chapter-audio.destroy', [$version, $audio]) }}" method="POST" onsubmit="return confirm('Remover este áudio?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" aria-label="Excluir">
                                                <x-icon name="trash" class="w-4 h-4" />
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $audios->links() }}
                </div>
            @endif
        </div>
    </x-bible::admin.layout>
@endsection
