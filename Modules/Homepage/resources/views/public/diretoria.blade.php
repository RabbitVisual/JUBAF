@extends('homepage::layouts.homepage')

@php
    $site = \App\Support\SiteBranding::siteName();
    $memberCount = $members->count();
@endphp

@section('title')
    Diretoria — {{ $site }}
@endsection

@section('content')
    @include('homepage::layouts.navbar-homepage')

    {{-- Herói minimalista --}}
    <section class="border-b border-gray-200/90 bg-white dark:border-slate-800 dark:bg-slate-950"
        aria-labelledby="diretoria-hero-title">
        <div class="container mx-auto px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
            <div class="mx-auto max-w-4xl text-center">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Transparência
                    institucional</p>
                <h1 id="diretoria-hero-title"
                    class="mt-3 font-poppins text-3xl font-bold leading-tight tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl">
                    Diretoria
                </h1>
                <p class="mt-2 text-base text-gray-500 dark:text-slate-400">
                    {{ $site }}
                </p>

                @if (filled($pageIntro ?? ''))
                    <p class="mx-auto mt-6 max-w-3xl text-base leading-relaxed text-gray-600 dark:text-slate-300 sm:text-lg">
                        {{ $pageIntro }}
                    </p>
                @else
                    <p class="mx-auto mt-6 max-w-3xl text-base leading-relaxed text-gray-600 dark:text-slate-300 sm:text-lg">
                        Membros eleitos e em funções conforme o Estatuto (capítulo III). Abaixo encontram-se os cargos do
                        mandato em curso.
                    </p>
                @endif

                @if ($memberCount > 0)
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                        <span
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 dark:border-slate-700 dark:bg-slate-800/50 dark:text-slate-200">
                            <x-icon name="users" style="duotone" class="size-4 text-blue-600 dark:text-blue-400" />
                            {{ $memberCount }} {{ $memberCount === 1 ? 'membro' : 'membros' }} em destaque
                        </span>
                        <a href="{{ route('homepage') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <x-icon name="house" style="duotone" class="size-4" />
                            Voltar ao início
                        </a>
                    </div>
                @else
                    <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                        <a href="{{ route('homepage') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <x-icon name="house" style="duotone" class="size-4" />
                            Voltar ao início
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Cartões da equipe --}}
    <div class="relative min-h-[30vh] bg-gray-50 pb-20 dark:bg-slate-950 sm:pb-24">
        <div class="container relative z-10 mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="-mt-10 rounded-3xl border border-gray-200/90 bg-white/95 p-6 shadow-2xl shadow-blue-900/5 backdrop-blur-md dark:border-slate-700/90 dark:bg-slate-900/95 dark:shadow-black/40 sm:p-8 lg:-mt-14 lg:p-10">

                @if ($members->isEmpty())
                    <div
                        class="flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-slate-50/80 px-8 py-16 text-center dark:border-slate-700 dark:bg-slate-900/50">
                        <x-icon name="users" style="duotone" class="mb-4 size-14 text-gray-400 dark:text-slate-500" />
                        <h2 class="font-poppins text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Composição em
                            atualização</h2>
                        <p class="mt-3 max-w-md text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                            A composição da diretoria será publicada em breve. Volte mais tarde ou contacte-nos pelos canais
                            institucionais.
                        </p>
                        <a href="{{ route('homepage') }}"
                            class="mt-8 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-blue-700">
                            <x-icon name="house" style="duotone" class="size-4" />
                            Voltar ao início
                        </a>
                    </div>
                @else
                    <div class="border-b border-gray-100 pb-8 dark:border-slate-800">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">Equipa em
                            serviço</p>
                        <h2
                            class="mt-2 font-poppins text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                            Conheça quem serve
                        </h2>
                        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                            Informações oficiais sobre mandato, cargos e perfis da diretoria em exercício.
                        </p>
                    </div>

                    <ul class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8" role="list">
                        @foreach ($members as $member)
                            <li>
                                <article
                                    class="group flex h-full flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-md ring-1 ring-black/5 transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl dark:border-slate-700/90 dark:bg-slate-950 dark:ring-white/5 dark:hover:border-blue-500/40">
                                    <div
                                        class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-950/60 dark:to-indigo-950/50">
                                        @if ($member->photo_path)
                                            <img src="{{ asset('storage/' . $member->photo_path) }}" alt=""
                                                class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
                                                loading="lazy" width="480" height="360" />
                                        @else
                                            <div class="flex h-full w-full items-center justify-center">
                                                <span
                                                    class="flex size-28 items-center justify-center rounded-full bg-white/95 text-3xl font-bold text-blue-700 shadow-lg dark:bg-slate-800/95 dark:text-blue-300"
                                                    aria-hidden="true">
                                                    {{ strtoupper(\Illuminate\Support\Str::substr($member->full_name, 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-blue-900/25 to-transparent opacity-0 transition duration-300 group-hover:opacity-100 dark:from-slate-950/50"
                                            aria-hidden="true"></div>
                                    </div>
                                    <div class="flex flex-1 flex-col p-6">
                                        <h3 class="font-poppins text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $member->full_name }}
                                        </h3>
                                        <p
                                            class="mt-1 inline-flex w-fit rounded-lg bg-blue-50 px-2.5 py-1 text-sm font-bold text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                            {{ $member->title_display }}
                                        </p>
                                        @if (filled($member->group_label))
                                            <p
                                                class="mt-2 text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-slate-400">
                                                {{ $member->group_label }}</p>
                                        @endif
                                        @if (filled($member->formation))
                                            <p
                                                class="mt-4 flex-1 text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                                                {{ $member->formation }}
                                            </p>
                                        @endif
                                        <div
                                            class="mt-5 space-y-1.5 border-t border-gray-100 pt-4 text-xs text-gray-500 dark:border-slate-800 dark:text-slate-400">
                                            @if ($member->mandate_year)
                                                <p>
                                                    <span
                                                        class="font-semibold text-gray-700 dark:text-gray-300">Gestão:</span>
                                                    {{ $member->mandate_year }}
                                                </p>
                                            @endif
                                            @if ($member->mandate_end)
                                                <p>
                                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Mandato
                                                        até:</span>
                                                    {{ $member->mandate_end->translatedFormat('d \d\e F \d\e Y') }}
                                                </p>
                                            @endif
                                            @if (!$member->mandate_year && !$member->mandate_end)
                                                <p class="italic text-gray-400 dark:text-slate-500">Datas de mandato a
                                                    definir.</p>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    @include('homepage::layouts.footer-homepage')
@endsection
