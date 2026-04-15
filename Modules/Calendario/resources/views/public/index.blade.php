@extends('homepage::layouts.homepage')

@section('title')
Eventos — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
@include('homepage::layouts.navbar-homepage')

@php
    $siteName = \App\Support\SiteBranding::siteName();
@endphp

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-teal-50/40 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 py-12 md:py-16">
    <div class="container mx-auto max-w-4xl px-4">
        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-900 dark:bg-teal-900/40 dark:text-teal-100">
                <x-icon name="calendar-days" style="duotone" class="h-4 w-4" />
                Agenda pública
            </div>
            <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white md:text-4xl">Eventos {{ $siteName }}</h1>
            <p class="mx-auto mt-3 max-w-2xl text-base text-gray-600 dark:text-gray-300">Encontros, cultos e momentos da juventude abertos a toda a comunidade.</p>
        </div>

        <ul class="space-y-4">
            @forelse($events as $event)
                <li class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm transition hover:border-teal-200 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-teal-800">
                    <a href="{{ route('eventos.show', $event->slug) }}" class="block p-5 md:p-6">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wide text-teal-700 dark:text-teal-400">{{ $event->starts_at ? ($event->all_day ? $event->starts_at->format('d/m/Y · dia inteiro') : $event->starts_at->format('d/m/Y · H:i')) : '—' }}</p>
                                <h2 class="mt-1 text-xl font-bold text-gray-900 dark:text-white">{{ $event->title }}</h2>
                                @if($event->location)
                                    <p class="mt-2 inline-flex items-center gap-1.5 text-sm text-gray-600 dark:text-gray-400">
                                        <x-icon name="location-dot" class="h-4 w-4 text-teal-600 dark:text-teal-400" style="duotone" />
                                        {{ $event->location }}
                                    </p>
                                @endif
                            </div>
                            <span class="inline-flex shrink-0 items-center gap-1 text-sm font-semibold text-teal-700 dark:text-teal-400">
                                Ver detalhes
                                <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                            </span>
                        </div>
                    </a>
                </li>
            @empty
                <li class="rounded-2xl border border-dashed border-gray-300 bg-white/60 px-6 py-16 text-center dark:border-slate-600 dark:bg-slate-800/40">
                    <x-icon name="calendar-days" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" style="duotone" />
                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Sem eventos públicos agendados</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Volte em breve ou contacte a diretoria da JUBAF.</p>
                </li>
            @endforelse
        </ul>

        @if($events->hasPages())
            <div class="mt-10">{{ $events->links() }}</div>
        @endif
    </div>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
