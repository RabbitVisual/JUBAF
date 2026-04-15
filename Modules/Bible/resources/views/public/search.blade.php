@extends('layouts.bible-public-homepage')

@section('title', 'Buscar na Bíblia – Bíblia Online')

@push('head')
    @include('bible::public.partials.sacred-reader-theme')
    <script>
        document.addEventListener('alpine:init', () => {
            window.Alpine.data('bibleSearch', () => {
                const c = window.__bibleSearchConfig;
                let versionAbbr = '';
                try {
                    versionAbbr = localStorage.getItem('bible_public_version') || c.defaultVersionAbbr;
                } catch (e) {
                    versionAbbr = c.defaultVersionAbbr;
                }
                return {
                    searchQuery: '',
                    searchResults: null,
                    searchLoading: false,
                    searchDebounce: null,
                    versionAbbr,
                    apiBase: c.apiBase,
                    chapterUrlTemplate: c.chapterUrlTemplate,
                    persistVersion() {
                        try {
                            localStorage.setItem('bible_public_version', this.versionAbbr);
                        } catch (e) {}
                    },
                    chapterHrefFromItem(item) {
                        if (!this.versionAbbr || !item.book_number || !item.chapter_number) {
                            return '#';
                        }
                        let url = this.chapterUrlTemplate
                            .replace('__V__', encodeURIComponent(this.versionAbbr))
                            .replace('__B__', String(item.book_number))
                            .replace('__C__', String(item.chapter_number));
                        if (item.verse_number) {
                            url += '#v' + item.verse_number;
                        }
                        return url;
                    },
                    exactVerseHref(v, bn, cn) {
                        if (!this.versionAbbr) {
                            return '#';
                        }
                        let url = this.chapterUrlTemplate
                            .replace('__V__', encodeURIComponent(this.versionAbbr))
                            .replace('__B__', String(bn))
                            .replace('__C__', String(cn));
                        url += '#v' + v.verse_number;
                        return url;
                    },
                    doSearch() {
                        const self = this;
                        clearTimeout(self.searchDebounce);
                        if (self.searchQuery.trim().length < 2) {
                            self.searchResults = null;
                            return;
                        }
                        self.searchDebounce = setTimeout(() => {
                            self.searchLoading = true;
                            self.searchResults = null;
                            const q = encodeURIComponent(self.searchQuery.trim());
                            const v = self.versionAbbr ? '&version=' + encodeURIComponent(self.versionAbbr) : '';
                            fetch(self.apiBase + '/search?q=' + q + v)
                                .then((r) => r.json())
                                .then((json) => {
                                    if (json.data) {
                                        self.searchResults = json.data;
                                    }
                                })
                                .catch((e) => console.error(e))
                                .finally(() => {
                                    self.searchLoading = false;
                                });
                        }, 300);
                    },
                };
            });
        });
    </script>
@endpush

@php
    $bibleSearchConfig = [
        'apiBase' => $apiBase,
        'defaultVersionAbbr' => $versions->isNotEmpty() ? $versions->first()->abbreviation : '',
        'versions' => $versions->map(fn($v) => ['abbreviation' => $v->abbreviation, 'name' => $v->name])->values()->toArray(),
        'chapterUrlTemplate' => route('bible.public.chapter', [
            'versionAbbr' => '__V__',
            'bookNumber' => '__B__',
            'chapterNumber' => '__C__',
        ]),
    ];
@endphp

@section('bible_public_content')
<script>window.__bibleSearchConfig = @json($bibleSearchConfig);</script>
<div class="bible-sacred-root bible-public-container min-h-screen pb-24"
     x-data="bibleSearch()">
    <header class="sticky top-0 z-30 bible-sacred-header">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('bible.public.index') }}"
                   class="flex items-center gap-2 text-[color:var(--sacred-ink-muted)] hover:text-[color:var(--sacred-ink)] transition-colors shrink-0">
                    <span class="w-9 h-9 rounded-full bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/30 flex items-center justify-center">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </span>
                    <span class="hidden sm:inline text-sm font-bold">Início</span>
                </a>
                <h1 class="text-lg sm:text-xl font-black text-[color:var(--sacred-ink)]">Buscar na Bíblia</h1>
                <div class="w-9 h-9 shrink-0" aria-hidden="true"></div>
            </div>
            <div class="mt-4 bible-sacred-paper p-3 sm:p-4">
                <label for="search-input" class="sr-only">Buscar por referência ou texto</label>
                <div class="relative">
                    <x-icon name="magnifying-glass" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-[color:var(--sacred-ink-muted)]" />
                    <input id="search-input"
                           type="search"
                           x-model="searchQuery"
                           @input.debounce.300ms="doSearch()"
                           placeholder="Ex.: João 3:16 ou uma palavra…"
                           class="w-full pl-10 pr-4 py-3 rounded-xl bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/25 text-[color:var(--sacred-ink)] placeholder:text-[color:var(--sacred-ink-muted)] focus:ring-2 focus:ring-[color:var(--sacred-accent)]/35"
                           autofocus>
                </div>
                <div class="mt-3">
                    <label for="search-version" class="sr-only">Versão para abrir os resultados</label>
                    <select id="search-version"
                            x-model="versionAbbr"
                            @change="persistVersion()"
                            class="w-full appearance-none pl-4 pr-10 py-2.5 rounded-xl text-sm font-bold bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/25 text-[color:var(--sacred-ink)]">
                        <option value="">Escolha a versão nos links</option>
                        @foreach($versions as $v)
                            <option value="{{ $v->abbreviation }}">{{ $v->name }} ({{ $v->abbreviation }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-6 bible-sacred-reading-column">
        <template x-if="searchLoading">
            <div class="flex items-center justify-center py-12">
                <svg class="animate-spin h-8 w-8 text-[color:var(--sacred-accent)]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        </template>
        <template x-if="!searchLoading && searchResults && Array.isArray(searchResults)">
            <ul class="space-y-3">
                <template x-for="(item, i) in searchResults" :key="i">
                    <li>
                        <a :href="versionAbbr ? chapterHrefFromItem(item) : '#'"
                           :class="versionAbbr ? '' : 'pointer-events-none opacity-50'"
                           class="bible-sacred-paper block p-4 hover:ring-2 hover:ring-[color:var(--sacred-edge)]/45 transition-all">
                            <span class="text-xs font-bold text-[color:var(--sacred-accent)]" x-text="item.reference || ''"></span>
                            <p class="text-sm text-[color:var(--sacred-ink)] mt-1 line-clamp-3" x-text="item.text || ''"></p>
                        </a>
                    </li>
                </template>
            </ul>
        </template>
        <template x-if="!searchLoading && searchResults && searchResults.type === 'exact'">
            <div class="bible-sacred-paper p-4 sm:p-6 space-y-3">
                <p class="text-sm font-bold text-[color:var(--sacred-accent)]" x-text="searchResults.reference"></p>
                <template x-for="(v, i) in (searchResults.verses || [])" :key="i">
                    <a :href="versionAbbr ? exactVerseHref(v, searchResults.book_number, searchResults.chapter_number) : '#'"
                       :class="versionAbbr ? '' : 'pointer-events-none opacity-50'"
                       class="block p-3 rounded-xl bg-[color:var(--sacred-parchment-deep)]/50 border border-[color:var(--sacred-edge)]/20 hover:border-[color:var(--sacred-accent)]/40 transition-colors">
                        <span class="bible-sacred-verse-num mr-2 align-top" x-text="v.verse_number"></span>
                        <span class="text-[color:var(--sacred-ink)] font-serif" x-text="v.text"></span>
                    </a>
                </template>
            </div>
        </template>
        <template x-if="!searchLoading && searchQuery.length >= 2 && searchResults && !Array.isArray(searchResults) && searchResults.type !== 'exact'">
            <p class="text-center text-[color:var(--sacred-ink-muted)] py-8 bible-sacred-paper p-6">Nenhum resultado encontrado no texto importado.</p>
        </template>
        <template x-if="!searchLoading && searchQuery.length < 2 && !searchResults">
            <div class="bible-sacred-paper p-6 sm:p-8 text-center">
                <p class="text-[color:var(--sacred-ink-muted)] text-sm leading-relaxed">
                    Digite ao menos <strong class="text-[color:var(--sacred-ink)]">2 caracteres</strong> para buscar por referência (ex.: João 3:16) ou por palavra no texto das versões disponíveis no site.
                </p>
            </div>
        </template>
    </main>
</div>
@endsection
