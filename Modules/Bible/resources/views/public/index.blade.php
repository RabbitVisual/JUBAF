@extends('layouts.bible-public-homepage')

@section('title', 'Bíblia Online – Leia a Bíblia Sagrada')

@push('head')
    @include('bible::public.partials.sacred-reader-theme')
@endpush

@php
    $biblePublicChapterUrlTemplate = route('bible.public.chapter', [
        'versionAbbr' => '__V__',
        'bookNumber' => '__B__',
        'chapterNumber' => '__C__',
    ]);
@endphp

@section('bible_public_content')
{{-- Config em script: evita quebrar o atributo x-data com aspas do URL (http://...) --}}
<script>
    window.__biblePublicIndexConfig = { chapterUrlTemplate: @json($biblePublicChapterUrlTemplate) };
</script>
<div class="bible-sacred-root bible-public-container min-h-screen py-6 sm:py-12 px-4 sm:px-6 lg:px-10"
     x-data="biblePublicIndex">
    <div class="max-w-3xl mx-auto bible-sacred-reading-column">
        <template x-if="last && last.versionAbbr && last.book_number && last.chapter_number">
            <a :href="continueHref()"
               class="bible-sacred-paper mb-8 flex items-center gap-4 p-4 sm:p-5 hover:opacity-95 transition-all group border-s-4 border-s-[color:var(--sacred-accent)] rounded-2xl">
                <span class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gradient-to-br from-[color:var(--sacred-accent)] to-[color:var(--sacred-gold)] text-white flex items-center justify-center shadow-lg">
                    <x-icon name="book-open" class="w-6 h-6" />
                </span>
                <div class="min-w-0 flex-1">
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[color:var(--sacred-accent)]">{{ __('Continuar lendo') }}</span>
                    <p class="font-bold text-[color:var(--sacred-ink)] text-base sm:text-lg mt-0.5 leading-snug" x-text="(last.book_name || '') + ' ' + (last.chapter_number || '')"></p>
                </div>
                <x-icon name="chevron-right" class="w-5 h-5 text-[color:var(--sacred-gold)] group-hover:translate-x-0.5 transition-transform flex-shrink-0" />
            </a>
        </template>

        {{-- Hero: sem barras laterais; visual mais limpo e apelativo --}}
        <div class="bible-sacred-paper rounded-3xl p-6 sm:p-10 mb-10 sm:mb-12 text-center shadow-xl ring-1 ring-[color:var(--sacred-edge)]/20">
            <div class="relative mx-auto mb-6 flex justify-center">
                <div class="pointer-events-none absolute inset-0 -z-10 flex items-center justify-center">
                    <span class="h-32 w-32 sm:h-40 sm:w-40 rounded-full bg-gradient-to-tr from-[color:var(--sacred-gold)]/35 via-[color:var(--sacred-accent)]/15 to-transparent blur-2xl scale-125" aria-hidden="true"></span>
                </div>
                <div class="relative flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-3xl bg-gradient-to-br from-[color:var(--sacred-parchment)] via-[color:var(--sacred-parchment-mid)] to-[color:var(--sacred-parchment-deep)] p-1 shadow-lg ring-2 ring-[color:var(--sacred-gold)]/35 sm:h-28 sm:w-28 sm:p-1.5 text-[color:var(--sacred-accent)] [&_i]:text-[2.35rem] sm:[&_i]:text-[3.35rem] [&_i]:leading-none">
                    <x-icon name="book-bible" class="h-full w-full drop-shadow-sm" />
                </div>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-[2.5rem] font-black text-[color:var(--sacred-ink)] tracking-tight mb-3">
                {{ __('Bíblia Online') }}
            </h1>
            <p class="text-[color:var(--sacred-ink-muted)] text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                {{ __('Lê no telemóvel, tablet ou computador — interface pensada para quem quer mergulhar no texto, com modo foco e estudo interlinear.') }}
            </p>
            <p class="mt-3 text-xs sm:text-sm text-[color:var(--sacred-ink-muted)] max-w-lg mx-auto">
                {{ __('No capítulo, o botão') }} <strong class="text-[color:var(--sacred-accent)]">{{ __('Foco') }}</strong> {{ __('esconde o menu do site para leres com atenção.') }}
            </p>
            <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center justify-center gap-3 mt-7">
                <a href="{{ route('bible.public.search') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 min-h-12 rounded-2xl text-sm font-bold bg-[color:var(--sacred-accent)] text-white shadow-md hover:opacity-95 active:scale-[0.99] transition-all">
                    <x-icon name="magnifying-glass" class="w-4 h-4" />
                    {{ __('Buscar na Bíblia') }}
                </a>
                <a href="{{ route('bible.public.interlinear') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 min-h-12 rounded-2xl text-sm font-bold border-2 border-[color:var(--sacred-edge)]/50 text-[color:var(--sacred-ink)] bg-[color:var(--sacred-parchment-deep)]/35 hover:border-[color:var(--sacred-accent)]/60 hover:bg-[color:var(--sacred-parchment-deep)]/55 transition-all">
                    <x-icon name="book-open" class="w-4 h-4 text-[color:var(--sacred-accent)]" />
                    {{ __('Modo estudo: Interlinear') }}
                </a>
            </div>
        </div>

        <div class="space-y-4">
            <h2 class="text-[10px] font-bold uppercase tracking-[0.25em] text-[color:var(--sacred-ink-muted)] px-1 flex items-center gap-3">
                <span class="h-px flex-1 bg-gradient-to-r from-transparent to-[color:var(--sacred-edge)]/50 max-w-[3rem] sm:max-w-xs"></span>
                {{ __('Escolhe a versão') }}
                <span class="h-px flex-1 bg-gradient-to-l from-transparent to-[color:var(--sacred-edge)]/50 max-w-[3rem] sm:max-w-xs"></span>
            </h2>
            <ul class="space-y-3 sm:space-y-4">
                @foreach($versions as $v)
                    <li>
                        <a href="{{ route('bible.public.read', $v->abbreviation) }}"
                           class="bible-sacred-paper flex items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl hover:ring-2 hover:ring-[color:var(--sacred-edge)]/50 active:scale-[0.99] transition-all group min-h-[4.25rem]">
                            <div class="flex items-center gap-4 min-w-0">
                                <span class="flex-shrink-0 w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center text-white font-bold text-sm shadow-md ring-1 ring-white/25"
                                      style="background: linear-gradient(145deg, var(--sacred-accent), var(--sacred-gold));">
                                    {{ strtoupper(substr($v->abbreviation, 0, 2)) }}
                                </span>
                                <div class="min-w-0">
                                    <span class="font-bold text-[color:var(--sacred-ink)] block text-base sm:text-lg leading-snug">{{ $v->name }}</span>
                                    <span class="text-sm text-[color:var(--sacred-ink-muted)]">{{ $v->abbreviation }}</span>
                                </div>
                            </div>
                            <x-icon name="chevron-right" class="w-5 h-5 sm:w-6 sm:h-6 text-[color:var(--sacred-gold)] opacity-70 group-hover:opacity-100 flex-shrink-0" />
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="mt-10 text-center text-xs sm:text-sm text-[color:var(--sacred-ink-muted)] leading-relaxed max-w-md mx-auto">
            {{ __('Leitura gratuita. Textos conforme as versões no sistema. Não precisas de conta.') }}
        </p>
    </div>
</div>
@endsection
