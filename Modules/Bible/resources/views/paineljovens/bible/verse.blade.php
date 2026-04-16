@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@php
    $bk = $verse->chapter->book ?? null;
    $ch = $verse->chapter ?? null;
    $ver = $bk?->bibleVersion;
@endphp

@section('title', __('Versículo'))

@section('jovens_content')
    <div class="max-w-2xl mx-auto pb-12 -mt-2">
        <article class="jovens-bible-paper rounded-3xl border border-gray-200/90 dark:border-gray-800 p-8 md:p-10 shadow-lg">
            @if($bk && $ch)
                <p class="text-[11px] font-bold uppercase tracking-widest text-blue-700 dark:text-blue-400">
                    {{ $bk->name }} {{ $ch->chapter_number }}:{{ $verse->verse_number }}
                    @if($ver)
                        <span class="text-gray-500 dark:text-gray-500 font-semibold"> · {{ $ver->abbreviation }}</span>
                    @endif
                </p>
            @endif

            <blockquote class="mt-6 jovens-bible-serif text-xl md:text-2xl text-gray-800 dark:text-gray-200 leading-[1.75]">
                {{ $verse->text }}
            </blockquote>

            <div class="mt-10 flex flex-col sm:flex-row gap-3">
                @if($ver && $bk && $ch)
                    <a href="{{ route('jovens.bible.chapter', ['version' => $ver->abbreviation, 'book' => $bk->book_number, 'chapter' => $ch->chapter_number]) }}#verse-{{ $verse->verse_number }}"
                       class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-3.5 text-sm font-semibold shadow-md shadow-blue-600/15 transition-colors">
                        <x-icon name="book-open" class="w-4 h-4" />
                        Ver no capítulo
                    </a>
                    <a href="{{ route('jovens.bible.read', $ver->abbreviation) }}"
                       class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-5 py-3.5 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-400/50 transition-colors">
                        Lista de livros
                    </a>
                @else
                    <a href="{{ route('jovens.bible.index') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-5 py-3.5 text-sm font-semibold">
                        Ir à Bíblia
                    </a>
                @endif
            </div>
        </article>
    </div>
@endsection
