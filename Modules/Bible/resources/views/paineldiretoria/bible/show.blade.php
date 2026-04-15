@extends('paineldiretoria::components.layouts.app')

@section('title', 'Bíblia digital — ' . $version->name)

@section('content')
    <x-bible::admin.layout
        title="{{ $version->name }}"
        subtitle="Resumo da versão, contagens e acesso aos livros. {{ $version->abbreviation }} · {{ $version->language }} · {{ $version->is_active ? 'Ativa' : 'Inativa' }}{{ $version->is_default ? ' · Padrão' : '' }}">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('edit', $version) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="pen-to-square" style="duotone" class="w-4 h-4 shrink-0" />
                Editar
            </a>
            <a href="{{ bible_admin_route('index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl bg-amber-600 text-white hover:bg-amber-700 transition-colors shadow-sm">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 shrink-0" />
                Todas as versões
            </a>
        </x-slot>

        <!-- Breadcrumb -->
        <nav class="flex items-center flex-wrap gap-x-2 gap-y-1 text-sm text-stone-600 dark:text-stone-400 mb-2">
            <a href="{{ bible_admin_route('index') }}" class="hover:text-amber-800 dark:hover:text-amber-300 transition-colors">Bíblia Digital</a>
            <x-icon name="chevron-right" style="duotone" class="w-4 h-4 shrink-0 opacity-70" />
            <span class="text-stone-900 dark:text-stone-100 font-medium">{{ $version->name }}</span>
        </nav>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-amber-100 dark:bg-amber-900/40 rounded-lg flex items-center justify-center">
                        <x-icon name="book-open" class="w-5 h-5 text-amber-800 dark:text-amber-300" />
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1 text-gray-900 dark:text-white">{{ $version->total_books }}</div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Livros</div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-amber-100 dark:bg-amber-900/40 rounded-lg flex items-center justify-center">
                        <x-icon name="file-lines" class="w-5 h-5 text-amber-800 dark:text-amber-300" />
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1 text-gray-900 dark:text-white">{{ number_format($version->total_chapters) }}</div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Capítulos</div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-11 h-11 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                        <x-icon name="file-lines" class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                    </div>
                </div>
                <div class="text-3xl font-bold mb-1 text-gray-900 dark:text-white">{{ number_format($version->total_verses) }}</div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Versículos</div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                <x-icon name="circle-info" style="duotone" class="w-5 h-5 mr-2 text-gray-400" />
                Informações da Versão
            </h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                    <div class="flex items-center gap-2">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $version->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $version->is_active ? 'Ativa' : 'Inativa' }}
                        </span>
                        @if($version->is_default)
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-linear-to-r from-yellow-400 to-yellow-500 text-yellow-900">
                                Padrão
                            </span>
                        @endif
                    </div>
                </div>
                @if($version->imported_at)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Importado em:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $version->imported_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Books Section -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/50">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center font-serif">
                    <x-icon name="book-open" class="w-6 h-6 mr-2 text-amber-800 dark:text-amber-300" />
                    Livros da Bíblia
                </h2>
            </div>

            @if($books->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-icon name="book-open" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nenhum livro importado</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Esta versão da Bíblia ainda não possui livros importados.</p>
                    <a href="{{ bible_admin_route('import') }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-colors">
                        <x-icon name="plus" class="w-5 h-5 mr-2" />
                        Importar Bíblia
                    </a>
                </div>
            @else
                <div class="grid md:grid-cols-2 gap-6 p-6">
                    <!-- Old Testament -->
                    <div>
                        <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="w-1 h-8 bg-linear-to-b from-amber-400 to-amber-600 rounded-full mr-3"></div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Antigo Testamento</h3>
                            <span class="ml-auto text-sm text-gray-500 dark:text-gray-400">{{ $oldTestament->count() }} livros</span>
                        </div>
                        <div class="space-y-1 max-h-[600px] overflow-y-auto pr-2">
                            @forelse($oldTestament as $book)
                                <a href="{{ bible_admin_route('book', ['version' => $version->id, 'book' => $book->id]) }}"
                                    class="group flex items-center justify-between p-3 rounded-xl hover:bg-amber-50/80 dark:hover:bg-gray-700 transition-all duration-200 border border-transparent hover:border-amber-200/80 dark:hover:border-gray-600">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-linear-to-br from-amber-700 to-amber-900 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ $book->book_number }}
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white group-hover:text-amber-900 dark:group-hover:text-amber-200 transition-colors">{{ $book->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $book->chapters_count }} cap.</span>
                                        <span>•</span>
                                        <span>{{ number_format($book->verses_count) }} vers.</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">Nenhum livro do Antigo Testamento</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- New Testament -->
                    <div>
                        <div class="flex items-center mb-4 pb-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="w-1 h-8 bg-linear-to-b from-green-400 to-green-600 rounded-full mr-3"></div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Novo Testamento</h3>
                            <span class="ml-auto text-sm text-gray-500 dark:text-gray-400">{{ $newTestament->count() }} livros</span>
                        </div>
                        <div class="space-y-1 max-h-[600px] overflow-y-auto pr-2">
                            @forelse($newTestament as $book)
                                <a href="{{ bible_admin_route('book', ['version' => $version->id, 'book' => $book->id]) }}"
                                    class="group flex items-center justify-between p-3 rounded-xl hover:bg-stone-100/80 dark:hover:bg-gray-700 transition-all duration-200 border border-transparent hover:border-stone-300/80 dark:hover:border-gray-600">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-linear-to-br from-stone-600 to-stone-800 rounded-lg flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ $book->book_number }}
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white group-hover:text-stone-800 dark:group-hover:text-stone-200 transition-colors">{{ $book->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $book->chapters_count }} cap.</span>
                                        <span>•</span>
                                        <span>{{ number_format($book->verses_count) }} vers.</span>
                                    </div>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400 py-4 text-center">Nenhum livro do Novo Testamento</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-bible::admin.layout>
@endsection

