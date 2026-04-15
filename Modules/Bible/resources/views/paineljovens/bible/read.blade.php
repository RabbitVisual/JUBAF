@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Leitor da Bíblia')

@section('content')
    <div class="space-y-8 pb-16 -mt-2">
        {{-- Cabeçalho tipo estante / e-reader --}}
        <header class="relative overflow-hidden rounded-3xl border border-stone-200/90 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-600/10 via-transparent to-violet-500/5 pointer-events-none"></div>
            <div class="relative px-6 py-8 md:px-10 md:py-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                <div class="max-w-2xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-teal-700 dark:text-teal-400 mb-3">Bíblia no painel</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-stone-900 dark:text-stone-100 tracking-tight leading-tight">
                        Escolhe um livro e continua a tua leitura
                    </h1>
                    <p class="mt-3 text-sm md:text-[15px] text-stone-600 dark:text-stone-400 leading-relaxed">
                        Interface simples, tipografia pensada para ler à vontade. Todas as ligações mantêm-te no Unijovem.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('jovens.bible.search') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/80 px-4 py-2.5 text-xs font-semibold text-stone-700 dark:text-stone-200 hover:border-teal-400/60 hover:bg-teal-50/80 dark:hover:bg-teal-950/40 transition-colors">
                        <x-icon name="magnifying-glass" class="w-4 h-4 text-teal-600 dark:text-teal-400" />
                        Buscar
                    </a>
                    <a href="{{ route('jovens.bible.favorites') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/80 px-4 py-2.5 text-xs font-semibold text-stone-700 dark:text-stone-200 hover:border-teal-400/60 hover:bg-teal-50/80 dark:hover:bg-teal-950/40 transition-colors">
                        <x-icon name="star" class="w-4 h-4 text-amber-500" />
                        Favoritos
                    </a>
                    <a href="{{ route('jovens.bible.interlinear') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white px-4 py-2.5 text-xs font-semibold shadow-md shadow-teal-600/20 transition-colors">
                        <x-icon name="layer-group" class="w-4 h-4" />
                        Interlinear
                    </a>
                    @if(Route::has('jovens.bible.plans.index'))
                        <a href="{{ route('jovens.bible.plans.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-teal-200 dark:border-teal-800 bg-teal-50/90 dark:bg-teal-950/40 px-4 py-2.5 text-xs font-semibold text-teal-900 dark:text-teal-100 hover:bg-teal-100/90 dark:hover:bg-teal-900/50 transition-colors">
                            <x-icon name="calendar-days" class="w-4 h-4" />
                            Planos
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- Versão --}}
        <section class="jovens-bible-paper rounded-3xl border border-stone-200/80 dark:border-stone-800 p-6 md:p-8 shadow-sm" data-tour="bible-version">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-stone-900 dark:text-stone-50">Tradução</h2>
                    <p class="text-sm text-stone-600 dark:text-stone-400 mt-1">
                        Podes mudar a versão em qualquer capítulo; aqui defines o ponto de partida ao escolheres um livro.
                    </p>
                </div>
                <div class="w-full md:max-w-xs">
                    <label for="version-select" class="sr-only">Versão da Bíblia</label>
                    <div class="relative">
                        <select id="version-select"
                            onchange="window.location.href = {{ json_encode(rtrim(route('jovens.bible.read'), '/')) }} + '/' + encodeURIComponent(this.value)"
                            class="w-full appearance-none rounded-2xl border border-stone-200 dark:border-stone-700 bg-white dark:bg-stone-900 pl-4 pr-11 py-3.5 text-sm font-semibold text-stone-900 dark:text-stone-100 shadow-inner focus:ring-2 focus:ring-teal-500/40 focus:border-teal-500 outline-none cursor-pointer">
                            <option value="">Selecionar…</option>
                            @foreach($versions as $v)
                                <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>
                                    {{ $v->abbreviation }} — {{ $v->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-stone-400">
                            <x-icon name="chevron-down" class="w-4 h-4" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Livros --}}
        <div class="grid lg:grid-cols-2 gap-8 lg:gap-10" data-tour="bible-book">
            <section class="space-y-4">
                <div class="flex items-center gap-3 px-1">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-[10px] font-black text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">AT</span>
                    <h3 class="text-base font-bold text-stone-900 dark:text-stone-100">Antigo Testamento</h3>
                    <span class="ml-auto text-[11px] font-semibold tabular-nums text-stone-500 dark:text-stone-400 bg-stone-100 dark:bg-stone-800 px-2.5 py-1 rounded-lg">{{ $oldTestament->count() }} livros</span>
                </div>
                <div class="rounded-3xl border border-stone-200/90 dark:border-stone-800 bg-white dark:bg-stone-900/80 p-5 md:p-6 shadow-sm">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($oldTestament as $book)
                            <a href="{{ route('jovens.bible.book', ['version' => $version->abbreviation, 'book' => $book->book_number]) }}"
                               class="group flex flex-col rounded-2xl border border-transparent bg-stone-50/80 dark:bg-stone-800/50 px-2 py-3 text-center transition-colors hover:border-amber-300/80 hover:bg-amber-50/90 dark:hover:bg-amber-950/30"
                               title="{{ $book->name }}">
                                <span class="text-[11px] font-bold text-amber-700/80 dark:text-amber-400/90">{{ $book->abbreviation ?: \Illuminate\Support\Str::limit($book->name, 3, '') }}</span>
                                <span class="mt-1 text-[12px] font-medium text-stone-800 dark:text-stone-200 leading-snug line-clamp-2">{{ $book->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <div class="flex items-center gap-3 px-1">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-[10px] font-black text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">NT</span>
                    <h3 class="text-base font-bold text-stone-900 dark:text-stone-100">Novo Testamento</h3>
                    <span class="ml-auto text-[11px] font-semibold tabular-nums text-stone-500 dark:text-stone-400 bg-stone-100 dark:bg-stone-800 px-2.5 py-1 rounded-lg">{{ $newTestament->count() }} livros</span>
                </div>
                <div class="rounded-3xl border border-stone-200/90 dark:border-stone-800 bg-white dark:bg-stone-900/80 p-5 md:p-6 shadow-sm">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($newTestament as $book)
                            <a href="{{ route('jovens.bible.book', ['version' => $version->abbreviation, 'book' => $book->book_number]) }}"
                               class="group flex flex-col rounded-2xl border border-transparent bg-stone-50/80 dark:bg-stone-800/50 px-2 py-3 text-center transition-colors hover:border-emerald-300/80 hover:bg-emerald-50/90 dark:hover:bg-emerald-950/30"
                               title="{{ $book->name }}">
                                <span class="text-[11px] font-bold text-emerald-700/80 dark:text-emerald-400/90">{{ $book->abbreviation ?: \Illuminate\Support\Str::limit($book->name, 3, '') }}</span>
                                <span class="mt-1 text-[12px] font-medium text-stone-800 dark:text-stone-200 leading-snug line-clamp-2">{{ $book->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <p class="text-center text-xs text-stone-500 dark:text-stone-500 max-w-xl mx-auto leading-relaxed">
            Dica: usa os planos de leitura para ritmo diário — no menu lateral, em <strong class="text-stone-700 dark:text-stone-300">Bíblia &amp; estudo</strong>.
        </p>
    </div>
@endsection
