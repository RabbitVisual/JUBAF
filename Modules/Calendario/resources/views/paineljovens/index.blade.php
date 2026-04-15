@extends('layouts.app')

@section('title', 'Eventos')

@section('content')
<div class="space-y-8 md:space-y-10 animate-fade-in pb-8">
    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 dark:border-slate-800 bg-gradient-to-br from-violet-600 via-fuchsia-600 to-slate-900 text-white shadow-2xl shadow-violet-900/20">
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.15\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative px-8 py-10 md:px-12 md:py-11 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
            <div class="max-w-2xl min-w-0">
                <p class="text-xs font-semibold uppercase tracking-wider text-violet-100/95 mb-3">Unijovem · Agenda</p>
                <h1 class="flex flex-wrap items-center gap-3 text-3xl md:text-4xl font-bold tracking-tight leading-tight">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/25 shadow-lg">
                        <x-module-icon module="Calendario" class="h-8 w-8 text-white" style="duotone" />
                    </span>
                    Os teus eventos
                </h1>
                <p class="mt-4 text-sm md:text-base text-violet-50/90 font-medium leading-relaxed">
                    Encontros da JUBAF visíveis para a tua idade e igreja. Inscreve-te quando a diretoria abrir inscrições — também podes consultar a agenda pública no site.
                </p>
            </div>
            @if(!empty($hasPublicCalendar))
                <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                    <a href="{{ route('eventos.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-7 py-4 rounded-2xl bg-white text-violet-800 text-sm font-bold shadow-lg hover:bg-violet-50 transition-all active:scale-[0.98]">
                        <x-icon name="globe" class="w-4 h-4" style="duotone" />
                        Agenda pública
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="rounded-2xl border border-violet-200/80 dark:border-violet-900/40 bg-violet-50/90 dark:bg-violet-950/30 px-6 py-5 flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="w-12 h-12 rounded-2xl bg-violet-500/20 flex items-center justify-center text-violet-700 dark:text-violet-300 shrink-0">
            <x-icon name="circle-info" style="duotone" class="w-6 h-6" />
        </div>
        <div class="min-w-0">
            <h2 class="text-base font-bold text-violet-900 dark:text-violet-200">Como funciona</h2>
            <p class="text-sm text-violet-900/85 dark:text-violet-100/85 mt-1.5 leading-relaxed">
                Só vês eventos para o teu perfil e, quando aplicável, da tua igreja local. Eventos <strong class="text-violet-950 dark:text-violet-100">públicos</strong> estão também no site — usa o botão acima.
            </p>
        </div>
    </div>

    <div>
        <h2 class="sr-only">Lista de eventos</h2>
        <ul class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($events as $event)
                <li class="group overflow-hidden rounded-2xl border border-violet-200/70 dark:border-violet-900/35 bg-white dark:bg-slate-800/80 shadow-sm transition-all duration-200 hover:border-fuchsia-300/80 hover:shadow-lg hover:shadow-violet-900/10 dark:hover:border-fuchsia-800/40">
                    <div class="aspect-[16/9] w-full overflow-hidden bg-slate-100 dark:bg-slate-700">
                        @if($event->cover_path)
                            <img src="{{ asset('storage/'.$event->cover_path) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-violet-500/70 dark:text-violet-300/70">
                                <x-icon name="calendar-days" class="h-10 w-10" style="duotone" />
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-5 p-6">
                        <div class="min-w-0 flex gap-4">
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wide text-fuchsia-700 dark:text-fuchsia-400">{{ optional($event->start_date)->format('d/m/Y · H:i') }}</p>
                                <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-white group-hover:text-violet-800 dark:group-hover:text-violet-200 transition-colors">{{ $event->title }}</h3>
                                @if($event->location)
                                    <p class="mt-2 flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        <x-icon name="location-dot" class="h-4 w-4 shrink-0 text-violet-500 dark:text-violet-400" style="duotone" />
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
                                        <span class="inline-flex rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-900 dark:bg-violet-900/40 dark:text-violet-100 capitalize">{{ $event->type }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route($routePrefix.'.show', $event) }}" class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-violet-600 to-fuchsia-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-violet-600/30 transition hover:brightness-110 active:scale-[0.98]">
                            Inscrever-se
                            <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                        </a>
                    </div>
                </li>
            @empty
                <li class="rounded-[2rem] border border-dashed border-violet-300/80 bg-gradient-to-b from-violet-50/50 to-white dark:from-violet-950/20 dark:to-slate-900/40 dark:border-violet-800/50 px-8 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-100 dark:bg-violet-900/40 text-violet-600 dark:text-violet-300">
                        <x-icon name="calendar-days" class="h-8 w-8" style="duotone" />
                    </div>
                    <p class="mt-6 text-lg font-bold text-gray-900 dark:text-white">Ainda sem eventos para ti</p>
                    <p class="mt-2 mx-auto max-w-md text-sm text-gray-600 dark:text-gray-400 leading-relaxed">Quando a diretoria publicar encontros para jovens da tua realidade, eles aparecem aqui.</p>
                    @if(!empty($hasPublicCalendar))
                        <a href="{{ route('eventos.index') }}" class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-violet-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-violet-600/25 hover:bg-violet-700 transition-colors">
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
