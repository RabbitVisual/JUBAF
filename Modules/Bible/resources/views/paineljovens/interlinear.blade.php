@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', __('Bíblia interlinear — :app', ['app' => config('app.name')]))

@section('jovens_content')
    {{-- Faixa contextual: reforça que tudo permanece no painel Jovens --}}
    <div class="mb-6 space-y-4 -mt-1 max-w-[88rem] mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 rounded-3xl border border-blue-200/80 dark:border-blue-900/50 bg-gradient-to-r from-blue-50/95 via-white to-gray-50/40 dark:from-blue-950/40 dark:via-gray-900 dark:to-gray-950/20 px-5 py-4 shadow-sm">
            <div class="flex gap-3 min-w-0">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md shadow-blue-600/20">
                    <x-icon name="layer-group" class="w-5 h-5" />
                </span>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-900 dark:text-gray-50">Estudo interlinear no Unijovem</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 leading-relaxed">
                        Texto original, Strong e comparação de versões — <strong class="text-gray-800 dark:text-gray-200">sem sair do painel de jovens</strong>. O botão «voltar» no leitor leva ao leitor por capítulos.
                    </p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('jovens.bible.read') }}"
                   class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-400/60 transition-colors">
                    <x-icon name="book-open" class="w-4 h-4 text-blue-600" />
                    Leitor por capítulo
                </a>
                <a href="{{ route('jovens.bible.search') }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-xs font-semibold shadow-md shadow-blue-600/15 transition-colors">
                    <x-icon name="magnifying-glass" class="w-4 h-4" />
                    Busca
                </a>
            </div>
        </div>
    </div>

    @include('bible::public.partials.sacred-reader-theme')
    <script>
    window.__interlinearConfig = {
        bibleVersions: @json($bibleVersions ?? []),
        panelMode: true,
        backUrl: @json($panelInterlinearBackUrl ?? route('jovens.bible.read')),
        backLabel: @json(__('Voltar ao leitor (painel)')),
        {{-- Fallback se algum fluxo não usar panelMode: mantém no painel, nunca na home pública --}}
        publicBibleIndexUrl: @json(route('jovens.bible.index')),
        publicInterlinearPath: @json(url(route('bible.public.interlinear', [], false))),
        routes: {
            books: @json(route('bible.public.interlinear.books')),
            data: @json(route('bible.public.interlinear.data')),
            strongPrefix: @json(\Illuminate\Support\Str::beforeLast(route('bible.public.interlinear.strong', ['number' => 'H1']), 'H1')),
            occurrencesPrefix: @json(\Illuminate\Support\Str::beforeLast(route('bible.public.interlinear.occurrences', ['number' => 'H1']), 'H1')),
        },
        loadErrorMessage: @json(__('Não foi possível carregar o capítulo. Tenta novamente.')),
    };
    </script>
    @include('bible::public.partials.interlinear-body')
@endsection
