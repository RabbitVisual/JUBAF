@extends('layouts.app')

@section('title', $aviso->titulo)

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <a href="{{ route('jovens.avisos.index') }}" class="text-violet-600 dark:text-violet-400 hover:underline">Avisos</a>
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-slate-500 dark:text-slate-400 truncate max-w-[12rem]">{{ Str::limit($aviso->titulo, 32) }}</span>
@endsection

@section('content')
<div class="max-w-3xl space-y-6 pb-8">
    <a href="{{ route('jovens.avisos.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline">
        <x-icon name="arrow-left" class="w-4 h-4" /> Voltar aos avisos
    </a>

    <article class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
        <div class="p-6 md:p-8 space-y-6">
            <header>
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">{{ $aviso->tipo_texto }}</span>
                    <span class="text-xs text-slate-500">{{ $aviso->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ $aviso->titulo }}</h1>
                @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
            </header>

            @if($aviso->descricao)
                <p class="text-lg text-slate-700 dark:text-slate-300 leading-relaxed">{{ $aviso->descricao }}</p>
            @endif

            @if($aviso->conteudo)
                <div class="prose prose-violet dark:prose-invert max-w-none">
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
                   class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-violet-700">
                    {{ $aviso->texto_botao ?? 'Saiba mais' }}
                    <x-icon name="arrow-up-right-from-square" class="w-4 h-4" />
                </a>
            @endif
        </div>
    </article>
</div>
@endsection
