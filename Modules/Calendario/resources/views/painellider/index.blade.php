@extends('painellider::components.layouts.app')

@section('title', 'Eventos')

@section('content')
<div class="space-y-8 md:space-y-10 animate-fade-in pb-8">
    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 dark:border-slate-800 bg-gradient-to-br from-emerald-600 via-teal-600 to-slate-900 text-white shadow-2xl shadow-emerald-900/20">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.15\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative px-8 py-10 md:px-12 md:py-11 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div class="max-w-2xl min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100/95 mb-3">Liderança local · Agenda</p>
                <h1 class="flex flex-wrap items-center gap-3 text-3xl md:text-4xl font-bold tracking-tight leading-tight">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/25 shadow-lg">
                        <x-module-icon module="Calendario" class="h-8 w-8 text-white" style="duotone" />
                    </span>
                    Eventos da JUBAF
                </h1>
                <p class="mt-4 text-sm md:text-base text-emerald-50/90 font-medium leading-relaxed">
                    Reuniões, formações e momentos institucionais visíveis para líderes. Inscreve-te quando as inscrições estiverem abertas e partilha a informação com a tua igreja.
                </p>
            </div>
            @if(!empty($hasPublicCalendar))
                <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                    <a href="{{ route('eventos.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl bg-white text-emerald-800 text-sm font-bold shadow-lg hover:bg-emerald-50 transition-all active:scale-[0.98]">
                        <x-icon name="globe" class="w-4 h-4" style="duotone" />
                        Agenda pública
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="rounded-2xl border border-teal-200/80 dark:border-teal-900/40 bg-teal-50/80 dark:bg-teal-950/25 px-6 py-5 flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-teal-500/20 flex items-center justify-center text-teal-800 dark:text-teal-300 shrink-0">
            <x-icon name="circle-info" style="duotone" class="w-6 h-6" />
        </div>
        <div class="min-w-0">
            <h2 class="text-base font-bold text-teal-900 dark:text-teal-100">Visibilidade</h2>
            <p class="text-sm text-teal-900/85 dark:text-teal-100/85 mt-1.5 leading-relaxed">
                Vês eventos marcados para <strong class="text-teal-950 dark:text-teal-50">líderes</strong> e os partilhados com toda a diretoria. Se o evento for por igreja, só aparece se a tua conta estiver vinculada à congregação correcta.
            </p>
        </div>
    </div>

    <div>
        <h2 class="sr-only">Lista de eventos</h2>
        <ul class="space-y-4">
            @forelse($events as $event)
                <li class="group overflow-hidden rounded-2xl border border-emerald-200/70 dark:border-emerald-900/35 bg-white dark:bg-slate-800/80 shadow-sm transition-all duration-200 hover:border-teal-300/80 hover:shadow-lg hover:shadow-emerald-900/10 dark:hover:border-teal-800/40">
                    <div class="flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0 flex gap-4">
                            <div class="hidden sm:flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-600/30">
                                <x-icon name="calendar-days" class="h-7 w-7" style="duotone" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wide text-teal-700 dark:text-teal-400">{{ $event->starts_at->format('d/m/Y · H:i') }}</p>
                                <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white group-hover:text-emerald-800 dark:group-hover:text-emerald-200 transition-colors">{{ $event->title }}</h3>
                                @if($event->location)
                                    <p class="mt-2 flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        <x-icon name="location-dot" class="h-4 w-4 shrink-0 text-emerald-600 dark:text-emerald-400" style="duotone" />
                                        {{ $event->location }}
                                    </p>
                                @endif
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if($event->registration_open)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-900 dark:bg-emerald-900/50 dark:text-emerald-100">
                                            <x-icon name="circle-check" class="h-3.5 w-3.5" style="solid" />
                                            Inscrições abertas
                                        </span>
                                    @endif
                                    @if($event->type)
                                        <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold text-teal-900 dark:bg-teal-900/40 dark:text-teal-100 capitalize">{{ $event->type }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route($routePrefix.'.show', $event) }}" class="inline-flex w-full sm:w-auto shrink-0 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition hover:brightness-110 active:scale-[0.98]">
                            Ver detalhes
                            <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                        </a>
                    </div>
                </li>
            @empty
                <li class="rounded-[2rem] border border-dashed border-emerald-300/80 bg-gradient-to-b from-emerald-50/50 to-white dark:from-emerald-950/20 dark:to-slate-900/40 dark:border-emerald-800/50 px-8 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300">
                        <x-icon name="calendar-days" class="h-8 w-8" style="duotone" />
                    </div>
                    <p class="mt-6 text-lg font-bold text-gray-900 dark:text-white">Sem eventos no momento</p>
                    <p class="mt-2 mx-auto max-w-md text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Quando a diretoria agendar encontros para líderes, eles surgem nesta lista.</p>
                    @if(!empty($hasPublicCalendar))
                        <a href="{{ route('eventos.index') }}" class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-colors">
                            <x-icon name="globe" class="h-4 w-4" style="duotone" />
                            Ver eventos públicos
                        </a>
                    @endif
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
