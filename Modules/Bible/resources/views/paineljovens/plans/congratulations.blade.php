@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', 'Leitura concluída')

@section('content')
<div class="flex min-h-[70vh] items-center justify-center py-8 -mt-2">
    <div class="w-full max-w-lg mx-4">
        <div class="rounded-[2rem] border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900 shadow-2xl overflow-hidden text-center relative">
            <div class="h-1.5 w-full bg-gradient-to-r from-teal-500 via-teal-400 to-stone-400"></div>
            <div class="absolute inset-0 pointer-events-none opacity-[0.06] bg-[radial-gradient(circle_at_30%_0%,teal,transparent_50%)]"></div>

            <div class="relative px-8 py-12 md:px-12 md:py-14">
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 shadow-inner">
                    <x-icon name="circle-check" class="w-10 h-10" />
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-stone-900 dark:text-white tracking-tight">Muito bem!</h1>
                <p class="mt-2 text-sm font-medium text-stone-500 dark:text-stone-400">
                    Dia {{ $day->day_number }} · {{ $subscription->plan->title }}
                </p>

                <div class="mt-8 rounded-2xl border border-teal-200/60 dark:border-teal-900/50 bg-teal-50/50 dark:bg-teal-950/20 px-6 py-6 text-left">
                    <x-icon name="quote-left" class="w-6 h-6 text-teal-600/70 mb-3" />
                    <p class="jovens-bible-serif text-stone-800 dark:text-stone-200 text-base leading-relaxed italic">
                        Lâmpada para os meus pés é a tua palavra, e luz para o meu caminho.
                    </p>
                    <p class="text-[11px] font-bold uppercase tracking-widest text-teal-700 dark:text-teal-500 mt-4">Salmos 119:105</p>
                </div>

                <div class="mt-10 space-y-3">
                    @if($nextDay)
                        <a href="{{ route('jovens.bible.reader', ['subscriptionId' => $subscription->id, 'day' => $nextDay]) }}"
                           class="flex w-full items-center justify-center gap-2 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white px-6 py-4 text-sm font-bold shadow-lg shadow-teal-600/20 transition-colors">
                            Próximo dia
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </a>
                    @else
                        <div class="rounded-2xl border border-teal-200 dark:border-teal-900/50 bg-teal-50/80 dark:bg-teal-950/30 px-4 py-3 text-sm font-semibold text-teal-900 dark:text-teal-100">
                            Concluíste este plano. Orgulho — continua a explorar outros percursos no catálogo.
                        </div>
                    @endif

                    <a href="{{ route('jovens.bible.plans.index') }}"
                       class="flex w-full items-center justify-center rounded-2xl border border-stone-200 dark:border-stone-700 bg-stone-50 dark:bg-stone-800/50 px-6 py-3.5 text-sm font-semibold text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-stone-800 transition-colors">
                        Voltar aos meus planos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
