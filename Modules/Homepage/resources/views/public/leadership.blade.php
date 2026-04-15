@extends('layouts.public-site')

@php
    $pageH1 = $s['homepage_board_section_title'] ?? 'Diretoria';
    $pageLead = trim((string) ($s['homepage_leadership_page_lead'] ?? ''));
    $boardIntro = trim((string) ($s['homepage_board_intro'] ?? ''));
    $heroBody = $pageLead !== '' ? $pageLead : $boardIntro;
@endphp

@section('title', $pageH1 . ' — ' . config('app.name'))

@push('head')
    @php
        $metaDesc = $pageLead !== '' ? $pageLead : ($boardIntro !== '' ? $boardIntro : 'Membros da diretoria JUBAF — mandato e serviço.');
    @endphp
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDesc), 160) }}" />
@endpush

@section('content')
    <div class="border-b border-border/70 bg-background">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
            @if (! empty($s['homepage_mandate_label']))
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">{{ $s['homepage_mandate_label'] }}</p>
            @endif
            <h1 class="mt-3 max-w-3xl text-3xl font-bold tracking-tight text-text sm:text-4xl dark:text-white">
                {{ $pageH1 }}
            </h1>
            @if ($heroBody !== '')
                <p class="mt-6 max-w-2xl text-base leading-relaxed text-text-muted sm:text-lg dark:text-slate-300">
                    {{ $heroBody }}
                </p>
            @endif

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('home.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="house" class="size-4 opacity-90" />
                    Início
                </a>
                @if (\Illuminate\Support\Facades\Route::has('eventos.index'))
                    <a href="{{ route('eventos.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-border/90 bg-background px-5 py-2.5 text-sm font-semibold text-text transition hover:bg-surface dark:border-slate-600 dark:text-white dark:hover:bg-slate-800">
                        <x-icon name="calendar-star" class="size-4 opacity-80" />
                        Eventos
                    </a>
                @endif
                @if (\Illuminate\Support\Facades\Route::has('secretaria.public.calendar'))
                    <a href="{{ route('secretaria.public.calendar') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-border/90 bg-background px-5 py-2.5 text-sm font-semibold text-text transition hover:bg-surface dark:border-slate-600 dark:text-white dark:hover:bg-slate-800">
                        <x-icon name="calendar-days" class="size-4 opacity-80" />
                        {{ $s['homepage_calendar_title'] ?? 'Calendário' }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-16">
        @if ($groupedMembers->flatten()->isNotEmpty())
            @foreach ($groupedMembers as $groupLabel => $members)
                <section class="{{ $loop->first ? '' : 'mt-16' }}">
                @if ($groupLabel !== '')
                    <div class="mb-6">
                        <h2 class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">
                            <x-icon name="users" class="size-4 opacity-80" />
                            {{ $groupLabel }}
                        </h2>
                        <div class="mt-2 h-px max-w-xs bg-gradient-to-r from-blue-500/50 to-transparent dark:from-blue-400/40"></div>
                    </div>
                @endif
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($members as $member)
                        <article
                            class="group flex flex-col overflow-hidden rounded-2xl border border-border/90 bg-background shadow-md ring-1 ring-black/[0.03] transition hover:-translate-y-0.5 hover:border-blue-300/60 hover:shadow-xl dark:border-slate-700 dark:bg-slate-950 dark:ring-white/[0.04] dark:hover:border-blue-600/50">
                            <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-blue-600/25 via-slate-200/50 to-slate-300/40 dark:from-blue-500/15 dark:via-slate-800/80 dark:to-slate-900">
                                @if ($member->photoUrl())
                                    <img src="{{ $member->photoUrl() }}" alt=""
                                        class="size-full object-cover transition duration-500 group-hover:scale-[1.03]" loading="lazy" decoding="async" />
                                @else
                                    <div class="flex size-full items-center justify-center text-5xl font-black text-blue-600/35 dark:text-blue-400/35">
                                        {{ mb_strtoupper(mb_substr($member->displayName(), 0, 1)) }}
                                    </div>
                                @endif
                                <div
                                    class="pointer-events-none absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-950/50 to-transparent opacity-0 transition group-hover:opacity-100 dark:from-slate-950/70">
                                </div>
                            </div>
                            <div class="flex flex-1 flex-col p-6">
                                <h3 class="text-xl font-bold text-text dark:text-white">{{ $member->displayName() }}</h3>
                                <p class="mt-1 text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $member->public_title }}</p>
                                @if ($member->publicAge())
                                    <p class="mt-2 text-sm text-text-muted">{{ $member->publicAge() }} anos</p>
                                @endif
                                @if ($member->resolvedLocation())
                                    <p class="mt-1 flex items-start gap-2 text-sm text-text-muted">
                                        <x-icon name="location-dot" class="mt-0.5 size-4 shrink-0 text-blue-600/70 dark:text-blue-400/70" />
                                        {{ $member->resolvedLocation() }}
                                    </p>
                                @endif
                                @if ($member->specialization)
                                    <p class="mt-4 text-sm text-text">
                                        <span class="font-semibold text-text-muted">Área:</span> {{ $member->specialization }}
                                    </p>
                                @endif
                                @if ($member->bio_short)
                                    <p class="mt-3 flex-1 text-sm leading-relaxed text-text-muted">{{ $member->bio_short }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
                </section>
            @endforeach
        @else
            <div
                class="mx-auto max-w-lg rounded-3xl border border-dashed border-border/90 bg-surface/40 px-8 py-16 text-center dark:border-slate-700 dark:bg-slate-900/30">
                <div
                    class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-blue-600/10 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                    <x-icon name="users" class="size-8 opacity-80" />
                </div>
                <h2 class="mt-6 text-xl font-bold text-text dark:text-white">Lista em breve</h2>
                <p class="mt-2 text-sm text-text-muted">A composição da diretoria será publicada assim que estiver disponível.</p>
                <a href="{{ route('home.index') }}"
                    class="mt-8 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    <x-icon name="house" class="size-4" />
                    Voltar ao início
                </a>
            </div>
        @endif
    </div>
@endsection
