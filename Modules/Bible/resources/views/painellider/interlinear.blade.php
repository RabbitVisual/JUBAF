@extends('painellider::layouts.lideres')

@section('title', __('Bíblia interlinear — :app', ['app' => config('app.name')]))

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('lideres.bible.plans.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Bíblia</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-600 dark:text-slate-300">Interlinear</span>
@endsection

@section('lideres_content')
    <x-ui.lideres::page-shell noPadding class="w-full max-w-none !mx-0 !space-y-0 !pb-0">
    @include('bible::public.partials.sacred-reader-theme')
    <script>
    window.__interlinearConfig = {
        bibleVersions: @json($bibleVersions ?? []),
        panelMode: true,
        backUrl: @json($panelInterlinearBackUrl ?? route('lideres.dashboard')),
        backLabel: @json(__('Bíblia no painel')),
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
    </x-ui.lideres::page-shell>
@endsection
