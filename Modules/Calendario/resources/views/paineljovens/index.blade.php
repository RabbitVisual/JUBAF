@extends('paineljovens::layouts.jovens')

@section('title', 'Eventos')


@section('jovens_content')
<x-ui.jovens::page-shell class="space-y-8 md:space-y-10">
    <header class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 text-white shadow-xl">
        <div class="absolute inset-0 pointer-events-none opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        <div class="relative flex flex-col gap-8 px-6 py-10 md:px-10 md:py-12 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl min-w-0">
                <p class="mb-3 text-xs font-bold uppercase tracking-widest text-blue-200/90">Unijovem · Agenda</p>
                <h1 class="flex flex-wrap items-center gap-3 text-3xl font-bold leading-tight tracking-tight md:text-4xl">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-lg ring-1 ring-white/25">
                        <x-module-icon module="Calendario" class="h-8 w-8 text-white" style="duotone" />
                    </span>
                    Os teus eventos
                </h1>
                <p class="mt-4 text-sm font-medium leading-relaxed text-blue-100/95 md:text-base">
                    Encontros da JUBAF visíveis para a tua idade e igreja. Inscreve-te quando a diretoria abrir inscrições — também podes consultar a agenda pública no site.
                </p>
            </div>
            @if(!empty($hasPublicCalendar))
                <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                    <a href="{{ route('eventos.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-7 py-4 text-sm font-bold text-blue-900 shadow-lg transition-all hover:bg-blue-50 active:scale-[0.98]">
                        <x-icon name="globe" class="h-4 w-4" style="duotone" />
                        Agenda pública
                    </a>
                </div>
            @endif
        </div>
    </header>

    <div class="flex flex-col gap-4 rounded-2xl border border-blue-200/80 bg-blue-50/90 px-6 py-5 dark:border-blue-900/40 dark:bg-blue-950/30 sm:flex-row sm:items-start">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-500/20 text-blue-700 dark:text-blue-300">
            <x-icon name="circle-info" style="duotone" class="h-6 w-6" />
        </div>
        <div class="min-w-0">
            <h2 class="text-base font-bold text-blue-900 dark:text-blue-200">Como funciona</h2>
            <p class="mt-1.5 text-sm leading-relaxed text-blue-900/85 dark:text-blue-100/85">
                Só vês eventos para o teu perfil e, quando aplicável, da tua igreja local. Eventos <strong class="text-blue-950 dark:text-blue-100">públicos</strong> estão também no site — usa o botão acima.
            </p>
        </div>
    </div>

    <div>
        <h2 class="sr-only">Lista de eventos</h2>
        <ul class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            @forelse($events as $event)
                <li class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-200 hover:border-blue-300/80 hover:shadow-md hover:shadow-blue-900/5 dark:border-gray-800 dark:bg-gray-900/80 dark:hover:border-blue-800/40">
                    <div class="aspect-[16/9] w-full overflow-hidden bg-gray-100 dark:bg-gray-800">
                        @if($event->cover_path)
                            <img src="{{ asset('storage/'.$event->cover_path) }}" alt="{{ $event->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-blue-500/70 dark:text-blue-400/70">
                                <x-icon name="calendar-days" class="h-10 w-10" style="duotone" />
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col gap-5 p-6">
                        <div class="min-w-0 flex gap-4">
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wide text-blue-700 dark:text-blue-400">{{ optional($event->start_date)->format('d/m/Y · H:i') }}</p>
                                <h3 class="mt-1 text-lg font-bold text-gray-900 transition-colors group-hover:text-blue-800 dark:text-white dark:group-hover:text-blue-300">{{ $event->title }}</h3>
                                @if($event->location)
                                    <p class="mt-2 flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        <x-icon name="location-dot" class="h-4 w-4 shrink-0 text-blue-500 dark:text-blue-400" style="duotone" />
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
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold capitalize text-blue-900 dark:bg-blue-900/40 dark:text-blue-100">{{ $event->type }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route($routePrefix.'.show', $event) }}" class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-2xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-blue-600/25 transition hover:bg-blue-700 active:scale-[0.98]">
                            Inscrever-se
                            <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                        </a>
                    </div>
                </li>
            @empty
                <li class="rounded-[2rem] border border-dashed border-gray-300 bg-gradient-to-b from-gray-50/80 to-white px-8 py-16 text-center dark:border-gray-700 dark:from-gray-950/30 dark:to-gray-900/40">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300">
                        <x-icon name="calendar-days" class="h-8 w-8" style="duotone" />
                    </div>
                    <p class="mt-6 text-lg font-bold text-gray-900 dark:text-white">Ainda sem eventos para ti</p>
                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-600 dark:text-gray-400">Quando a diretoria publicar encontros para jovens da tua realidade, eles aparecem aqui.</p>
                    @if(!empty($hasPublicCalendar))
                        <a href="{{ route('eventos.index') }}" class="mt-8 inline-flex items-center gap-2 rounded-2xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-md shadow-blue-600/25 transition-colors hover:bg-blue-700">
                            <x-icon name="globe" class="h-4 w-4" style="duotone" />
                            Ver eventos públicos
                        </a>
                    @endif
                </li>
            @endforelse
        </ul>
    </div>
</x-ui.jovens::page-shell>
@endsection
