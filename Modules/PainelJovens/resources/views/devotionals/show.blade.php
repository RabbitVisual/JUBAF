@extends('paineljovens::layouts.jovens')

@section('title', $devotional->title)


@section('jovens_content')
@php
    $bodyPlain = strip_tags((string) $devotional->body);
    $wordCount = $bodyPlain !== '' ? count(preg_split('/\s+/u', trim($bodyPlain), -1, PREG_SPLIT_NO_EMPTY)) : 0;
    $readMinutes = max(1, (int) ceil($wordCount / 180));
    $displayDate = $devotional->devotional_date ?? $devotional->published_at;
    $chapterJovens = $devotional->scriptureChapterJovensUrl();
    $chapterPublic = $devotional->scriptureChapterPublicUrl();
    $chapterUrl = $chapterJovens ?? $chapterPublic;
    $ytId = null;
    if ($devotional->video_url) {
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{6,})/', $devotional->video_url, $m)) {
            $ytId = $m[1];
        }
    }
@endphp

<x-ui.jovens::page-shell class="space-y-8 pb-10">
    @if ($devotional->cover_image_path)
        <div class="relative max-h-[min(22rem,55vh)] overflow-hidden rounded-[2rem] border border-gray-200/80 dark:border-gray-800">
            <img src="{{ $devotional->coverImageUrl() }}" alt="" class="w-full h-full max-h-[min(22rem,55vh)] object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950/90 via-gray-950/40 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 p-6 md:p-8">
                @if ($devotional->theme)
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200">{{ $devotional->theme }}</p>
                @endif
                <h1 class="mt-2 text-2xl md:text-3xl font-bold text-white leading-tight">{{ $devotional->title }}</h1>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-white/90">
                    @if ($au = $devotional->avatarUser())
                        <x-user-avatar :user="$au" size="md" class="ring-2 ring-white/30" />
                    @else
                        <span class="flex size-11 items-center justify-center rounded-full bg-white/20 text-sm font-bold backdrop-blur-sm">
                            {{ strtoupper(\Illuminate\Support\Str::substr($devotional->authorDisplayName(), 0, 1)) }}
                        </span>
                    @endif
                    <div>
                        <p class="font-semibold">{{ $devotional->authorDisplayName() }}</p>
                        @if ($devotional->authorSubtitle())
                            <p class="text-white/75 text-sm">{{ $devotional->authorSubtitle() }}</p>
                        @endif
                    </div>
                    @if ($displayDate)
                        <span class="hidden sm:inline text-white/40">|</span>
                        <time datetime="{{ $displayDate->toIso8601String() }}">{{ $displayDate->translatedFormat('d \d\e F \d\e Y') }}</time>
                    @endif
                    @if ($wordCount > 0)
                        <span class="hidden sm:inline text-white/40">|</span>
                        <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="w-4 h-4 opacity-80" style="duotone" /> {{ $readMinutes }} min</span>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="rounded-2xl border border-gray-200 bg-white p-8 dark:border-gray-800 dark:bg-gray-900 md:p-10">
            @if ($devotional->theme)
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400">{{ $devotional->theme }}</p>
            @endif
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-gray-900 dark:text-white md:text-4xl">{{ $devotional->title }}</h1>
            <div class="mt-8 flex flex-wrap items-center gap-4">
                @if ($au = $devotional->avatarUser())
                    <x-user-avatar :user="$au" size="lg" class="ring-2 ring-blue-200 dark:ring-blue-800" />
                @else
                    <span class="flex size-14 items-center justify-center rounded-full bg-gradient-to-br from-blue-600 to-blue-800 text-lg font-bold text-white shadow-lg">
                        {{ strtoupper(\Illuminate\Support\Str::substr($devotional->authorDisplayName(), 0, 1)) }}
                    </span>
                @endif
                <div>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $devotional->authorDisplayName() }}</p>
                    @if ($devotional->authorSubtitle())
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $devotional->authorSubtitle() }}</p>
                    @endif
                    <p class="mt-1 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        @if ($displayDate)
                            <time datetime="{{ $displayDate->toIso8601String() }}">{{ $displayDate->translatedFormat('d \d\e F \d\e Y') }}</time>
                        @endif
                        @if ($wordCount > 0 && $displayDate)
                            <span>·</span>
                        @endif
                        @if ($wordCount > 0)
                            <span>{{ $readMinutes }} min de leitura</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="space-y-10 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 md:p-10">
        <a href="{{ route('jovens.devotionals.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-800 transition hover:border-blue-300 hover:bg-blue-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:border-blue-600">
            <x-icon name="chevron-left" class="w-4 h-4 text-blue-600 dark:text-blue-400" />
            Voltar aos devocionais
        </a>

        {{-- Passagem --}}
        <div class="overflow-hidden rounded-2xl border border-blue-200/80 bg-gradient-to-br from-blue-50 to-white dark:border-blue-900/50 dark:from-blue-950/40 dark:to-gray-900">
            <div class="flex flex-wrap items-center justify-between gap-3 bg-gradient-to-r from-blue-600 to-blue-800 px-5 py-4">
                <span class="inline-flex items-center gap-2 text-sm font-bold text-white">
                    <x-icon name="book-bible" style="duotone" class="w-5 h-5 text-amber-200" />
                    {{ $devotional->scripture_reference }}
                </span>
                @if ($chapterUrl)
                    <a href="{{ $chapterUrl }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white/20 px-4 py-2 text-xs font-bold text-white ring-1 ring-white/30 transition hover:bg-white/30">
                        <x-icon name="book-open" style="duotone" class="w-3.5 h-3.5" />
                        Ler capítulo na Bíblia
                    </a>
                @endif
            </div>
            <div class="border-t border-blue-900/10 px-5 py-6 sm:px-8 dark:border-white/10">
                @if ($devotional->scripture_text)
                    <blockquote class="font-serif text-base leading-relaxed text-gray-800 dark:text-gray-100 sm:text-lg">
                        @foreach (preg_split("/\r\n|\n|\r/", $devotional->scripture_text) as $line)
                            @if (trim($line) !== '')
                                <p class="mb-3 last:mb-0">{{ $line }}</p>
                            @endif
                        @endforeach
                    </blockquote>
                @else
                    <p class="text-sm italic text-gray-500 dark:text-gray-400">Texto bíblico não foi anexado a este devocional.</p>
                @endif
                @if ($bv = $devotional->resolvedBibleVersion())
                    <p class="mt-5 border-t border-gray-200/80 pt-4 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
                        Versão: <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $bv->name }}</span> ({{ $bv->abbreviation }})
                    </p>
                @endif
            </div>
        </div>

        {{-- Vídeo upload --}}
        @if ($devotional->video_path)
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-black shadow-xl dark:border-gray-700">
                <video class="aspect-video w-full" controls playsinline preload="metadata" src="{{ $devotional->videoUrl() }}"></video>
            </div>
        @elseif ($ytId)
            <div class="aspect-video overflow-hidden rounded-2xl border border-gray-200 bg-black shadow-xl dark:border-gray-700">
                <iframe class="h-full w-full min-h-[240px]"
                    src="https://www.youtube-nocookie.com/embed/{{ $ytId }}?rel=0"
                    title="Vídeo do devocional"
                    loading="lazy"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
        @elseif ($devotional->video_url)
            <div class="rounded-2xl border border-gray-200 bg-gradient-to-br from-gray-50 to-white p-6 dark:border-gray-700 dark:from-gray-800/50 dark:to-gray-900/80 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">
                            <x-icon name="play" style="duotone" class="w-5 h-5" />
                        </span>
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-white">Vídeo complementar</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Abre numa nova janela.</p>
                        </div>
                    </div>
                    <a href="{{ $devotional->video_url }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg transition hover:bg-blue-700">
                        Ver vídeo
                        <x-icon name="arrow-up-right-from-square" style="duotone" class="w-4 h-4" />
                    </a>
                </div>
            </div>
        @endif

        {{-- Reflexão --}}
        <div>
            <div class="mb-4 flex items-center gap-3 border-b border-gray-100 pb-4 dark:border-gray-800">
                <span class="flex size-10 items-center justify-center rounded-xl bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">
                    <x-icon name="lightbulb" style="duotone" class="w-5 h-5" />
                </span>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white sm:text-2xl">Reflexão</h2>
            </div>
            <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-semibold prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-p:leading-relaxed">
                {!! \Illuminate\Support\Str::markdown($devotional->body) !!}
            </div>
        </div>

        <div class="flex flex-col gap-4 border-t border-gray-100 pt-8 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-600 dark:text-gray-400">Partilha com a tua célula ou guarda para meditar mais tarde.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('jovens.devotionals.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-blue-700">
                    <x-icon name="book-open" style="duotone" class="w-4 h-4" />
                    Todos os devocionais
                </a>
                @if (Route::has('devocionais.show'))
                    <a href="{{ route('devocionais.show', $devotional) }}" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:border-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                        <x-icon name="globe" style="duotone" class="h-4 w-4 text-blue-600" />
                        Ver no site público
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-ui.jovens::page-shell>
@endsection
