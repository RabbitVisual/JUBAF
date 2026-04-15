@extends('blog::layouts.blog')

@section('title', 'Blog — notícias e comunicação JUBAF')
@section('meta_description', 'Notícias, comunicados e artigos da Juventude Batista Feirense (JUBAF).')

@section('content')
<div class="bg-slate-50 dark:bg-slate-950">
    <!-- Hero: destaques -->
    @if($featuredPosts->count() > 0)
    <div class="relative w-full overflow-hidden bg-slate-900" x-data="{ activeSlide: 0, slides: {{ $featuredPosts->count() }}, timer: null }" x-init="timer = setInterval(() => { activeSlide = (activeSlide === slides - 1) ? 0 : activeSlide + 1 }, 6500)">
        <div class="relative min-h-[320px] md:min-h-[420px] lg:min-h-[480px]">
            @foreach($featuredPosts as $index => $post)
            <div x-show="activeSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full">

                @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-55">
                @else
                <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900"></div>
                @endif

                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/50 to-transparent"></div>

                <div class="relative z-10 flex min-h-[320px] md:min-h-[420px] lg:min-h-[480px] items-end">
                    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 md:pb-14 pt-24 md:pt-28">
                        <span class="inline-block rounded-full bg-blue-600/95 text-white text-xs font-bold px-3 py-1.5 uppercase tracking-wider shadow-lg mb-4">
                            {{ $post->category->name }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-white leading-tight max-w-4xl">
                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-200 transition-colors">
                                {{ $post->title }}
                            </a>
                        </h2>
                        @if($post->excerpt)
                        <p class="mt-4 text-base md:text-lg text-slate-200 max-w-2xl line-clamp-2 md:line-clamp-3">
                            {{ strip_tags($post->excerpt) }}
                        </p>
                        @endif
                        <a href="{{ route('blog.show', $post->slug) }}"
                           class="mt-6 inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-blue-900 shadow-lg hover:bg-blue-50 transition-colors">
                            Ler publicação
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($featuredPosts->count() > 1)
        <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-2 z-20">
            @foreach($featuredPosts as $index => $post)
            <button type="button" @click="activeSlide = {{ $index }}"
                    :class="{ 'bg-white w-8': activeSlide === {{ $index }}, 'bg-white/40 w-2': activeSlide !== {{ $index }} }"
                    class="h-2 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/80"
                    aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        <button type="button" @click="activeSlide = (activeSlide === 0) ? slides - 1 : activeSlide - 1"
                class="absolute left-3 md:left-6 top-1/2 -translate-y-1/2 z-20 bg-black/35 hover:bg-black/50 text-white p-3 rounded-full backdrop-blur-sm transition-colors hidden sm:flex items-center justify-center"
                aria-label="Anterior">
            <x-icon name="chevron-left" class="w-5 h-5" />
        </button>
        <button type="button" @click="activeSlide = (activeSlide === slides - 1) ? 0 : activeSlide + 1"
                class="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 z-20 bg-black/35 hover:bg-black/50 text-white p-3 rounded-full backdrop-blur-sm transition-colors hidden sm:flex items-center justify-center"
                aria-label="Seguinte">
            <x-icon name="chevron-right" class="w-5 h-5" />
        </button>
        @endif
    </div>
    @else
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900">
        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-blue-400/20 via-transparent to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center md:text-left">
            <p class="text-xs font-semibold uppercase tracking-widest text-blue-300 mb-3">Blog institucional</p>
            <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight">Juventude Batista Feirense</h1>
            <p class="mt-3 text-lg text-blue-100/95 max-w-2xl mx-auto md:mx-0">{{ \App\Support\SiteBranding::siteTagline() }}</p>
        </div>
    </div>
    @endif

    <!-- Intro -->
    <section class="border-b border-blue-100/80 dark:border-slate-800 bg-white dark:bg-slate-900/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-12 flex flex-col md:flex-row md:items-center md:justify-between gap-8">
            <div class="max-w-2xl">
                <h2 class="text-xl md:text-2xl font-bold text-slate-900 dark:text-white">Comunicação e transparência</h2>
                <p class="mt-2 text-slate-600 dark:text-slate-400 leading-relaxed">
                    Acompanhe notícias, comunicados e iniciativas da diretoria e das igrejas. Conteúdos pensados para fortalecer a comunhão e o testemunho da juventude batista em Feira de Santana.
                </p>
            </div>
            <div class="flex shrink-0 justify-center md:justify-end">
                <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600/10 dark:bg-blue-500/15 ring-1 ring-blue-200/60 dark:ring-blue-500/30">
                    <x-module-icon module="blog" class="h-9 w-9 opacity-95" alt="" />
                </span>
            </div>
        </div>
    </section>

    <!-- Filtros -->
    <div class="sticky top-16 md:top-[4.25rem] z-30 border-b border-slate-200/90 dark:border-slate-800 bg-slate-50/95 dark:bg-slate-950/95 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <form action="{{ route('blog.index') }}" method="GET" class="flex flex-col gap-3 md:flex-row md:items-center md:gap-4 lg:gap-6">
                <div class="flex shrink-0 items-center gap-2 text-slate-700 dark:text-slate-300 md:min-w-[7rem]">
                    <x-icon name="filter" class="h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400" style="duotone" />
                    <span class="text-sm font-semibold uppercase tracking-wide leading-none">Filtrar</span>
                </div>
                {{-- Uma grelha alinha altura e baseline dos controlos em todos os breakpoints --}}
                <div class="grid min-w-0 flex-1 grid-cols-1 gap-3 sm:grid-cols-[minmax(0,1fr)_minmax(11rem,14rem)_auto] sm:items-center">
                    <div class="relative isolate min-w-0">
                        {{-- Wrapper flex + inset-y-0: FA duotone alinha ao centro; top/translate no <i> quebra com ::before --}}
                        <span class="pointer-events-none absolute inset-y-0 left-0 z-10 flex w-10 items-center justify-center text-slate-400">
                            <x-icon name="magnifying-glass" class="block h-4 w-4 shrink-0" />
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Palavra-chave…"
                               autocomplete="off"
                               class="relative z-0 box-border h-11 w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:placeholder:text-slate-500">
                    </div>
                    <div class="relative min-w-0 sm:max-w-none">
                        <select name="category" onchange="this.form.submit()"
                                class="box-border h-11 w-full cursor-pointer appearance-none rounded-xl border border-slate-200 bg-white py-0 pl-3 pr-10 text-sm leading-normal text-slate-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex w-10 items-center justify-center border-l border-transparent text-slate-400">
                            <x-icon name="chevron-down" class="h-4 w-4" />
                        </div>
                    </div>
                    <button type="submit" class="inline-flex h-11 shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/40 sm:w-auto sm:justify-self-end">
                        Aplicar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Grelha de posts + sidebar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div class="grid grid-cols-1 gap-10 xl:grid-cols-12 xl:gap-12">
            <div class="xl:col-span-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($posts as $post)
                        @include('blog::public.partials.post-card', ['post' => $post, 'imageLoading' => 'lazy'])
                    @empty
                        <div class="md:col-span-2 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-8 py-16 text-center">
                            <x-icon name="newspaper" class="mx-auto h-12 w-12 text-slate-400 mb-4" style="duotone" />
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Nenhuma publicação encontrada</h3>
                            <p class="mt-2 text-slate-500 dark:text-slate-400 max-w-md mx-auto text-sm">Experimente outros filtros ou volte mais tarde.</p>
                            <a href="{{ route('blog.index') }}" class="mt-6 inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Ver todas</a>
                        </div>
                    @endforelse
                </div>

                @if($posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
            <aside class="xl:col-span-4 xl:sticky xl:top-28 xl:self-start" aria-label="Destaques e ligações">
                @include('blog::public.partials.blog-public-sidebar')
            </aside>
        </div>
    </div>
</div>
@endsection
