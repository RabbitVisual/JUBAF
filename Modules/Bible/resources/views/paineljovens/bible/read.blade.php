@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Leitor da Bíblia')

@section('jovens_content')
    <div class="space-y-8 pb-16 -mt-2">
        {{-- Cabeçalho tipo estante / e-reader --}}
        <header class="relative overflow-hidden rounded-3xl border border-gray-200/90 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-transparent to-blue-500/5 pointer-events-none"></div>
            <div class="relative px-6 py-8 md:px-10 md:py-10 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-8">
                <div class="max-w-2xl">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-blue-700 dark:text-blue-400 mb-3">Bíblia no painel</p>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight leading-tight">
                        Escolhe um livro e continua a tua leitura
                    </h1>
                    <p class="mt-3 text-sm md:text-[15px] text-gray-600 dark:text-gray-400 leading-relaxed">
                        Interface simples, tipografia pensada para ler à vontade. Todas as ligações mantêm-te no Unijovem.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('jovens.bible.search') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 px-4 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-400/60 hover:bg-blue-50/80 dark:hover:bg-blue-950/40 transition-colors">
                        <x-icon name="magnifying-glass" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
                        Buscar
                    </a>
                    <a href="{{ route('jovens.bible.favorites') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/80 px-4 py-2.5 text-xs font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-400/60 hover:bg-blue-50/80 dark:hover:bg-blue-950/40 transition-colors">
                        <x-icon name="star" class="w-4 h-4 text-amber-500" />
                        Favoritos
                    </a>
                    <a href="{{ route('jovens.bible.interlinear') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-xs font-semibold shadow-md shadow-blue-600/20 transition-colors">
                        <x-icon name="layer-group" class="w-4 h-4" />
                        Interlinear
                    </a>
                    @if(Route::has('jovens.bible.plans.index'))
                        <a href="{{ route('jovens.bible.plans.index') }}"
                           class="inline-flex items-center gap-2 rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50/90 dark:bg-blue-950/40 px-4 py-2.5 text-xs font-semibold text-blue-900 dark:text-blue-100 hover:bg-blue-100/90 dark:hover:bg-blue-900/50 transition-colors">
                            <x-icon name="calendar-days" class="w-4 h-4" />
                            Planos
                        </a>
                    @endif
                </div>
            </div>
        </header>

        {{-- Versão --}}
        <section class="jovens-bible-paper rounded-3xl border border-gray-200/80 dark:border-gray-800 p-6 md:p-8 shadow-sm" data-tour="bible-version">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50">Tradução</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Podes mudar a versão em qualquer capítulo; aqui defines o ponto de partida ao escolheres um livro.
                    </p>
                </div>
                <div class="w-full md:max-w-xs">
                    <label for="version-select" class="sr-only">Versão da Bíblia</label>
                    <div class="relative">
                        <select id="version-select"
                            onchange="window.location.href = {{ json_encode(rtrim(route('jovens.bible.read'), '/')) }} + '/' + encodeURIComponent(this.value)"
                            class="w-full appearance-none rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 pl-4 pr-11 py-3.5 text-sm font-semibold text-gray-900 dark:text-gray-100 shadow-inner focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 outline-none cursor-pointer">
                            <option value="">Selecionar…</option>
                            @foreach($versions as $v)
                                <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>
                                    {{ $v->abbreviation }} — {{ $v->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
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
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Antigo Testamento</h3>
                    <span class="ml-auto text-[11px] font-semibold tabular-nums text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-lg">{{ $oldTestament->count() }} livros</span>
                </div>
                <div class="rounded-3xl border border-gray-200/90 dark:border-gray-800 bg-white dark:bg-gray-900/80 p-5 md:p-6 shadow-sm">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($oldTestament as $book)
                            <a href="{{ route('jovens.bible.book', ['version' => $version->abbreviation, 'book' => $book->book_number]) }}"
                               class="group flex flex-col rounded-2xl border border-transparent bg-gray-50/80 dark:bg-gray-800/50 px-2 py-3 text-center transition-colors hover:border-amber-300/80 hover:bg-amber-50/90 dark:hover:bg-amber-950/30"
                               title="{{ $book->name }}">
                                <span class="text-[11px] font-bold text-amber-700/80 dark:text-amber-400/90">{{ $book->abbreviation ?: \Illuminate\Support\Str::limit($book->name, 3, '') }}</span>
                                <span class="mt-1 text-[12px] font-medium text-gray-800 dark:text-gray-200 leading-snug line-clamp-2">{{ $book->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="space-y-4">
                <div class="flex items-center gap-3 px-1">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-[10px] font-black text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">NT</span>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Novo Testamento</h3>
                    <span class="ml-auto text-[11px] font-semibold tabular-nums text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2.5 py-1 rounded-lg">{{ $newTestament->count() }} livros</span>
                </div>
                <div class="rounded-3xl border border-gray-200/90 dark:border-gray-800 bg-white dark:bg-gray-900/80 p-5 md:p-6 shadow-sm">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                        @foreach($newTestament as $book)
                            <a href="{{ route('jovens.bible.book', ['version' => $version->abbreviation, 'book' => $book->book_number]) }}"
                               class="group flex flex-col rounded-2xl border border-transparent bg-gray-50/80 dark:bg-gray-800/50 px-2 py-3 text-center transition-colors hover:border-emerald-300/80 hover:bg-emerald-50/90 dark:hover:bg-emerald-950/30"
                               title="{{ $book->name }}">
                                <span class="text-[11px] font-bold text-emerald-700/80 dark:text-emerald-400/90">{{ $book->abbreviation ?: \Illuminate\Support\Str::limit($book->name, 3, '') }}</span>
                                <span class="mt-1 text-[12px] font-medium text-gray-800 dark:text-gray-200 leading-snug line-clamp-2">{{ $book->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>

        <p class="text-center text-xs text-gray-500 dark:text-gray-500 max-w-xl mx-auto leading-relaxed">
            Dica: usa os planos de leitura para ritmo diário — no menu lateral, em <strong class="text-gray-700 dark:text-gray-300">Bíblia &amp; estudo</strong>.
        </p>
    </div>
@endsection
