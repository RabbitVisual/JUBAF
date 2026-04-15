@extends('bible::components.layouts.panel')

@include('bible::paineljovens.partials.jovens-bible-styles')

@section('title', $plan->title)

@section('content')
<div class="max-w-5xl mx-auto pb-20 -mt-2">
    <a href="{{ route('jovens.bible.plans.catalog') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-stone-500 hover:text-teal-700 dark:hover:text-teal-400 mb-8 transition-colors">
        <x-icon name="chevron-left" class="w-4 h-4" />
        Voltar ao catálogo
    </a>

    <header class="relative rounded-[2rem] overflow-hidden border border-stone-200 dark:border-stone-800 shadow-xl mb-10">
        @if($plan->cover_image)
            <img src="{{ Storage::url($plan->cover_image) }}" alt="" class="w-full h-72 md:h-80 object-cover">
        @else
            <div class="w-full h-72 md:h-80 bg-gradient-to-br from-teal-800 to-stone-900"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/50 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-10">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/15 text-white border border-white/10">{{ ucfirst($plan->type) }}</span>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold text-teal-100 border border-teal-400/30 flex items-center gap-1">
                    <x-icon name="clock" class="w-3 h-3" /> {{ $plan->duration_days }} dias
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight max-w-3xl">{{ $plan->title }}</h1>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-10">
            <section>
                <h2 class="text-xl font-bold text-stone-900 dark:text-white mb-4">Sobre este plano</h2>
                <div class="prose dark:prose-invert max-w-none text-stone-600 dark:text-stone-300 leading-relaxed text-base">
                    {!! nl2br(e($plan->description)) !!}
                </div>
            </section>

            <section>
                <h2 class="text-lg font-bold text-stone-900 dark:text-white mb-6 flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-teal-50 dark:bg-teal-950/40 text-teal-700 dark:text-teal-400">
                        <x-icon name="calendar-days" class="w-4 h-4" />
                    </span>
                    Pré-visualização do calendário
                </h2>

                <div class="space-y-3">
                    @forelse($sampleDays as $day)
                        <div class="flex gap-4 rounded-2xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900/80 p-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-stone-100 dark:bg-stone-800 font-bold text-sm text-stone-500">
                                {{ $day->day_number }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-stone-900 dark:text-white">Dia {{ $day->day_number }}</h4>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach($day->contents as $content)
                                        @if($content->type === 'scripture')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-teal-50 text-teal-900 dark:bg-teal-950/50 dark:text-teal-200">
                                                {{ $content->book->name }} {{ $content->chapter_start }}
                                                @if($content->chapter_end > $content->chapter_start)-{{ $content->chapter_end }}@endif
                                            </span>
                                        @elseif($content->type === 'devotional')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-violet-50 text-violet-900 dark:bg-violet-950/50 dark:text-violet-200">
                                                Devocional
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-stone-500 italic">Calendário ainda não disponível.</p>
                    @endforelse

                    @if($plan->days_count > 5)
                        <p class="text-sm text-stone-500 text-center pt-2">+ {{ $plan->days_count - 5 }} dias no plano completo</p>
                    @endif
                </div>
            </section>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky top-4 rounded-3xl border border-stone-200 dark:border-stone-800 bg-white dark:bg-stone-900/80 p-8 shadow-lg text-center space-y-5">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-teal-50 dark:bg-teal-950/50 text-teal-700 dark:text-teal-400">
                    <x-icon name="circle-check" class="w-7 h-7" />
                </div>
                <div>
                    <h3 class="text-xl font-bold text-stone-900 dark:text-white">Inscrever-me</h3>
                    <p class="text-sm text-stone-600 dark:text-stone-400 mt-2 leading-relaxed">
                        O progresso fica guardado no teu painel. Podes voltar a qualquer momento.
                    </p>
                </div>

                <form action="{{ route('jovens.bible.plans.subscribe', $plan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 rounded-2xl bg-teal-600 hover:bg-teal-700 text-white font-bold text-base shadow-lg shadow-teal-600/20 transition-colors flex items-center justify-center gap-2">
                        Começar este plano
                        <x-icon name="arrow-right" class="w-5 h-5" />
                    </button>
                </form>

                <p class="text-[11px] text-stone-500 dark:text-stone-500 leading-relaxed">
                    Ao inscreveres, aceitas acompanhar o plano no painel Unijovem (sem redirecionar para o site público).
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
