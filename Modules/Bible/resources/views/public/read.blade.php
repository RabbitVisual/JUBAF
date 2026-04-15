@extends('layouts.bible-public-homepage')

@section('title', 'Bíblia ' . $version->abbreviation . ' – Livros')

@push('head')
    @include('bible::public.partials.sacred-reader-theme')
@endpush

@section('bible_public_content')
<div class="bible-sacred-root bible-public-container min-h-screen pb-24">
    <header class="sticky top-0 z-30 bible-sacred-header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('bible.public.index') }}"
                   class="flex items-center gap-2 text-[color:var(--sacred-ink-muted)] hover:text-[color:var(--sacred-ink)] transition-colors shrink-0">
                    <span class="w-9 h-9 rounded-full bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/30 flex items-center justify-center">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </span>
                    <span class="hidden sm:inline text-sm font-bold">Início</span>
                </a>
                <div class="flex-1 min-w-0 flex items-center justify-center gap-2">
                    <x-icon name="book-bible" class="w-6 h-6 text-[color:var(--sacred-accent)] shrink-0" />
                    <h1 class="text-lg sm:text-xl font-black text-[color:var(--sacred-ink)] truncate">Bíblia {{ $version->abbreviation }}</h1>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('bible.public.interlinear') }}" class="w-9 h-9 rounded-full bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/30 flex items-center justify-center text-[color:var(--sacred-accent)] hover:opacity-90 transition-opacity"
                       aria-label="Bíblia interlinear" title="Interlinear">
                        <x-icon name="book-open" class="w-4 h-4" />
                    </a>
                    <a href="{{ route('bible.public.search') }}" class="w-9 h-9 rounded-full bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/30 flex items-center justify-center text-[color:var(--sacred-accent)] hover:opacity-90 transition-opacity"
                       aria-label="Buscar na Bíblia">
                        <x-icon name="magnifying-glass" class="w-4 h-4" />
                    </a>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-[color:var(--sacred-edge)]/25">
                <label for="version-select" class="sr-only">Trocar versão</label>
                <select id="version-select"
                        onchange="window.location.href = @json(route('bible.public.read', ['versionAbbr' => '__V__'])).replace('__V__', encodeURIComponent(this.value))"
                        class="w-full min-h-11 appearance-none pl-4 pr-10 py-2.5 rounded-xl text-sm font-bold bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/35 text-[color:var(--sacred-ink)] focus:ring-2 focus:ring-[color:var(--sacred-accent)]/40 focus:outline-none">
                    @foreach($versions as $v)
                        <option value="{{ $v->abbreviation }}" {{ $v->id === $version->id ? 'selected' : '' }}>{{ $v->name }} ({{ $v->abbreviation }})</option>
                    @endforeach
                </select>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        @if(session('error'))
            <div class="mb-6 bible-sacred-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="bible-sacred-page-frame p-4 sm:p-6 mb-10">
        <div class="bible-sacred-paper p-5 sm:p-8">
            <h2 class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-[color:var(--sacred-accent)] mb-5">
                <x-icon name="book-open" class="w-4 h-4" />
                Antigo Testamento
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                @foreach($oldTestament as $b)
                    <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                       class="flex items-center justify-center p-3 sm:p-4 rounded-xl text-center text-[color:var(--sacred-ink)] font-bold text-sm sm:text-base border border-[color:var(--sacred-edge)]/25 bg-[color:var(--sacred-parchment-deep)]/50 hover:border-[color:var(--sacred-accent)]/50 hover:bg-[color:var(--sacred-parchment)] active:scale-[0.98] transition-all min-h-[3.25rem] sm:min-h-[3rem]">
                        {{ $b->name }}
                    </a>
                @endforeach
            </div>
        </div>
        </div>

        <div class="bible-sacred-page-frame p-4 sm:p-6">
        <div class="bible-sacred-paper p-5 sm:p-8">
            <h2 class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-[color:var(--sacred-gold)] mb-5">
                <x-icon name="book-open" class="w-4 h-4" />
                Novo Testamento
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 sm:gap-3">
                @foreach($newTestament as $b)
                    <a href="{{ route('bible.public.book', [$version->abbreviation, $b->book_number]) }}"
                       class="flex items-center justify-center p-3 sm:p-4 rounded-xl text-center text-[color:var(--sacred-ink)] font-bold text-sm sm:text-base border border-[color:var(--sacred-edge)]/25 bg-[color:var(--sacred-parchment-deep)]/50 hover:border-[color:var(--sacred-gold)]/55 hover:bg-[color:var(--sacred-parchment)] active:scale-[0.98] transition-all min-h-[3.25rem] sm:min-h-[3rem]">
                        {{ $b->name }}
                    </a>
                @endforeach
            </div>
        </div>
        </div>
    </main>
</div>
@endsection
