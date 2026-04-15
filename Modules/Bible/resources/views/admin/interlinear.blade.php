@extends('layouts.app')

@section('title', __('Bíblia digital — leitor interlinear'))

@section('content')
    <x-bible::admin.layout
        title="{{ __('Leitor interlinear') }}"
        subtitle="{{ __('Texto original, léxico Strong, ocorrências e referências — mesmo motor que o site e os painéis de jovens/líderes.') }}">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-amber-200/80 dark:border-amber-900/50 text-sm font-semibold text-stone-800 dark:text-stone-100 bg-white dark:bg-stone-800 hover:bg-amber-50 dark:hover:bg-stone-700 transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 shrink-0" />
                {{ __('Versões e livros') }}
            </a>
        </x-slot>

        @include('bible::public.partials.sacred-reader-theme')
        <script>
        window.__interlinearConfig = {
            bibleVersions: @json($bibleVersions ?? []),
            panelMode: true,
            backUrl: @json($panelInterlinearBackUrl ?? route('admin.bible.index')),
            backLabel: @json(__('Área Bíblia digital')),
            publicBibleIndexUrl: @json(route('bible.public.index')),
            publicInterlinearPath: @json(url(route('bible.public.interlinear', [], false))),
            routes: {
                books: @json(route('bible.public.interlinear.books')),
                data: @json(route('bible.public.interlinear.data')),
                strongPrefix: @json(\Illuminate\Support\Str::beforeLast(route('bible.public.interlinear.strong', ['number' => 'H1']), 'H1')),
                occurrencesPrefix: @json(\Illuminate\Support\Str::beforeLast(route('bible.public.interlinear.occurrences', ['number' => 'H1']), 'H1')),
            },
            loadErrorMessage: @json(__('Não foi possível carregar o capítulo. Tente novamente.')),
        };
        </script>
        @include('bible::public.partials.interlinear-body')
    </x-bible::admin.layout>
@endsection
