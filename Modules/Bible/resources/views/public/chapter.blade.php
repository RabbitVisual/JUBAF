@extends('layouts.bible-public-homepage')

@section('title', $book->name . ' ' . $chapter->chapter_number . ' – Bíblia ' . $version->abbreviation)

@php
    $bibleChapterConfig = [
        'apiBase' => url('api/v1/bible'),
        'versionAbbr' => $version->abbreviation,
        'bookNumber' => $book->book_number,
        'chapterNumber' => $chapter->chapter_number,
        'bookName' => $book->name,
        'versions' => $versions->map(fn($v) => ['id' => $v->id, 'abbreviation' => $v->abbreviation, 'name' => $v->name])->values()->toArray(),
        'chapterAudioUrl' => $chapterAudioUrl ?? null,
        'versionName' => $version->name,
        'chapterUrlTemplate' => route('bible.public.chapter', [
            'versionAbbr' => '__V__',
            'bookNumber' => '__B__',
            'chapterNumber' => '__C__',
        ]),
    ];
@endphp
@push('head')
    @include('bible::public.partials.sacred-reader-theme')
    <style>
        .bible-reading-column { max-width: 42rem; }
        [x-cloak] { display: none !important; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
@endpush

@section('bible_public_content')
<script>window.__bibleChapterConfig = @json($bibleChapterConfig);</script>
<script>
    (function () {
        try {
            var c = window.__bibleChapterConfig || {};
            if (c.versionAbbr != null && c.bookNumber != null && c.chapterNumber != null) {
                localStorage.setItem(
                    'bible_public_last_chapter',
                    JSON.stringify({
                        versionAbbr: String(c.versionAbbr),
                        book_number: c.bookNumber,
                        chapter_number: c.chapterNumber,
                        book_name: c.bookName || '',
                    })
                );
            }
        } catch (e) {}
    })();
</script>
<div class="bible-sacred-root bible-public-container min-h-screen pb-28"
     id="bible-public-chapter"
     x-data="bibleChapter"
     x-init="init()"
     :data-bible-palette="biblePalette"
     x-effect="document.body.classList.toggle('bible-reading-mode', readingMode)"
     @keydown.escape.window="if (booksOpen) { booksOpen = false; } if (searchOpen) { searchOpen = false; }">

    {{-- Sticky header --}}
    <nav class="sticky top-0 z-40 bible-sacred-header transition-all"
         x-show="!readingMode"
         x-transition>
        <div class="max-w-4xl mx-auto px-3 sm:px-6 py-2.5 sm:py-3">
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1 sm:gap-2 shrink-0">
                    <button @click="booksOpen = true"
                            type="button"
                            class="flex items-center gap-1.5 p-2 sm:px-3 sm:py-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] hover:text-[color:var(--sacred-ink)] transition-colors"
                            aria-label="Abrir lista de livros">
                        <x-icon name="book-open" class="w-5 h-5" />
                        <span class="hidden sm:inline text-sm font-bold">Livros</span>
                    </button>
                    <a href="{{ route('bible.public.book', [$version->abbreviation, $book->book_number]) }}"
                       class="p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] transition-colors"
                       aria-label="Voltar ao livro">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </a>
                </div>
                <div class="flex-1 min-w-0 flex flex-col sm:flex-row items-center justify-center gap-0.5 sm:gap-2 text-center">
                    <h1 class="text-sm sm:text-base font-black text-[color:var(--sacred-ink)] truncate w-full sm:w-auto">
                        {{ $book->name }} <span class="text-[color:var(--sacred-accent)]">{{ $chapter->chapter_number }}</span>
                    </h1>
                    <label for="pub-version" class="sr-only">Versão</label>
                    <select id="pub-version"
                            @change="goToVersion($event.target.value)"
                            class="text-xs sm:text-sm font-bold text-[color:var(--sacred-accent)] bg-transparent border-0 py-1 pr-6 focus:ring-0 cursor-pointer max-w-[7rem] sm:max-w-none truncate">
                        @foreach($versions as $v)
                            <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>{{ $v->abbreviation }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button @click="searchOpen = true; searchResults = null; searchQuery = ''"
                            type="button"
                            class="p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] hover:text-[color:var(--sacred-accent)] transition-colors"
                            aria-label="Busca">
                        <x-icon name="magnifying-glass" class="w-5 h-5" />
                    </button>
                    <button @click="compareMode = !compareMode; if(compareMode && compareVersion2) fetchCompare()"
                            :class="compareMode ? 'bg-[color:var(--sacred-parchment-deep)] text-[color:var(--sacred-accent)] ring-2 ring-[color:var(--sacred-edge)]/40' : 'text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)]'"
                            class="p-2 rounded-lg transition-colors"
                            type="button"
                            aria-label="Comparar versões">
                        <x-icon name="columns-3" class="w-5 h-5" />
                    </button>
                    <button @click="readingMode = !readingMode"
                            type="button"
                            class="flex items-center gap-1.5 pl-2 pr-2.5 py-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] hover:text-[color:var(--sacred-accent)] transition-colors"
                            :aria-pressed="readingMode"
                            title="Modo leitura: tela limpa, sem menu do site (também em tela cheia)"
                            aria-label="Modo leitura — oculta o menu do site">
                        <x-icon name="book-open-reader" class="w-5 h-5 shrink-0" />
                        <span class="hidden sm:inline text-xs font-black uppercase tracking-wide text-[color:var(--sacred-accent)]">Foco</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Chapter pills --}}
    <div class="sticky top-[52px] sm:top-[56px] z-30 bible-sacred-header py-2 px-3 overflow-x-auto scrollbar-hide"
         x-show="!readingMode"
         x-transition>
        <div class="max-w-3xl mx-auto flex gap-1.5 justify-center flex-nowrap sm:flex-wrap min-w-0">
            @for($i = 1; $i <= ($totalChapters ?? 0); $i++)
                <a href="{{ route('bible.public.chapter', [$version->abbreviation, $book->book_number, $i]) }}"
                   class="flex-shrink-0 w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-lg text-sm font-bold transition-all
                   {{ $i == $chapter->chapter_number ? 'bg-[color:var(--sacred-accent)] text-white shadow-md' : 'text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)]' }}">
                    {{ $i }}
                </a>
            @endfor
        </div>
    </div>

    {{-- Leitura: tamanho + tom do pergaminho --}}
    <div class="fixed right-3 bottom-24 sm:bottom-28 z-20 flex flex-col gap-1 bible-sacred-paper p-1.5 rounded-xl shadow-lg max-w-[10rem]"
         x-show="!readingMode"
         x-transition>
        <button @click="fontPanelOpen = !fontPanelOpen; palettePanelOpen = false" type="button" class="p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)]" aria-label="Tamanho da fonte">
            <x-icon name="font" class="w-5 h-5" />
        </button>
        <template x-if="fontPanelOpen">
            <div class="flex items-center gap-1 border-t border-[color:var(--sacred-edge)]/25 pt-2 mt-1">
                <button @click="fontSizeDown()" type="button" class="w-8 h-8 rounded-lg bg-[color:var(--sacred-parchment-deep)] text-sm font-bold text-[color:var(--sacred-ink)]" aria-label="Diminuir fonte">A-</button>
                <span class="text-xs font-bold text-[color:var(--sacred-ink-muted)] min-w-[2.5rem]" x-text="fontSize + '%'"></span>
                <button @click="fontSizeUp()" type="button" class="w-8 h-8 rounded-lg bg-[color:var(--sacred-parchment-deep)] text-sm font-bold text-[color:var(--sacred-ink)]" aria-label="Aumentar fonte">A+</button>
            </div>
        </template>
        <button @click="palettePanelOpen = !palettePanelOpen; fontPanelOpen = false" type="button" class="p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] border-t border-[color:var(--sacred-edge)]/20" aria-label="Tom do pergaminho" title="Tom do pergaminho">
            <x-icon name="droplet" class="w-5 h-5" />
        </button>
        <template x-if="palettePanelOpen">
            <div class="flex flex-col gap-1 border-t border-[color:var(--sacred-edge)]/25 pt-2 mt-1">
                <button type="button" @click="setPalette('classic')" class="text-left px-2 py-1.5 rounded-lg text-xs font-bold" :class="biblePalette === 'classic' ? 'bg-[color:var(--sacred-accent)] text-white' : 'text-[color:var(--sacred-ink)] hover:bg-[color:var(--sacred-parchment-deep)]'">Clássico</button>
                <button type="button" @click="setPalette('sepia')" class="text-left px-2 py-1.5 rounded-lg text-xs font-bold" :class="biblePalette === 'sepia' ? 'bg-[color:var(--sacred-accent)] text-white' : 'text-[color:var(--sacred-ink)] hover:bg-[color:var(--sacred-parchment-deep)]'">Sépia</button>
                <button type="button" @click="setPalette('contrast')" class="text-left px-2 py-1.5 rounded-lg text-xs font-bold" :class="biblePalette === 'contrast' ? 'bg-[color:var(--sacred-accent)] text-white' : 'text-[color:var(--sacred-ink)] hover:bg-[color:var(--sacred-parchment-deep)]'">Contraste</button>
            </div>
        </template>
    </div>

    {{-- Modo leitura (Foco): overlay com tema pergaminho; esconde navbar/rodapé do site via body.bible-reading-mode --}}
    <div id="bible-reading-mode-container"
         x-show="readingMode"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bible-sacred-root overflow-y-auto flex flex-col"
         :data-bible-palette="biblePalette"
         @fullscreenchange.window="fullscreenActive = !!(document.fullscreenElement || document.webkitFullscreenElement)"
         @webkitfullscreenchange.window="fullscreenActive = !!(document.fullscreenElement || document.webkitFullscreenElement)">
        <template x-if="readingMode">
            <div class="flex-1 flex flex-col min-h-full">
                {{-- Barra mínima modo leitura: sair + tela cheia --}}
                <div class="sticky top-0 z-10 flex items-center justify-between px-3 py-2 bible-sacred-header">
                    <span class="text-sm font-bold text-[color:var(--sacred-ink-muted)] truncate" x-text="bookName + ' ' + chapterNumber"></span>
                    <div class="flex items-center gap-1">
                        <button @click="toggleReadingFullscreen()" type="button" class="p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)]" :aria-pressed="fullscreenActive" aria-label="Tela cheia">
                            <x-icon name="expand" class="w-5 h-5" />
                        </button>
                        <button @click="exitReadingMode()" type="button" class="p-2 rounded-lg bg-[color:var(--sacred-accent)] text-white hover:opacity-90 font-bold text-sm" aria-label="Sair do modo leitura">Sair</button>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2 py-2 border-b border-[color:var(--sacred-edge)]/20 px-2">
                    <button @click="fontSizeDown()" type="button" class="w-9 h-9 rounded-lg bg-[color:var(--sacred-parchment-deep)] text-sm font-bold text-[color:var(--sacred-ink)]" aria-label="Diminuir fonte">A-</button>
                    <span class="text-sm font-bold text-[color:var(--sacred-ink-muted)] min-w-[3rem] flex items-center justify-center" x-text="fontSize + '%'"></span>
                    <button @click="fontSizeUp()" type="button" class="w-9 h-9 rounded-lg bg-[color:var(--sacred-parchment-deep)] text-sm font-bold text-[color:var(--sacred-ink)]" aria-label="Aumentar fonte">A+</button>
                    <div class="flex gap-1 ms-1">
                        <button type="button" @click="setPalette('classic')" class="px-2 py-1 rounded text-[10px] font-black uppercase" :class="biblePalette === 'classic' ? 'bg-[color:var(--sacred-accent)] text-white' : 'text-[color:var(--sacred-ink-muted)]'">1</button>
                        <button type="button" @click="setPalette('sepia')" class="px-2 py-1 rounded text-[10px] font-black uppercase" :class="biblePalette === 'sepia' ? 'bg-[color:var(--sacred-accent)] text-white' : 'text-[color:var(--sacred-ink-muted)]'">2</button>
                        <button type="button" @click="setPalette('contrast')" class="px-2 py-1 rounded text-[10px] font-black uppercase" :class="biblePalette === 'contrast' ? 'bg-[color:var(--sacred-accent)] text-white' : 'text-[color:var(--sacred-ink-muted)]'">3</button>
                    </div>
                </div>
                <div class="flex-1 w-full max-w-3xl mx-auto px-3 sm:px-5 py-5 sm:py-8 bible-reading-column bible-sacred-reading-column bible-reading-sheet mb-8"
                     :style="'font-size: calc(1.0625rem * ' + (fontSize / 100) + ')'">
                    @if(!empty($chapterAudioUrl))
                        <div class="mb-6 p-4 rounded-xl border border-[color:var(--sacred-edge)]/30 bg-[color:var(--sacred-parchment-deep)]/35" aria-label="Ouvir capítulo em áudio">
                            <p class="text-sm font-bold text-[color:var(--sacred-ink-muted)] mb-3 flex items-center gap-2">
                                <x-icon name="volume-high" class="w-4 h-4 text-[color:var(--sacred-accent)]" />
                                Áudio deste capítulo ({{ $version->name }})
                            </p>
                            <audio controls class="w-full max-w-md" preload="metadata" src="{{ $chapterAudioUrl }}">
                                Seu navegador não suporta o elemento de áudio.
                            </audio>
                        </div>
                    @endif
                    @if(!$verses->isEmpty())
                        <div class="space-y-4 sm:space-y-5">
                            @foreach($verses as $verse)
                                <div class="bible-sacred-verse-block group/verse flex gap-2 sm:gap-4 p-3 sm:p-4 rounded-xl hover:bg-[color:var(--sacred-parchment-deep)]/55 transition-colors" id="v{{ $verse->verse_number }}">
                                    <span class="bible-sacred-verse-num">{{ $verse->verse_number }}</span>
                                    <span class="bible-reading-verse-text flex-1 min-w-0 text-[color:var(--sacred-ink)] font-serif">{{ $verse->text }}</span>
                                    <button type="button"
                                            @click="copyVerse({{ $verse->verse_number }}, @js($verse->text))"
                                            class="shrink-0 self-start p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] hover:text-[color:var(--sacred-accent)] opacity-70 sm:opacity-0 sm:group-hover/verse:opacity-100 focus:opacity-100 transition-opacity"
                                            :aria-label="'Copiar versículo {{ $verse->verse_number }}'"
                                            title="Copiar versículo">
                                        <x-icon name="link-simple" class="w-4 h-4" />
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        @if(isset($previousChapter) || isset($nextChapter))
                            <footer class="mt-10 pt-6 border-t border-[color:var(--sacred-edge)]/30 flex flex-wrap items-center justify-between gap-4">
                                @if(isset($previousChapter))
                                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $previousChapter->book->book_number, $previousChapter->chapter_number]) }}"
                                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[color:var(--sacred-edge)]/35 text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] font-bold text-sm transition-colors">
                                        <x-icon name="chevron-left" class="w-5 h-5" />
                                        <span class="hidden sm:inline">Cap. {{ $previousChapter->chapter_number }}</span>
                                    </a>
                                @else
                                    <span aria-hidden="true"></span>
                                @endif
                                <span class="text-xs font-bold text-[color:var(--sacred-ink-muted)] uppercase tracking-wider">{{ $book->name }} {{ $chapter->chapter_number }}</span>
                                @if(isset($nextChapter))
                                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $nextChapter->book->book_number, $nextChapter->chapter_number]) }}"
                                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[color:var(--sacred-accent)] text-white font-bold text-sm hover:opacity-90 transition-opacity">
                                        <span class="hidden sm:inline">Cap. {{ $nextChapter->chapter_number }}</span>
                                        <x-icon name="chevron-right" class="w-5 h-5" />
                                    </a>
                                @else
                                    <span aria-hidden="true"></span>
                                @endif
                            </footer>
                        @endif
                    @endif
                </div>
            </div>
        </template>
    </div>

    {{-- Main content (hidden in reading mode; shown when not reading) --}}
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-10"
         x-show="!readingMode"
         x-transition>
        <div class="bible-reading-column bible-sacred-reading-column mx-auto bible-reading-sheet px-4 sm:px-8 py-6 sm:py-10"
             :style="'font-size: calc(1.0625rem * ' + (fontSize / 100) + ')'">

            {{-- Compare mode: choose version then show 1-1, 2-2 --}}
            <template x-if="compareMode && !compareData && !compareLoading">
                <div class="mb-8 p-6 rounded-2xl border border-[color:var(--sacred-edge)]/35 bg-[color:var(--sacred-parchment-deep)]/40 text-center">
                    <p class="text-sm font-bold text-[color:var(--sacred-ink)] mb-3">Escolha a segunda versão para comparar (texto vindo do banco de dados)</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach($versions as $v)
                            @if($v->id !== $version->id)
                                <button type="button"
                                        @click="compareVersion2 = '{{ $v->abbreviation }}'; fetchCompare()"
                                        class="px-4 py-2 rounded-xl bg-[color:var(--sacred-parchment)] border border-[color:var(--sacred-edge)]/30 text-sm font-bold text-[color:var(--sacred-ink)] hover:border-[color:var(--sacred-accent)]/50 transition-colors"
                                        x-text="'{{ $v->abbreviation }}'"></button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </template>

            <template x-if="compareMode && compareLoading">
                <div class="flex flex-col items-center justify-center py-12">
                    <svg class="animate-spin h-10 w-10 text-[color:var(--sacred-accent)] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span class="text-sm font-bold text-[color:var(--sacred-ink-muted)]">Carregando comparação...</span>
                </div>
            </template>

            {{-- Compare view: 1-1, 2-2, 3-3 --}}
            <template x-if="compareMode && compareData && !compareLoading">
                <div class="space-y-6">
                    <template x-for="(v1, idx) in compareData.v1.verses" :key="idx">
                        <div class="border border-[color:var(--sacred-edge)]/30 rounded-xl overflow-hidden bg-[color:var(--sacred-parchment)] shadow-md">
                            <div class="px-4 py-2 bg-[color:var(--sacred-parchment-deep)] border-b border-[color:var(--sacred-edge)]/25">
                                <span class="bible-sacred-verse-num" x-text="v1.verse_number"></span>
                            </div>
                            <div class="p-4 space-y-3">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-wider text-[color:var(--sacred-accent)]" x-text="compareData.v1.abbreviation"></span>
                                    <p class="text-[color:var(--sacred-ink)] font-serif leading-relaxed mt-0.5" x-text="v1.text"></p>
                                </div>
                                <div class="pl-4 border-l-2 border-[color:var(--sacred-gold)]/50">
                                    <span class="text-[10px] font-black uppercase tracking-wider text-[color:var(--sacred-gold)]" x-text="compareData.v2.abbreviation"></span>
                                    <p class="text-[color:var(--sacred-ink-muted)] font-serif leading-relaxed mt-0.5 text-[0.95em]"
                                       x-text="(compareData.v2.verses[idx] || {}).text || '—'"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- Chapter audio (when available) --}}
            @if(!empty($chapterAudioUrl))
                <div x-show="!compareMode || !compareData" class="mb-8 p-4 rounded-xl border border-[color:var(--sacred-edge)]/25 bg-[color:var(--sacred-parchment-deep)]/35" aria-label="Ouvir capítulo em áudio">
                    <p class="text-sm font-bold text-[color:var(--sacred-ink-muted)] mb-3 flex items-center gap-2">
                        <x-icon name="volume-high" class="w-4 h-4 text-[color:var(--sacred-accent)]" />
                        Áudio deste capítulo ({{ $version->name }})
                    </p>
                    <audio controls class="w-full max-w-md" preload="metadata" src="{{ $chapterAudioUrl }}">
                        Seu navegador não suporta o elemento de áudio.
                    </audio>
                </div>
            @endif

            {{-- Normal reading (or when not compare) --}}
            @if($verses->isEmpty())
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/25 flex items-center justify-center">
                        <x-icon name="triangle-exclamation" class="w-8 h-8 text-[color:var(--sacred-ink-muted)]" />
                    </div>
                    <p class="text-[color:var(--sacred-ink-muted)]">Este capítulo ainda não foi importado ou não está disponível nesta versão.</p>
                </div>
            @else
                <div x-show="!compareMode || !compareData" class="space-y-4 sm:space-y-5">
                    @foreach($verses as $verse)
                        <div class="bible-sacred-verse-block group/verse flex gap-2 sm:gap-4 p-3 sm:p-4 rounded-xl hover:bg-[color:var(--sacred-parchment-deep)]/45 transition-colors" id="v{{ $verse->verse_number }}">
                            <span class="bible-sacred-verse-num">{{ $verse->verse_number }}</span>
                            <p class="bible-reading-verse-text flex-1 min-w-0 text-[color:var(--sacred-ink)] font-serif pt-0.5">
                                {{ $verse->text }}
                            </p>
                            <button type="button"
                                    @click="copyVerse({{ $verse->verse_number }}, @js($verse->text))"
                                    class="shrink-0 self-start p-2 rounded-lg text-[color:var(--sacred-ink-muted)] hover:bg-[color:var(--sacred-parchment-deep)] hover:text-[color:var(--sacred-accent)] opacity-70 sm:opacity-0 sm:group-hover/verse:opacity-100 focus:opacity-100 transition-opacity"
                                    title="Copiar versículo">
                                <x-icon name="link-simple" class="w-4 h-4" />
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    {{-- Footer prev/next --}}
    @if($verses->isNotEmpty())
        <footer class="fixed bottom-0 left-0 right-0 z-30 bible-sacred-header safe-area-pb"
                x-show="!readingMode"
                x-transition>
            <div class="max-w-3xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
                @if($previousChapter)
                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $previousChapter->book->book_number, $previousChapter->chapter_number]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[color:var(--sacred-parchment-deep)] text-[color:var(--sacred-ink)] font-bold text-sm border border-[color:var(--sacred-edge)]/25 hover:opacity-90 transition-opacity">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                        <span class="hidden sm:inline">Cap. {{ $previousChapter->chapter_number }}</span>
                    </a>
                @else
                    <span aria-hidden="true"></span>
                @endif
                <span class="text-xs font-bold text-[color:var(--sacred-ink-muted)] uppercase tracking-wider">{{ $book->name }} {{ $chapter->chapter_number }}</span>
                @if($nextChapter)
                    <a href="{{ route('bible.public.chapter', [$version->abbreviation, $nextChapter->book->book_number, $nextChapter->chapter_number]) }}"
                       class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[color:var(--sacred-accent)] text-white font-bold text-sm hover:opacity-90 transition-opacity">
                        <span class="hidden sm:inline">Cap. {{ $nextChapter->chapter_number }}</span>
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </a>
                @else
                    <span aria-hidden="true"></span>
                @endif
            </div>
        </footer>
    @endif

    {{-- Modal Livros (estrutura alinhada a Flowbite: backdrop + painel, foco Escape) --}}
    <div x-show="booksOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto overflow-x-hidden"
         role="dialog"
         aria-modal="true"
         aria-labelledby="bible-books-title"
         @books-open.window="booksOpen = true">
        <div class="flex min-h-[calc(100%-1rem)] items-center justify-center p-4">
            <div x-show="booksOpen"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/70 backdrop-blur-sm"
                 @click="booksOpen = false"
                 aria-hidden="true"></div>
            <div x-show="booksOpen"
                 x-transition
                 @click.stop
                 class="relative bible-sacred-paper rounded-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden shadow-2xl border border-[color:var(--sacred-edge)]/30">
                <div class="p-4 sm:p-6 border-b border-[color:var(--sacred-edge)]/25 flex items-center justify-between">
                    <h2 id="bible-books-title" class="text-lg font-bold text-[color:var(--sacred-ink)]">Livros</h2>
                    <button @click="booksOpen = false" type="button" class="p-2 rounded-lg hover:bg-[color:var(--sacred-parchment-deep)] text-[color:var(--sacred-ink-muted)] ms-auto" aria-label="Fechar lista de livros">
                        <x-icon name="xmark" class="w-5 h-5" />
                    </button>
                </div>
                <div class="p-4 sm:p-6 overflow-y-auto max-h-[70vh] space-y-6">
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-[color:var(--sacred-accent)] mb-3">Antigo Testamento</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($oldTestament ?? [] as $b)
                                <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                                   @click="booksOpen = false"
                                   class="p-3 rounded-xl bg-[color:var(--sacred-parchment-deep)]/50 border border-[color:var(--sacred-edge)]/25 text-sm font-bold text-[color:var(--sacred-ink)] hover:border-[color:var(--sacred-accent)]/45 transition-colors text-center">
                                    {{ $b->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-[color:var(--sacred-gold)] mb-3">Novo Testamento</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            @foreach($newTestament ?? [] as $b)
                                <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                                   @click="booksOpen = false"
                                   class="p-3 rounded-xl bg-[color:var(--sacred-parchment-deep)]/50 border border-[color:var(--sacred-edge)]/25 text-sm font-bold text-[color:var(--sacred-ink)] hover:border-[color:var(--sacred-gold)]/50 transition-colors text-center">
                                    {{ $b->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Drawer Busca (padrão Flowbite drawer: painel inferior) --}}
    <div x-show="searchOpen"
         x-cloak
         class="fixed inset-0 z-50 overflow-hidden"
         role="dialog"
         aria-modal="true"
         aria-labelledby="bible-search-drawer-title">
        <div x-show="searchOpen" x-transition class="absolute inset-0 bg-gray-900/50 dark:bg-gray-900/70 backdrop-blur-sm" @click="searchOpen = false" aria-hidden="true"></div>
        <div x-show="searchOpen"
             @click.stop
             x-transition:enter="transform transition ease-out duration-200"
             x-transition:enter-start="translate-y-full"
             x-transition:enter-end="translate-y-0"
             class="absolute bottom-0 left-0 right-0 bible-sacred-paper rounded-t-2xl max-h-[85vh] flex flex-col border-t-2 border-[color:var(--sacred-edge)]/40 shadow-2xl">
            <div class="mx-auto mt-3 h-1 w-10 rounded-full bg-[color:var(--sacred-edge)]/40 shrink-0" aria-hidden="true"></div>
            <div class="p-4 border-b border-[color:var(--sacred-edge)]/20 flex items-center gap-3">
                <h2 id="bible-search-drawer-title" class="sr-only">Buscar na Bíblia</h2>
                <label for="bible-search-input" class="sr-only">Buscar na Bíblia</label>
                <input id="bible-search-input"
                       type="search"
                       x-model="searchQuery"
                       @input.debounce.300ms="doSearch()"
                       placeholder="Referência (ex: João 3:16) ou palavra..."
                       class="flex-1 px-4 py-3 rounded-xl bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/20 text-[color:var(--sacred-ink)] placeholder:text-[color:var(--sacred-ink-muted)] focus:ring-2 focus:ring-[color:var(--sacred-accent)]/40">
                <button @click="searchOpen = false" type="button" class="p-2 rounded-lg hover:bg-[color:var(--sacred-parchment-deep)] text-[color:var(--sacred-ink-muted)]">
                    <x-icon name="xmark" class="w-5 h-5" />
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <template x-if="searchLoading">
                    <div class="flex items-center justify-center py-12">
                        <svg class="animate-spin h-8 w-8 text-[color:var(--sacred-accent)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </template>
                <template x-if="!searchLoading && searchResults && Array.isArray(searchResults)">
                    <ul class="space-y-2">
                        <template x-for="(item, i) in searchResults" :key="i">
                            <li>
                                <a :href="searchResultHref(item)"
                                   @click="searchOpen = false"
                                   class="block p-4 rounded-xl bg-[color:var(--sacred-parchment-deep)]/40 border border-[color:var(--sacred-edge)]/20 hover:border-[color:var(--sacred-accent)]/40 transition-colors">
                                    <span class="text-xs font-bold text-[color:var(--sacred-accent)]" x-text="item.reference || ''"></span>
                                    <p class="text-sm text-[color:var(--sacred-ink)] mt-1 line-clamp-2" x-text="item.text || ''"></p>
                                </a>
                            </li>
                        </template>
                    </ul>
                </template>
                <template x-if="!searchLoading && searchResults && searchResults.type === 'exact'">
                    <div class="space-y-2">
                        <p class="text-sm font-bold text-[color:var(--sacred-accent)]" x-text="searchResults.reference"></p>
                        <template x-for="(v, i) in (searchResults.verses || [])" :key="i">
                            <a :href="exactVerseHref(v.verse_number, searchResults.book_number || bookNumber, searchResults.chapter_number || chapterNumber)"
                               @click="searchOpen = false"
                               class="block p-4 rounded-xl bg-[color:var(--sacred-parchment-deep)]/40 border border-[color:var(--sacred-edge)]/20 hover:border-[color:var(--sacred-accent)]/40 transition-colors">
                                <span class="bible-sacred-verse-num mr-2" x-text="v.verse_number"></span>
                                <span class="text-[color:var(--sacred-ink)] font-serif" x-text="v.text"></span>
                            </a>
                        </template>
                    </div>
                </template>
                <template x-if="!searchLoading && searchQuery.length >= 2 && searchResults && !searchResults.length && searchResults.type !== 'exact'">
                    <p class="text-center text-[color:var(--sacred-ink-muted)] py-8">Nenhum resultado encontrado.</p>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
