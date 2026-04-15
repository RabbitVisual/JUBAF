@extends('admin::layouts.app')

@section('title', 'Gerenciar Bíblia Digital')

@section('content')
    <x-bible::admin.layout
        title="Gerenciar Bíblia Digital"
        subtitle="Gerencie e administre todas as versões da Bíblia disponíveis no sistema.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('study.strongs.index') }}"
                class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-amber-900 dark:text-amber-100 bg-amber-100/90 dark:bg-amber-900/50 border border-amber-300/80 dark:border-amber-700/60 rounded-xl hover:bg-amber-200/90 dark:hover:bg-amber-900/70 transition-colors shadow-sm">
                <x-icon name="book-bible" class="w-5 h-5 mr-2" />
                Léxico Strong
            </a>
            <a href="{{ bible_admin_route('import') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-amber-50 bg-linear-to-r from-amber-800 to-stone-900 hover:from-amber-900 hover:to-stone-950 rounded-xl shadow-lg shadow-amber-950/20 transition-all">
                <x-icon name="plus" class="w-5 h-5 mr-2" />
                Importar nova versão
            </a>
        </x-slot>

        <!-- Versions Grid -->
        @if ($versions->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($versions as $version)
                    <div
                        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-amber-200/70 dark:border-amber-900/45 hover:shadow-lg hover:border-amber-400/80 dark:hover:border-amber-700/60 transition-all duration-300 overflow-hidden">
                        <!-- Card Header -->
                        <div
                            class="p-6 bg-linear-to-br from-amber-50/90 to-stone-100/80 dark:from-stone-800 dark:to-amber-950/40 border-b border-amber-200/60 dark:border-amber-900/40">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ $version->name }}
                                    </h3>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-200/80 text-amber-950 dark:bg-amber-900/40 dark:text-amber-200">
                                            {{ $version->abbreviation }}
                                        </span>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400">{{ $version->language }}</span>
                                        @if ($version->is_default)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-linear-to-r from-yellow-400 to-yellow-500 text-yellow-900 shadow-sm">
                                                <x-icon name="star" class="w-3 h-3 mr-1" />
                                                Padrão
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <span
                                    class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $version->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ $version->is_active ? 'Ativa' : 'Inativa' }}
                                </span>
                            </div>
                            @if ($version->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $version->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Statistics -->
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ $version->books_count ?? 0 }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Livros</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($version->total_chapters) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Capítulos</div>
                                </div>
                                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($version->total_verses) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Versículos</div>
                                </div>
                            </div>

                            @if ($version->imported_at)
                                <div
                                    class="flex items-center text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <x-icon name="clock" style="duotone" class="w-4 h-4 mr-2" />
                                    Importado em {{ $version->imported_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div
                            class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <a href="{{ bible_admin_route('show', $version) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-amber-900 dark:text-amber-100 bg-amber-100 dark:bg-amber-900/40 rounded-lg hover:bg-amber-200 dark:hover:bg-amber-900/60 transition-colors"
                                    title="Visualizar">
                                    <x-icon name="eye" style="duotone" class="w-4 h-4 mr-1.5" />
                                    Ver
                                </a>
                                <a href="{{ bible_admin_route('edit', $version) }}"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-stone-800 dark:text-stone-100 bg-stone-200/80 dark:bg-stone-700 rounded-lg hover:bg-stone-300 dark:hover:bg-stone-600 transition-colors"
                                    title="Editar">
                                    <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 mr-1.5" />
                                    Editar
                                </a>
                            </div>
                            <form action="{{ bible_admin_route('destroy', $version) }}" method="POST" class="inline"
                                onsubmit="return confirm('Tem certeza que deseja excluir esta versão? Todos os dados serão perdidos.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50 transition-colors"
                                    title="Excluir">
                                    <x-icon name="trash-can" style="duotone" class="w-4 h-4" />
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div
                class="flex flex-col items-center justify-center py-16 px-4 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-amber-300/70 dark:border-amber-800/60">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <x-icon name="book-open" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhuma versão cadastrada</h3>
                <p class="text-gray-600 dark:text-gray-400 text-center max-w-md mb-6">Comece importando uma versão da
                    Bíblia para disponibilizar aos membros do sistema.</p>
                <a href="{{ bible_admin_route('import') }}"
                    class="inline-flex items-center px-6 py-3 text-sm font-semibold text-amber-50 bg-linear-to-r from-amber-800 to-stone-900 hover:from-amber-900 hover:to-stone-950 rounded-xl shadow-lg transition-all duration-200">
                    <x-icon name="plus" class="w-5 h-5 mr-2" />
                    Importar Primeira Versão
                </a>
            </div>
        @endif
    </x-bible::admin.layout>
@endsection

