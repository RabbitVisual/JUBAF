@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Busca na Bíblia')

@php
    $jovensChapterUrlTemplate = route('jovens.bible.chapter', [
        'version' => '__V__',
        'book' => '__B__',
        'chapter' => '__C__',
    ]);
    $bibleSearchAlpineOpts = [
        'chapterTpl' => $jovensChapterUrlTemplate,
        'defaultVersion' => $defaultVersionAbbr,
    ];
@endphp

@section('jovens_content')
{{-- Aspas simples no atributo: JSON interior usa " e não pode ir dentro de x-data="..." --}}
<div class="pb-20 -mt-2 max-w-4xl mx-auto space-y-8 jovens-bible-search"
     x-data='bibleSearch(@json($bibleSearchAlpineOpts))'>

    {{-- Hero alinhado ao painel Jovens (azul + cinza) --}}
    <header class="jovens-bible-hero px-6 py-8 md:px-10 md:py-10">
        <div class="relative z-[1] flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
            <div class="max-w-xl">
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-blue-700 dark:text-blue-400 mb-2">Concordância · Painel Unijovem</p>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-50 tracking-tight leading-tight">
                    Busca inteligente na Palavra
                </h1>
                <p class="mt-3 text-sm md:text-[15px] text-gray-600 dark:text-gray-400 leading-relaxed">
                    Os resultados abrem <strong class="text-gray-800 dark:text-gray-200">no leitor do painel</strong>, no capítulo e versículo certos — sem saíres para a página pública.
                </p>
                <p class="mt-3 inline-flex items-center gap-2 rounded-xl bg-white/70 dark:bg-gray-900/60 border border-gray-200/80 dark:border-gray-700 px-3 py-2 text-xs font-semibold text-gray-600 dark:text-gray-300">
                    <x-icon name="book" class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" />
                    Versão por defeito: <span class="tabular-nums text-blue-800 dark:text-blue-200">{{ $defaultVersionAbbr }}</span>
                </p>
            </div>

            {{-- Atalhos: só rotas jovens.* --}}
            <nav class="flex flex-wrap gap-2 shrink-0" aria-label="Atalhos da Bíblia no painel">
                <a href="{{ route('jovens.bible.read') }}" class="jovens-bible-quicklink">
                    <x-icon name="book-open" class="w-4 h-4 text-blue-600" />
                    Leitor
                </a>
                <a href="{{ route('jovens.bible.favorites') }}" class="jovens-bible-quicklink">
                    <x-icon name="star" class="w-4 h-4 text-amber-500" />
                    Favoritos
                </a>
                @if(Route::has('jovens.bible.interlinear'))
                    <a href="{{ route('jovens.bible.interlinear') }}" class="jovens-bible-quicklink">
                        <x-icon name="layer-group" class="w-4 h-4 text-blue-600" />
                        Interlinear
                    </a>
                @endif
                @if(Route::has('jovens.bible.plans.index'))
                    <a href="{{ route('jovens.bible.plans.index') }}" class="jovens-bible-quicklink">
                        <x-icon name="calendar-days" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                        Planos
                    </a>
                @endif
            </nav>
        </div>
    </header>

    {{-- Campo de pesquisa: type="text" + inputmode="search" evita o X nativo do browser (duplicava o nosso) --}}
    <div class="sticky top-0 z-20 -mx-4 px-4 md:mx-0 md:px-0 pb-3 pt-0 bg-gray-50/95 dark:bg-gray-950/95 backdrop-blur border-b border-gray-200/80 dark:border-gray-800 md:static md:border-0 md:bg-transparent md:backdrop-none">
        <label class="relative block group jovens-bible-search-field" data-tour="bible-search-input">
            <span class="pointer-events-none absolute left-4 top-1/2 z-[1] -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">
                <x-icon name="magnifying-glass" class="w-5 h-5" />
            </span>
            <input type="text"
                   inputmode="search"
                   enterkeyhint="search"
                   x-model="query"
                   @input.debounce.300ms="performSearch()"
                   placeholder="Escreve pelo menos 3 letras (ex.: amor, paz, esperança)…"
                   autocomplete="off"
                   :class="(loading || query.length > 0) ? 'pr-[3.25rem]' : 'pr-4'"
                   class="jovens-bible-search-input w-full rounded-2xl border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 py-3.5 pl-12 text-base font-semibold text-gray-900 dark:text-gray-100 placeholder:text-gray-400 placeholder:font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none shadow-inner transition-[padding,color,border-color,box-shadow] duration-150">

            {{-- Trailing: só ocupa espaço quando há loading ou texto (evita “zona morta” à direita) --}}
            <div class="absolute right-2 top-1/2 z-[1] flex h-10 min-w-[2.5rem] -translate-y-1/2 items-center justify-center"
                 x-show="loading || query.length > 0"
                 x-cloak>
                <span x-show="loading" class="text-blue-600" x-cloak>
                    <x-icon name="spinner" class="h-5 w-5 animate-spin" />
                </span>
                <button type="button"
                        x-show="query.length > 0 && !loading"
                        @click="query = ''; results = []; hasSearched = false; fetchError = ''"
                        class="flex h-9 w-9 items-center justify-center rounded-xl text-gray-500 transition-colors hover:bg-gray-100 hover:text-rose-600 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-rose-400"
                        aria-label="Limpar pesquisa">
                    <x-icon name="xmark" class="h-5 w-5" />
                </button>
            </div>
        </label>
        <p class="mt-2 text-[11px] leading-relaxed text-gray-500 dark:text-gray-500">
            Dica: no telemóvel, o resultado abre já com o versículo em destaque no leitor do painel.
        </p>
    </div>

    <main>
        <p x-show="fetchError" x-text="fetchError" class="mb-4 rounded-2xl border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-950/30 px-4 py-3 text-sm text-rose-800 dark:text-rose-200" x-cloak></p>

        {{-- Estado inicial --}}
        <div x-show="results.length === 0 && !hasSearched && !fetchError" class="jovens-bible-paper text-center py-16 px-6 rounded-3xl border border-gray-200/90 dark:border-gray-800 shadow-sm">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-br from-blue-100 to-gray-100 dark:from-blue-950/50 dark:to-gray-900/40 text-blue-700 dark:text-blue-400">
                <x-icon name="book-bible" class="w-10 h-10" />
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Começa a pesquisar</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                A concordância procura na tradução <strong class="text-gray-800 dark:text-gray-200">{{ $defaultVersionAbbr }}</strong>. Cada cartão leva-te ao capítulo certo, dentro do Unijovem.
            </p>
        </div>

        {{-- Sem resultados --}}
        <div x-show="results.length === 0 && hasSearched && !loading && !fetchError" x-cloak class="text-center py-14 px-4 jovens-bible-paper rounded-3xl border border-gray-200/90 dark:border-gray-800">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400 mb-4">
                <x-icon name="magnifying-glass" class="w-7 h-7" />
            </div>
            <p class="text-lg font-bold text-gray-900 dark:text-white">
                Nada encontrado para «<span x-text="query" class="text-blue-600 dark:text-blue-400"></span>»
            </p>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                Verifica a ortografia ou experimenta um sinónimo. Também podes explorar o <a href="{{ route('jovens.bible.read') }}" class="font-semibold text-blue-700 dark:text-blue-400 hover:underline">leitor por livro</a>.
            </p>
        </div>

        {{-- Contagem --}}
        <div x-show="results.length > 0" x-cloak class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                <span x-text="results.length"></span>
                <span x-text="results.length === 1 ? 'ocorrência' : 'ocorrências'"></span>
                <span class="text-gray-500 dark:text-gray-400 font-normal"> · {{ $defaultVersionAbbr }}</span>
            </p>
            <a href="{{ route('jovens.bible.read') }}" class="text-xs font-semibold text-blue-700 dark:text-blue-400 hover:underline">
                Abrir lista de livros
            </a>
        </div>

        {{-- Resultados --}}
        <div class="space-y-3" data-tour="bible-search-results">
            <template x-for="verse in results" :key="verse.id">
                <a :href="chapterUrl(verse)"
                   class="jovens-bible-card block p-5 md:p-6 hover:border-blue-400/60 hover:shadow-md transition-all group">

                    <div class="flex justify-between items-start gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-950/60 dark:to-blue-900/40 text-[10px] font-black text-blue-900 dark:text-blue-200">
                                <span x-text="verse.book_abbreviation || String(verse.book_name).substring(0,3).toUpperCase()"></span>
                            </span>
                            <div class="min-w-0">
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                                    <span x-text="verse.book_name"></span>
                                    <span class="text-blue-700 dark:text-blue-400"><span x-text="verse.chapter_number"></span>:<span x-text="verse.verse_number"></span></span>
                                </h3>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mt-0.5">Painel · {{ $defaultVersionAbbr }}</p>
                            </div>
                        </div>
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <x-icon name="chevron-right" class="w-4 h-4" />
                        </span>
                    </div>

                    <p class="jovens-bible-serif text-base text-gray-700 dark:text-gray-300 leading-relaxed" x-html="highlightText(verse.text)"></p>
                </a>
            </template>
        </div>
    </main>
</div>

<script>
    function bibleSearch(opts) {
        opts = opts || {};
        return {
            query: '',
            results: [],
            loading: false,
            hasSearched: false,
            fetchError: '',
            chapterTpl: opts.chapterTpl || '',
            defaultVersion: opts.defaultVersion || 'NVI',

            chapterUrl(verse) {
                return this.chapterTpl
                    .replace('__V__', encodeURIComponent(this.defaultVersion))
                    .replace('__B__', String(verse.book_number))
                    .replace('__C__', String(verse.chapter_number)) + '#verse-' + verse.verse_number;
            },

            async performSearch() {
                this.fetchError = '';
                if (this.query.length < 3) {
                    this.results = [];
                    this.hasSearched = false;
                    return;
                }
                this.loading = true;
                this.hasSearched = true;
                try {
                    const response = await fetch(`{{ route('jovens.bible.api.search') }}?q=${encodeURIComponent(this.query)}`);
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }
                    this.results = await response.json();
                    if (!Array.isArray(this.results)) {
                        this.results = [];
                    }
                } catch (e) {
                    console.error(e);
                    this.results = [];
                    this.fetchError = 'Não foi possível pesquisar agora. Tenta outra vez dentro de instantes.';
                } finally {
                    this.loading = false;
                }
            },

            highlightText(text) {
                if (!text) return '';
                if (!this.query) return String(text);
                const safeQuery = this.query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp('(' + safeQuery + ')', 'gi');
                return String(text).replace(regex, '<mark class="jovens-bible-search-mark">$1</mark>');
            }
        };
    }
</script>
@endsection
