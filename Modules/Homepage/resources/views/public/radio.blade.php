@extends('homepage::layouts.homepage')

@php
    $site = \App\Support\SiteBranding::siteName();
    $isLive = $showRadio && $embedUrl !== '';
@endphp

@section('title')
{{ $radioTitle }} — {{ $site }}
@endsection

@section('content')
@include('homepage::layouts.navbar-homepage')

{{-- Herói minimalista --}}
<section class="border-b border-gray-200/90 bg-white dark:border-slate-800 dark:bg-slate-950" aria-labelledby="radio-hero-title">
    <div class="container mx-auto px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
        <div class="mx-auto max-w-4xl text-center">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Transmissão</p>
            <h1 id="radio-hero-title" class="mt-3 font-poppins text-3xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl">
                {{ $radioTitle }}
            </h1>
            <p class="mt-2 text-base text-gray-500 dark:text-slate-400">
                {{ $site }}
            </p>

            @if (filled($radioLead))
                <p class="mx-auto mt-6 max-w-2xl text-base leading-relaxed text-gray-600 dark:text-slate-300 sm:text-lg">
                    {{ $radioLead }}
                </p>
            @endif

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                @if ($isLive)
                    <span class="inline-flex items-center gap-2 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-emerald-800 dark:border-emerald-900/50 dark:bg-emerald-950/50 dark:text-emerald-300">
                        <span class="relative flex h-2.5 w-2.5 shrink-0" aria-hidden="true">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        </span>
                        No ar
                    </span>
                @endif
                <a href="{{ route('homepage') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="house" style="duotone" class="size-4" />
                    Voltar ao início
                </a>
                @if ($isLive)
                    <a href="{{ route('radio') }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                        <x-icon name="square-arrow-up-right" style="duotone" class="size-4" />
                        Abrir em nova aba
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Conteúdo em painel elevado --}}
<div class="relative min-h-[40vh] bg-gray-50 pb-20 pt-0 dark:bg-slate-950 sm:pb-24">
    <div class="container relative z-10 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="-mt-10 rounded-3xl border border-gray-200/90 bg-white/95 p-6 shadow-2xl shadow-blue-900/5 backdrop-blur-md dark:border-slate-700/90 dark:bg-slate-900/95 dark:shadow-black/40 sm:p-8 lg:-mt-14 lg:p-10">

            @if ($isLive)
                <div class="overflow-hidden rounded-2xl border border-gray-200/90 shadow-xl shadow-slate-900/10 ring-1 ring-black/5 dark:border-slate-600/80 dark:shadow-black/30 dark:ring-white/5">
                    <div class="flex flex-col items-stretch justify-between gap-3 border-b border-slate-700/80 bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-4 sm:flex-row sm:items-center sm:px-8">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/logo-rede-316-player-degrade.png') }}" alt="Rede 3.16" class="h-9 w-auto object-contain opacity-95 sm:h-11" onerror="this.style.display='none'">
                            <div class="hidden h-8 w-px bg-white/15 sm:block" aria-hidden="true"></div>
                            <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400 sm:text-xs">Player</span>
                        </div>
                        <span class="inline-flex items-center gap-2 self-start rounded-lg bg-emerald-500/20 px-3 py-1 text-xs font-semibold text-emerald-200 sm:self-auto">
                            <span class="size-1.5 rounded-full bg-emerald-400" aria-hidden="true"></span>
                            Ao vivo
                        </span>
                    </div>
                    <div class="relative overflow-hidden bg-slate-100 dark:bg-slate-950/60" style="min-height: 280px;">
                        <iframe
                            src="{{ $embedUrlForPage }}"
                            title="{{ $radioTitle }} — ao vivo"
                            scrolling="no"
                            allow="autoplay; clipboard-write"
                            class="block w-full border-0"
                            style="height: 280px; min-height: 260px;"
                            loading="eager"></iframe>
                    </div>
                    <p class="border-t border-gray-100 bg-gray-50/90 px-4 py-2.5 text-center text-[10px] text-gray-600 dark:border-slate-800 dark:bg-slate-900/80 dark:text-slate-400 sm:text-xs">
                        Se o áudio não iniciar, use o controlo do player.
                    </p>
                </div>

                @if (!empty($randomVerse))
                    <div class="mt-8 rounded-2xl border border-amber-200/70 bg-gradient-to-br from-amber-50/95 to-orange-50/40 p-6 dark:border-amber-900/45 dark:from-stone-900/90 dark:to-stone-950/80 sm:p-8">
                        <p class="mb-3 text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-400">Versículo do momento</p>
                        <blockquote class="font-serif text-lg italic leading-relaxed text-stone-800 dark:text-stone-100 sm:text-xl">"{{ $randomVerse['text'] }}"</blockquote>
                        <cite class="mt-3 block text-sm font-semibold text-amber-900 not-italic dark:text-amber-300">{{ $randomVerse['reference'] }}</cite>
                    </div>
                @endif

                <div class="mt-8 grid gap-4 md:grid-cols-2">
                    <div class="flex items-start gap-4 rounded-2xl border border-blue-100/90 bg-blue-50/70 p-5 dark:border-blue-800/40 dark:bg-blue-950/30">
                        <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/10 dark:bg-blue-400/10">
                            <x-icon name="circle-info" style="duotone" class="size-5 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-900 dark:text-white">Ouça enquanto navega</h2>
                            <p class="mt-1 text-sm leading-relaxed text-gray-700 dark:text-gray-300">Abra esta página noutro separador para manter a transmissão enquanto usa o resto do site.</p>
                        </div>
                    </div>
                    @if ($officialUrl !== '')
                        <div class="flex items-start gap-4 rounded-2xl border border-gray-200/90 bg-gray-50/80 p-5 dark:border-slate-700 dark:bg-slate-800/40">
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-gray-200/80 dark:bg-slate-700/80">
                                <x-icon name="globe" style="duotone" class="size-5 text-gray-600 dark:text-slate-300" />
                            </div>
                            <div>
                                <h2 class="font-semibold text-gray-900 dark:text-white">Mais informação</h2>
                                <a href="{{ $officialUrl }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">
                                    Site da rádio
                                    <x-icon name="arrow-up-right-from-square" style="duotone" class="size-4 shrink-0" />
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-slate-50/80 px-8 py-16 text-center dark:border-slate-700 dark:bg-slate-900/50">
                    <x-icon name="tower-broadcast" style="duotone" class="mb-4 size-14 text-gray-400 dark:text-slate-500" />
                    <h2 class="font-poppins text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Rádio indisponível</h2>
                    <p class="mt-3 max-w-md text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        A transmissão não está configurada. Configure o URL do player no painel da homepage.
                    </p>
                    <a href="{{ route('homepage') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700">
                        <x-icon name="house" style="duotone" class="size-5" />
                        Voltar ao início
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
