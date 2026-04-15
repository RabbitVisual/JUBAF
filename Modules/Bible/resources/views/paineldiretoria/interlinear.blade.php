@extends('paineldiretoria::components.layouts.app')

@section('title', __('Bíblia digital — leitor interlinear'))

@section('content')
    <x-bible::admin.layout
        title="{{ __('Leitor interlinear') }}"
        subtitle="{{ __('Ferramenta de estudo: palavra a palavra, Strong, ocorrências e ligações — alinhado ao leitor público e aos painéis de leitura.') }}">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 text-sm font-semibold text-gray-800 dark:text-gray-100 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4 shrink-0" />
                {{ __('Versões e livros') }}
            </a>
        </x-slot>

        @include('bible::public.partials.sacred-reader-theme')
        <script>
        window.__interlinearConfig = {
            bibleVersions: @json($bibleVersions ?? []),
            panelMode: true,
            backUrl: @json($panelInterlinearBackUrl ?? route('diretoria.bible.index')),
            backLabel: @json(__('Bíblia digital (diretoria)')),
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
