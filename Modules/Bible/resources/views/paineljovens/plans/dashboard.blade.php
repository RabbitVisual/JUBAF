@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Planos de leitura')

@push('styles')
<style>[x-cloak]{display:none!important}</style>
@endpush

@section('jovens_content')
    <div class="max-w-6xl mx-auto space-y-10 pb-12 -mt-2">
        <header class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 text-white shadow-xl">
            <div class="absolute inset-0 opacity-[0.12] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative px-6 py-10 md:px-10 md:py-12 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="max-w-xl">
                    <p class="text-blue-200/90 text-xs font-bold uppercase tracking-widest mb-3">Leitura guiada</p>
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight leading-tight">A tua jornada pelas Escrituras</h1>
                    <p class="mt-4 text-sm md:text-base text-blue-100/95 leading-relaxed">
                        Planos organizados como um diário: um passo de cada vez, sempre dentro do painel Unijovem.
                    </p>
                </div>
                <div class="rounded-2xl border border-white/15 bg-white/10 backdrop-blur-md p-6 w-full lg:max-w-sm">
                    <p class="text-blue-100 text-sm font-medium">Queres um plano novo?</p>
                    <a href="{{ route('jovens.bible.plans.catalog') }}"
                       class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-white text-blue-900 px-4 py-3 text-sm font-bold shadow-lg hover:bg-blue-50 transition-colors">
                        <x-icon name="book" class="w-4 h-4" />
                        Abrir catálogo
                    </a>
                </div>
            </div>
        </header>

        <section>
            <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-6">
                <span class="h-8 w-1 rounded-full bg-blue-500"></span>
                Planos em curso
            </h2>

            @if($subscriptions->isEmpty())
                <div class="jovens-bible-paper rounded-3xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 md:p-16 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                        <x-icon name="book-open" class="w-8 h-8" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Começa quando quiseres</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                        Escolhe um plano no catálogo e acompanha o progresso dia a dia — ideal para criar hábito de leitura.
                    </p>
                    <a href="{{ route('jovens.bible.plans.catalog') }}"
                       class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 text-sm font-semibold shadow-md shadow-blue-600/20 transition-colors">
                        Explorar planos
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($subscriptions as $sub)
                        <article class="flex flex-col rounded-3xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900/80 shadow-sm overflow-hidden h-full">
                            <div class="h-28 bg-gradient-to-r from-blue-600 to-blue-800 relative">
                                <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_80%_20%,white,transparent_55%)]"></div>
                                <div class="relative p-6">
                                    <h3 class="text-lg font-bold text-white leading-snug line-clamp-2 drop-shadow-sm">
                                        {{ $sub->plan->title }}
                                    </h3>
                                </div>
                            </div>

                            <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800" role="progressbar" aria-valuenow="{{ $sub->percent }}" aria-valuemin="0" aria-valuemax="100">
                                <div class="h-full bg-blue-500 rounded-r transition-all duration-500" style="width: {{ $sub->percent }}%"></div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between gap-4 mb-4">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Progresso</p>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white tabular-nums">{{ $sub->percent }}%</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Dia</p>
                                        <p class="text-lg font-bold text-blue-700 dark:text-blue-400 tabular-nums">
                                            {{ $sub->current_day_number }} <span class="text-gray-400 font-normal">/ {{ $sub->total_days ?? $sub->plan->duration_days }}</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-6 flex-1">
                                    {{ $sub->plan->description ?? 'Continua a leitura diária para aprofundar a Palavra.' }}
                                </p>

                                <div class="space-y-2 mt-auto">
                                    <a href="{{ route('jovens.bible.reader', ['subscriptionId' => $sub->id, 'day' => $sub->current_day_number]) }}"
                                       class="flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white py-3 text-sm font-bold transition-colors">
                                        Continuar leitura
                                        <x-icon name="arrow-right" class="w-4 h-4" />
                                    </a>
                                    @if(!empty($sub->offer_recalculate))
                                        <div x-data="{ open: false }" class="w-full">
                                            <button type="button" @click="open = true"
                                                class="w-full py-2.5 text-sm font-semibold text-amber-800 dark:text-amber-300 border border-amber-300/60 dark:border-amber-700/60 rounded-2xl hover:bg-amber-50 dark:hover:bg-amber-950/30 transition-colors flex items-center justify-center gap-2">
                                                <x-icon name="arrows-rotate" class="w-4 h-4" />
                                                Recalcular dias
                                            </button>
                                            <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
                                                <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-md w-full p-6 border border-gray-200 dark:border-gray-800" @click.outside="open = false">
                                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Recalcular rotas</h4>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                                                        A leitura em falta será repartida até à data final do plano, mantendo o que já leste.
                                                    </p>
                                                    <div class="flex gap-3">
                                                        <button type="button" @click="open = false" class="flex-1 py-2.5 font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl">
                                                            Cancelar
                                                        </button>
                                                        <form method="POST" action="{{ route('jovens.bible.plans.recalculate', $sub->id) }}" class="flex-1">
                                                            @csrf
                                                            <button type="submit" class="w-full py-2.5 font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition-colors">
                                                                Confirmar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
