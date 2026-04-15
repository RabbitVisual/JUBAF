@extends('layouts.bible-public-homepage')

@section('title', 'Bíblia Online')

@push('head')
    @include('bible::public.partials.sacred-reader-theme')
@endpush

@section('bible_public_content')
<div class="bible-sacred-root bible-public-container min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center bible-sacred-paper p-8 sm:p-10">
        <div class="inline-flex items-center gap-3 mb-6">
            <span class="bible-sacred-spine h-12" aria-hidden="true"></span>
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center bg-[color:var(--sacred-parchment-deep)] border border-[color:var(--sacred-edge)]/35 text-[color:var(--sacred-accent)]">
                <x-icon name="book" class="w-8 h-8" />
            </div>
            <span class="bible-sacred-spine h-12" aria-hidden="true"></span>
        </div>
        <h1 class="text-2xl font-black text-[color:var(--sacred-ink)] mb-3">Bíblia Online</h1>
        <p class="text-[color:var(--sacred-ink-muted)] mb-8 leading-relaxed">
            Nenhuma versão da Bíblia está publicada no momento. Assim que os textos forem importados no painel, eles aparecerão aqui para leitura pública.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[color:var(--sacred-accent)] text-white font-bold hover:opacity-90 transition-opacity w-full sm:w-auto">
                <x-icon name="arrow-left" class="w-5 h-5" />
                Voltar ao início
            </a>
            <a href="{{ route('bible.public.interlinear') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border-2 border-[color:var(--sacred-edge)]/40 text-[color:var(--sacred-ink)] font-bold hover:border-[color:var(--sacred-accent)]/50 transition-colors w-full sm:w-auto">
                <x-icon name="book-open" class="w-5 h-5 text-[color:var(--sacred-accent)]" />
                Estudo interlinear
            </a>
        </div>
    </div>
</div>
@endsection
