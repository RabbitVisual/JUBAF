@extends('painellider::layouts.lideres')

@section('title', 'Eventos')

@section('lideres_content')
<x-ui.lideres::page-shell class="animate-fade-in space-y-8 pb-8 md:space-y-10">
    <x-ui.lideres::hero
        variant="gradient"
        eyebrow="Liderança local · Agenda"
        title="Eventos da JUBAF"
        description="Reuniões, formações e momentos institucionais visíveis para líderes. Inscreve-te quando as inscrições estiverem abertas e partilha a informação com a tua igreja.">
        <x-slot name="actions">
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:items-center">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-lg ring-1 ring-white/25">
                    <x-module-icon module="Calendario" class="h-8 w-8 text-white" style="duotone" />
                </span>
                @if(!empty($hasPublicCalendar))
                    <a href="{{ route('eventos.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-7 py-4 text-sm font-bold text-emerald-800 shadow-lg transition-all hover:bg-emerald-50 active:scale-[0.98]">
                        <x-icon name="globe" class="h-4 w-4" style="duotone" />
                        Agenda pública
                    </a>
                @endif
            </div>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="flex flex-col gap-4 rounded-2xl border border-teal-200/80 bg-teal-50/80 px-6 py-5 dark:border-teal-900/40 dark:bg-teal-950/25 sm:flex-row sm:items-start">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-teal-500/20 text-teal-800 dark:text-teal-300">
            <x-icon name="circle-info" style="duotone" class="h-6 w-6" />
        </div>
        <div class="min-w-0">
            <h2 class="text-base font-bold text-teal-900 dark:text-teal-100">Visibilidade</h2>
            <p class="mt-1.5 text-sm leading-relaxed text-teal-900/85 dark:text-teal-100/85">
                Vês eventos marcados para <strong class="text-teal-950 dark:text-teal-50">líderes</strong> e os partilhados com toda a diretoria. Se o evento for por igreja, só aparece se a tua conta estiver vinculada à congregação correcta.
            </p>
        </div>
    </div>

    <div>
        <h2 class="sr-only">Lista de eventos</h2>
        <ul class="space-y-4">
            @forelse($events as $event)
                <li class="group overflow-hidden rounded-2xl border border-emerald-200/70 bg-white shadow-sm transition-all duration-200 hover:border-teal-300/80 hover:shadow-lg hover:shadow-emerald-900/10 dark:border-emerald-900/35 dark:bg-slate-800/80 dark:hover:border-teal-800/40">
                    <div class="flex flex-col gap-5 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex min-w-0 gap-4">
                            <div class="hidden h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-600/30 sm:flex">
                                <x-icon name="calendar-days" class="h-7 w-7" style="duotone" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wide text-teal-700 dark:text-teal-400">{{ $event->starts_at->format('d/m/Y · H:i') }}</p>
                                <h3 class="mt-1 text-lg font-bold text-gray-900 transition-colors group-hover:text-emerald-800 dark:text-white dark:group-hover:text-emerald-200">{{ $event->title }}</h3>
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
                                        <span class="inline-flex rounded-full bg-teal-100 px-3 py-1 text-xs font-semibold capitalize text-teal-900 dark:bg-teal-900/40 dark:text-teal-100">{{ $event->type }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route($routePrefix.'.show', $event) }}" class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/30 transition hover:brightness-110 active:scale-[0.98] sm:w-auto">
                            Ver detalhes
                            <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                        </a>
                    </div>
                </li>
            @empty
                <li class="rounded-[2rem] border border-dashed border-emerald-300/80 bg-gradient-to-b from-emerald-50/50 to-white px-8 py-16 text-center dark:border-emerald-800/50 dark:from-emerald-950/20 dark:to-slate-900/40">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                        <x-icon name="calendar-days" class="h-8 w-8" style="duotone" />
                    </div>
                    <p class="mt-6 text-lg font-bold text-gray-900 dark:text-white">Sem eventos no momento</p>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-600 dark:text-gray-400">Quando a diretoria agendar encontros para líderes, eles surgem nesta lista.</p>
                    @if(!empty($hasPublicCalendar))
                        <a href="{{ route('eventos.index') }}" class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-600/25 transition-colors hover:bg-emerald-700">
                            <x-icon name="globe" class="h-4 w-4" style="duotone" />
                            Ver eventos públicos
                        </a>
                    @endif
                </li>
            @endforelse
        </ul>
    </div>
</x-ui.lideres::page-shell>
@endsection
