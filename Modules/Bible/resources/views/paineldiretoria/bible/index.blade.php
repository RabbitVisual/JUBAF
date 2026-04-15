@extends('paineldiretoria::components.layouts.app')

@section('title', 'Bíblia digital — versões')

@section('content')
    <x-bible::admin.layout
        title="Versões e livros"
        subtitle="Lista de traduções importadas, estado e atalhos para editar ou remover.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.strongs.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="book-bible" class="w-5 h-5 shrink-0" />
                Léxico Strong
            </a>
            <a href="{{ bible_admin_route('import') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl bg-amber-600 text-white hover:bg-amber-700 transition-colors shadow-sm">
                <x-icon name="plus" class="w-5 h-5 shrink-0" />
                Importar nova versão
            </a>
        </x-slot>

        @if ($versions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($versions as $version)
                    <div
                        class="group rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden hover:border-amber-300 dark:hover:border-amber-700/60 transition-colors">
                        <div
                            class="p-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50/80 dark:bg-slate-900/40">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $version->name }}
                                    </h3>
                                    <div class="flex items-center gap-2 flex-wrap mt-1">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200">
                                            {{ $version->abbreviation }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $version->language }}</span>
                                        @if ($version->is_default)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-900 dark:bg-yellow-900/30 dark:text-yellow-200">
                                                <x-icon name="star" class="w-3 h-3 mr-1" />
                                                Padrão
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span
                                    class="shrink-0 px-2 py-1 text-xs font-semibold rounded-full {{ $version->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $version->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </div>
                            @if ($version->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $version->description }}
                                </p>
                            @endif
                        </div>

                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center p-2.5 bg-gray-50 dark:bg-slate-900/50 rounded-lg">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $version->books_count ?? 0 }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Livros</div>
                                </div>
                                <div class="text-center p-2.5 bg-gray-50 dark:bg-slate-900/50 rounded-lg">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($version->total_chapters) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Capítulos</div>
                                </div>
                                <div class="text-center p-2.5 bg-gray-50 dark:bg-slate-900/50 rounded-lg">
                                    <div class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($version->total_verses) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Versículos</div>
                                </div>
                            </div>

                            @if ($version->imported_at)
                                <div
                                    class="flex items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-slate-700">
                                    <x-icon name="clock" style="duotone" class="w-4 h-4 mr-2 shrink-0" />
                                    Importado em {{ $version->imported_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>

                        <div
                            class="px-5 py-3 bg-gray-50 dark:bg-slate-900/40 border-t border-gray-200 dark:border-slate-700 flex items-center justify-between gap-2">
                            <div class="flex items-center flex-wrap gap-2">
                                <a href="{{ bible_admin_route('show', $version) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-amber-800 dark:text-amber-200 bg-amber-50 dark:bg-amber-950/50 rounded-lg hover:bg-amber-100 dark:hover:bg-amber-900/40 transition-colors">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4 mr-1.5" />
                                    Ver
                                </a>
                                <a href="{{ bible_admin_route('edit', $version) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                                    <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 mr-1.5" />
                                    Editar
                                </a>
                            </div>
                            <form action="{{ bible_admin_route('destroy', $version) }}" method="POST" class="inline"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta versão? Todos os dados serão perdidos.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/40 transition-colors"
                                    title="Excluir">
                                    <x-icon name="trash-can" style="duotone" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div
                class="flex flex-col items-center justify-center py-16 px-4 rounded-xl border-2 border-dashed border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800">
                <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                    <x-icon name="book-open" class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhuma versão cadastrada</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 text-center max-w-md mb-6">Importe um ficheiro JSON para
                    disponibilizar uma tradução aos membros.</p>
                <a href="{{ bible_admin_route('import') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold rounded-xl bg-amber-600 text-white hover:bg-amber-700 transition-colors">
                    <x-icon name="plus" class="w-5 h-5" />
                    Importar primeira versão
                </a>
            </div>
        @endif
    </x-bible::admin.layout>
@endsection
