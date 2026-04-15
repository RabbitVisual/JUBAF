@extends('homepage::layouts.homepage')

@php
    $chapterUrl = $devotional->scriptureChapterPublicUrl();
    $bodyPlain = strip_tags((string) $devotional->body);
    $wordCount = $bodyPlain !== '' ? count(preg_split('/\s+/u', trim($bodyPlain), -1, PREG_SPLIT_NO_EMPTY)) : 0;
    $readMinutes = max(1, (int) ceil($wordCount / 180));
    $listTitle = trim((string) ($s['homepage_devotionals_page_title'] ?? '')) ?: 'Devocionais';
    $displayDate = $devotional->devotional_date ?? $devotional->published_at;
@endphp

@section('title', $metaTitle)

@section('content')
@include('homepage::layouts.navbar-homepage')

@if ($devotional->cover_image_path)
    <header class="relative max-h-[min(28rem,70vh)] w-full overflow-hidden bg-slate-900">
        <img src="{{ $devotional->coverImageUrl() }}" alt="" class="size-full max-h-[min(28rem,70vh)] w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/55 to-slate-900/20" aria-hidden="true"></div>
        <div class="absolute inset-x-0 bottom-0">
            <div class="container mx-auto px-4 pb-10 pt-20 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-4xl">
                    @if ($devotional->theme)
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-amber-300">{{ $devotional->theme }}</p>
                    @endif
                    <h1 class="mt-2 font-poppins text-3xl font-bold tracking-tight text-white sm:text-4xl lg:text-5xl">{{ $devotional->title }}</h1>
                    <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-white/90">
                        <span class="inline-flex items-center gap-2">
                            <span class="flex size-10 items-center justify-center rounded-full bg-white/20 text-sm font-bold backdrop-blur-sm">{{ strtoupper(\Illuminate\Support\Str::substr($devotional->authorDisplayName(), 0, 1)) }}</span>
                            <span>
                                <span class="font-semibold">{{ $devotional->authorDisplayName() }}</span>
                                @if ($devotional->authorSubtitle())
                                    <span class="text-white/75"> · {{ $devotional->authorSubtitle() }}</span>
                                @endif
                            </span>
                        </span>
                        @if ($displayDate)
                            <span class="hidden text-white/40 sm:inline" aria-hidden="true">|</span>
                            <time datetime="{{ $displayDate instanceof \Carbon\CarbonInterface ? $displayDate->toIso8601String() : '' }}">
                                {{ $displayDate->translatedFormat('d \d\e F \d\e Y') }}
                            </time>
                        @endif
                        @if ($wordCount > 0)
                            <span class="hidden text-white/40 sm:inline" aria-hidden="true">|</span>
                            <span class="inline-flex items-center gap-1.5 text-white/85">
                                <x-icon name="clock" style="duotone" class="size-4 opacity-80" />
                                {{ $readMinutes }} min de leitura
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </header>
@else
    <header class="border-b border-gray-200/90 bg-white dark:border-slate-800 dark:bg-slate-950" aria-labelledby="devotional-title">
        <div class="container mx-auto px-4 py-12 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
            <div class="mx-auto max-w-4xl">
                @if ($devotional->theme)
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">{{ $devotional->theme }}</p>
                @endif
                <h1 id="devotional-title" class="mt-3 font-poppins text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl">{{ $devotional->title }}</h1>
                <div class="mt-8 flex flex-wrap items-center gap-4 text-gray-900 dark:text-white">
                    <span class="flex size-14 items-center justify-center rounded-full border border-gray-200 bg-gray-100 text-lg font-bold text-gray-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                        {{ strtoupper(\Illuminate\Support\Str::substr($devotional->authorDisplayName(), 0, 1)) }}
                    </span>
                    <div>
                        <p class="text-lg font-semibold">{{ $devotional->authorDisplayName() }}</p>
                        @if ($devotional->authorSubtitle())
                            <p class="text-sm text-gray-600 dark:text-slate-400">{{ $devotional->authorSubtitle() }}</p>
                        @endif
                        <p class="mt-1 flex flex-wrap items-center gap-x-2 text-sm text-gray-500 dark:text-slate-400">
                            @if ($displayDate)
                                <time datetime="{{ $displayDate instanceof \Carbon\CarbonInterface ? $displayDate->toIso8601String() : '' }}">{{ $displayDate->translatedFormat('d \d\e F \d\e Y') }}</time>
                            @endif
                            @if ($wordCount > 0)
                                @if ($displayDate)
                                    <span class="text-gray-300 dark:text-slate-600">·</span>
                                @endif
                                <span>{{ $readMinutes }} min de leitura</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endif

<div class="relative bg-gray-50 pb-20 dark:bg-slate-950 sm:pb-24">
    <article class="container relative z-10 mx-auto px-4 sm:px-6 lg:px-8">
        <div class="{{ $devotional->cover_image_path ? '-mt-8' : '-mt-10' }} mx-auto max-w-4xl">
            <div class="rounded-3xl border border-gray-200/90 bg-white/95 p-6 shadow-2xl shadow-blue-900/5 backdrop-blur-md dark:border-slate-700/90 dark:bg-slate-900/95 dark:shadow-black/40 sm:p-8 lg:p-10">
                <a href="{{ route('devocionais.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-bold text-gray-800 transition hover:border-blue-200 hover:bg-white dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-blue-500/50">
                    <x-icon name="chevron-left" class="size-4 text-blue-600 dark:text-blue-400" />
                    Voltar a {{ $listTitle }}
                </a>

                {{-- Leitura bíblica --}}
                <div class="mt-10 overflow-hidden rounded-2xl border border-blue-200/80 bg-gradient-to-br from-slate-50 to-white shadow-inner dark:border-blue-900/40 dark:from-slate-950 dark:to-slate-900/90">
                    <div class="jubaf-blue-panel flex flex-wrap items-center justify-between gap-3 px-5 py-4 sm:px-6">
                        <span class="inline-flex items-center gap-2 text-sm font-bold text-white">
                            <x-icon name="book-bible" style="duotone" class="size-5 text-amber-200" />
                            {{ $devotional->scripture_reference }}
                        </span>
                        @if ($chapterUrl)
                            <a href="{{ $chapterUrl }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2 text-xs font-bold text-white ring-1 ring-white/30 transition hover:bg-white/25">
                                <x-icon name="book-open" style="duotone" class="size-3.5" />
                                Ler capítulo na Bíblia
                            </a>
                        @endif
                    </div>
                    <div class="border-t border-blue-900/10 px-5 py-6 sm:px-8 sm:py-8 dark:border-white/10">
                        @if ($devotional->scripture_text)
                            <blockquote class="font-serif text-base leading-relaxed text-slate-800 dark:text-slate-100 sm:text-lg">
                                @foreach (preg_split("/\r\n|\n|\r/", $devotional->scripture_text) as $line)
                                    @if (trim($line) !== '')
                                        <p class="mb-3 last:mb-0">{{ $line }}</p>
                                    @endif
                                @endforeach
                            </blockquote>
                        @else
                            <p class="text-sm italic text-gray-500 dark:text-slate-400">Texto bíblico não foi anexado a este devocional.</p>
                        @endif
                        @if ($bv = $devotional->resolvedBibleVersion())
                            <p class="mt-5 border-t border-gray-200/80 pt-4 text-xs text-gray-500 dark:border-slate-700 dark:text-slate-400">
                                Versão:
                                <span class="font-semibold text-gray-800 dark:text-slate-200">{{ $bv->name }}</span>
                                ({{ $bv->abbreviation }})
                            </p>
                        @endif
                    </div>
                </div>

                @if ($devotional->video_path)
                    <div class="mt-10 overflow-hidden rounded-2xl border border-gray-200 bg-black shadow-xl dark:border-slate-700">
                        <video class="aspect-video w-full" controls playsinline preload="metadata" src="{{ $devotional->videoUrl() }}"></video>
                    </div>
                @elseif ($devotional->video_url)
                    <div class="mt-10 rounded-2xl border border-gray-200 bg-gradient-to-br from-slate-50 to-white p-6 dark:border-slate-700 dark:from-slate-800/50 dark:to-slate-900/80 sm:p-8">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-start gap-3">
                                <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                                    <x-icon name="play" style="duotone" class="size-5" />
                                </span>
                                <div>
                                    <h2 class="font-poppins text-base font-bold text-gray-900 dark:text-white">Vídeo complementar</h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-slate-400">Abre numa nova janela no serviço onde o vídeo está alojado.</p>
                                </div>
                            </div>
                            <a href="{{ $devotional->video_url }}" target="_blank" rel="noopener noreferrer"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-blue-700">
                                Ver vídeo
                                <x-icon name="arrow-up-right-from-square" style="duotone" class="size-4" />
                            </a>
                        </div>
                    </div>
                @endif

                <div class="prose prose-lg prose-slate mt-12 max-w-none dark:prose-invert">
                    <div class="mb-4 flex items-center gap-3 border-b border-gray-100 pb-4 dark:border-slate-800">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                            <x-icon name="lightbulb" style="duotone" class="size-5" />
                        </span>
                        <h2 class="!m-0 font-poppins text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Reflexão</h2>
                    </div>
                    <div class="leading-relaxed text-gray-800 dark:text-slate-200">
                        {!! nl2br(e($devotional->body)) !!}
                    </div>
                </div>

                <div class="mt-12 flex flex-col gap-4 border-t border-gray-100 pt-10 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-600 dark:text-slate-400">Gostou desta leitura? Explore mais reflexões na coleção.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('devocionais.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-blue-700">
                            <x-icon name="books" style="duotone" class="size-4" />
                            Ver todos os devocionais
                        </a>
                        @if (module_enabled('Bible') && Route::has('bible.public.index'))
                            <a href="{{ route('bible.public.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-bold text-gray-800 shadow-sm transition hover:border-blue-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                <x-icon name="book-bible" style="duotone" class="size-4 text-amber-600 dark:text-amber-400" />
                                Bíblia online
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

@include('homepage::layouts.footer-homepage')
@endsection
