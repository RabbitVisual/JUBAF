@extends('layouts.bible-public-homepage')

@section('title', $book->name . ' – Bíblia ' . $version->abbreviation)

@push('head')
    @include('bible::public.partials.sacred-reader-theme')
@endpush

@section('bible_public_content')
<div class="bible-sacred-root bible-public-container min-h-screen pb-24">
    <header class="sticky top-0 z-30 bible-sacred-header">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('bible.public.read', $version->abbreviation) }}"
                   class="flex items-center gap-2 text-[color:var(--sacred-ink-muted)] hover:text-[color:var(--sacred-ink)] transition-colors shrink-0">
                    <span class="w-9 h-9 rounded-full bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/30 flex items-center justify-center">
                        <x-icon name="chevron-left" class="w-5 h-5" />
                    </span>
                    <span class="hidden sm:inline text-sm font-bold">Todos os livros</span>
                </a>
                <h1 class="flex-1 min-w-0 text-lg sm:text-xl font-black text-[color:var(--sacred-ink)] text-center truncate px-2">{{ $book->name }}</h1>
                <div class="w-9 h-9 shrink-0" aria-hidden="true"></div>
            </div>
            <p class="text-center text-xs text-[color:var(--sacred-ink-muted)] mt-2">
                {{ $book->testament === 'old' ? 'Antigo Testamento' : 'Novo Testamento' }} · {{ $chapters->count() }} capítulos
            </p>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-10">
        @if(session('error'))
            <div class="mb-6 bible-sacred-alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif
        @if($chapters->isEmpty())
            <div class="bible-sacred-paper text-center py-16 px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[color:var(--sacred-parchment-deep)] flex items-center justify-center border border-[color:var(--sacred-edge)]/30">
                    <x-icon name="triangle-exclamation" class="w-8 h-8 text-[color:var(--sacred-ink-muted)]" />
                </div>
                <p class="text-[color:var(--sacred-ink-muted)]">Nenhum capítulo disponível para este livro nesta versão.</p>
            </div>
        @else
            <div class="bible-sacred-page-frame p-4 sm:p-5">
            <div class="bible-sacred-paper p-4 sm:p-6">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[color:var(--sacred-ink-muted)] mb-5 text-center leading-relaxed max-w-md mx-auto">Escolha o capítulo. No leitor, use <strong class="text-[color:var(--sacred-accent)]">Foco</strong> para ler sem o menu do site.</p>
                <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10 gap-2.5 sm:gap-3">
                    @foreach($chapters as $ch)
                        <a href="{{ route('bible.public.chapter', [$version->abbreviation, $book->book_number, $ch->chapter_number]) }}"
                           class="min-h-[2.85rem] sm:aspect-square flex items-center justify-center rounded-xl border-2 border-[color:var(--sacred-edge)]/28 bg-[color:var(--sacred-parchment-deep)]/45 hover:border-[color:var(--sacred-accent)]/55 hover:shadow-md active:scale-[0.97] transition-all group">
                            <span class="bible-sacred-chapter-pill group-hover:scale-105 transition-transform">{{ $ch->chapter_number }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            </div>
        @endif
    </main>
</div>
@endsection
