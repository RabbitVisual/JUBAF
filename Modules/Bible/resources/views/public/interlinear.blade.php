@extends('layouts.bible-public-homepage')

@section('title', __('Bíblia interlinear — :app', ['app' => config('app.name')]))

@section('meta_description', __('Leitura interlinear: texto original, léxico Strong e comparação de versões.'))

@push('head')
    @include('bible::public.partials.sacred-reader-theme')
@endpush

@section('bible_public_content')
<script>
window.__interlinearConfig = {
    bibleVersions: @json($bibleVersions ?? []),
    panelMode: false,
    backUrl: '',
    backLabel: '',
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
@endsection
