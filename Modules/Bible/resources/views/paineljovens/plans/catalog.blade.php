@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Catálogo de planos')

@section('jovens_content')
    <div class="max-w-6xl mx-auto space-y-12 pb-16 -mt-2">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8">
            <div>
                <a href="{{ route('jovens.bible.plans.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-blue-700 dark:hover:text-blue-400 mb-3 transition-colors">
                    <x-icon name="chevron-left" class="w-4 h-4" />
                    Os meus planos
                </a>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Explorar planos</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400 text-sm md:text-base max-w-xl leading-relaxed">
                    Escolhe um percurso alinhado com o teu ritmo — tudo permanece no painel de jovens.
                </p>
            </div>

            <form action="{{ route('jovens.bible.plans.catalog') }}" method="GET" class="w-full lg:w-72 relative">
                <label class="sr-only" for="catalog-search">Pesquisar planos</label>
                <input id="catalog-search" type="search" name="search" value="{{ request('search') }}" placeholder="Filtrar por título…"
                       class="w-full rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 py-3 pl-11 pr-4 text-sm font-medium text-gray-900 dark:text-gray-100 outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500">
                <x-icon name="magnifying-glass" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" />
            </form>
        </div>

        @if($featuredPlans->isNotEmpty())
            <section>
                <div class="flex items-center gap-2 mb-6">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300">
                        <x-icon name="star" class="w-4 h-4" />
                    </span>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Sugestões</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    @foreach($featuredPlans as $plan)
                        <article class="relative flex flex-col rounded-3xl overflow-hidden border border-gray-800 bg-gray-900 text-white min-h-[22rem] shadow-xl group">
                            @if(isset($plan->cover_image))
                                <img src="{{ Storage::url($plan->cover_image) }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:scale-[1.02] transition-transform duration-700">
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-800 to-gray-950"></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-gray-950/70 to-transparent"></div>
                            <div class="relative mt-auto p-8 flex flex-col gap-3">
                                <span class="self-start px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/10 border border-white/10">
                                    {{ ucfirst($plan->type) }}
                                </span>
                                <h3 class="text-2xl font-bold leading-tight">{{ $plan->title }}</h3>
                                <p class="text-sm text-gray-300 line-clamp-2">{{ $plan->description }}</p>
                                <div class="flex items-center justify-between pt-4">
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <x-icon name="clock" class="w-3.5 h-3.5" />
                                        {{ $plan->duration_days }} dias
                                    </span>
                                    <a href="{{ route('jovens.bible.plans.preview', $plan->id) }}" class="inline-flex items-center gap-2 rounded-xl bg-white text-gray-900 px-4 py-2 text-xs font-bold hover:bg-blue-50 transition-colors">
                                        Ver plano
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section>
            <div class="flex items-center gap-2 mb-6">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                    <x-icon name="layer-group" class="w-4 h-4" />
                </span>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Todos os planos</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($allPlans as $plan)
                    <article class="flex flex-col rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/80 overflow-hidden shadow-sm hover:shadow-md hover:border-blue-300/50 dark:hover:border-blue-800/50 transition-all">
                        @if($plan->cover_image)
                            <div class="h-44 overflow-hidden bg-gray-100 dark:bg-gray-800 relative">
                                <img src="{{ Storage::url($plan->cover_image) }}" alt="" class="w-full h-full object-cover">
                                <span class="absolute top-3 right-3 rounded-lg bg-white/90 dark:bg-gray-900/90 px-2 py-1 text-[10px] font-bold text-gray-700 dark:text-gray-200">
                                    {{ $plan->duration_days }} dias
                                </span>
                            </div>
                            <div class="p-6 flex-1 flex flex-col">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white line-clamp-2">{{ $plan->title }}</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-4 flex-1 leading-relaxed">{{ $plan->description }}</p>
                            </div>
                        @else
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-400">
                                        <x-icon name="book-open" class="w-6 h-6" />
                                    </div>
                                    <span class="text-[10px] font-bold uppercase tracking-wide text-gray-500 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-lg">{{ $plan->duration_days }} dias</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->title }}</h3>
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 line-clamp-5 flex-1 leading-relaxed">{{ $plan->description }}</p>
                            </div>
                        @endif
                        <div class="px-6 pb-6">
                            <a href="{{ route('jovens.bible.plans.preview', $plan->id) }}" class="block w-full text-center rounded-2xl border border-gray-200 dark:border-gray-700 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 hover:bg-blue-600 hover:border-blue-600 hover:text-white transition-colors">
                                Detalhes
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $allPlans->links() }}
            </div>
        </section>
    </div>
@endsection
