@extends('layouts.app')

@section('title', $book->name . ' ' . $chapter->chapter_number . ' - ' . $version->name)

@section('content')
    <x-bible::admin.layout
        title="{{ $book->name }} {{ $chapter->chapter_number }}"
        subtitle="Leitura de conferência do texto importado. {{ $version->name }} ({{ $version->abbreviation }}) · {{ $verses->count() }} versículos">
        <x-slot name="actions">
            @if($previousChapter)
                <a href="{{ bible_admin_route('chapter', ['version' => $version->id, 'book' => $previousChapter->book_id, 'chapter' => $previousChapter->id]) }}"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-semibold rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                    <x-icon name="chevron-left" style="duotone" class="w-5 h-5 shrink-0" />
                    Anterior
                </a>
            @endif
            @if($nextChapter)
                <a href="{{ bible_admin_route('chapter', ['version' => $version->id, 'book' => $nextChapter->book_id, 'chapter' => $nextChapter->id]) }}"
                    class="inline-flex items-center gap-1 px-3 py-2 text-sm font-semibold rounded-xl bg-amber-600 text-white hover:bg-amber-700 shadow-sm transition-colors">
                    Próximo
                    <x-icon name="chevron-right" style="duotone" class="w-5 h-5 shrink-0" />
                </a>
            @endif
        </x-slot>

        <nav class="flex items-center flex-wrap gap-x-2 gap-y-1 text-sm text-stone-600 dark:text-stone-400 mb-2 max-w-6xl">
            <a href="{{ bible_admin_route('index') }}" class="hover:text-amber-800 dark:hover:text-amber-300 transition-colors">Bíblia Digital</a>
            <x-icon name="chevron-right" style="duotone" class="w-4 h-4 shrink-0 opacity-70" />
            <a href="{{ bible_admin_route('show', $version) }}" class="hover:text-amber-800 dark:hover:text-amber-300 transition-colors">{{ $version->name }}</a>
            <x-icon name="chevron-right" style="duotone" class="w-4 h-4 shrink-0 opacity-70" />
            <a href="{{ bible_admin_route('book', ['version' => $version->id, 'book' => $book->id]) }}" class="hover:text-amber-800 dark:hover:text-amber-300 transition-colors">{{ $book->name }}</a>
            <x-icon name="chevron-right" style="duotone" class="w-4 h-4 shrink-0 opacity-70" />
            <span class="text-stone-900 dark:text-stone-100 font-medium">Capítulo {{ $chapter->chapter_number }}</span>
        </nav>

        <!-- Reading Content -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden max-w-6xl">
            @if($verses->isEmpty())
                <div class="text-center py-16 px-6">
                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-icon name="book-open" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum versículo encontrado</h3>
                    <p class="text-gray-600 dark:text-gray-400">Este capítulo ainda não possui versículos importados.</p>
                </div>
            @else
                <div class="p-8 md:p-12 lg:p-16 bg-gray-50/50 dark:bg-slate-900/30">
                    <div class="max-w-4xl mx-auto space-y-6">
                        @foreach($verses as $verse)
                            <div class="group flex items-start gap-4 p-4 rounded-xl hover:bg-white dark:hover:bg-slate-800/80 transition-all duration-200 border border-transparent hover:border-gray-200 dark:hover:border-slate-600">
                                <span class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-amber-600 text-white font-bold text-sm shadow-sm font-serif">
                                    {{ $verse->verse_number }}
                                </span>
                                <p class="flex-1 text-gray-900 dark:text-white text-lg leading-relaxed font-serif" style="line-height: 1.9;">
                                    {{ $verse->text }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Bottom Navigation -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200 dark:border-slate-700">
            <a href="{{ bible_admin_route('book', ['version' => $version->id, 'book' => $book->id]) }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-left" style="duotone" class="w-4 h-4 mr-2" />
                Voltar para {{ $book->name }}
            </a>

            <div class="flex items-center gap-3">
                @if($previousChapter)
                    <a href="{{ bible_admin_route('chapter', ['version' => $version->id, 'book' => $previousChapter->book_id, 'chapter' => $previousChapter->id]) }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-amber-700 dark:hover:text-amber-400 transition-colors">
                        <x-icon name="chevron-left" style="duotone" class="w-4 h-4 mr-1" />
                        Cap. {{ $previousChapter->chapter_number }}
                    </a>
                @endif

                @if($nextChapter)
                    <a href="{{ bible_admin_route('chapter', ['version' => $version->id, 'book' => $nextChapter->book_id, 'chapter' => $nextChapter->id]) }}"
                        class="inline-flex items-center gap-1 px-4 py-2 text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 rounded-xl shadow-sm transition-colors">
                        Cap. {{ $nextChapter->chapter_number }}
                        <x-icon name="chevron-right" style="duotone" class="w-4 h-4 ml-1" />
                    </a>
                @endif
            </div>
        </div>
    </x-bible::admin.layout>
@endsection

