@extends('pastor.layouts.app')

@section('title', $aviso->titulo)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <a href="{{ route('pastor.avisos.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-sky-600 dark:text-sky-400 hover:underline">
        <x-icon name="arrow-left" class="w-4 h-4" /> Voltar
    </a>

    <article class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 md:p-8 shadow-sm space-y-6">
        <header>
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="text-xs font-semibold uppercase tracking-wide text-sky-600 dark:text-sky-400">{{ $aviso->tipo_texto }}</span>
                <span class="text-xs text-slate-500">{{ $aviso->created_at->translatedFormat('d M Y, H:i') }}</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $aviso->titulo }}</h1>
            @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
        </header>

        @if($aviso->descricao)
            <p class="text-slate-700 dark:text-slate-300 leading-relaxed">{{ $aviso->descricao }}</p>
        @endif

        @if($aviso->conteudo)
            <div class="prose prose-sky dark:prose-invert max-w-none">
                {!! $aviso->conteudo !!}
            </div>
        @endif

        @if($aviso->imagem)
            <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                <img src="{{ asset('storage/'.$aviso->imagem) }}" alt="" class="w-full object-cover max-h-96" loading="lazy" />
            </div>
        @endif

        @if($aviso->botao_exibir && $aviso->url_acao)
            <a href="{{ $aviso->url_acao }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-sky-700">
                {{ $aviso->texto_botao ?? 'Saiba mais' }}
                <x-icon name="arrow-up-right-from-square" class="w-4 h-4" />
            </a>
        @endif
    </article>
</div>
@endsection
