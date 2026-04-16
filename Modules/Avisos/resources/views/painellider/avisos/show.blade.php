@extends('painellider::layouts.lideres')

@section('title', $aviso->titulo)

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <a href="{{ route('lideres.avisos.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:underline">Avisos</a>
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="max-w-[12rem] truncate text-slate-500 dark:text-slate-400">{{ Str::limit($aviso->titulo, 32) }}</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="max-w-3xl space-y-6 pb-8">
    <a href="{{ route('lideres.avisos.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 hover:underline dark:text-emerald-400">
        <x-icon name="arrow-left" class="h-4 w-4" /> Voltar aos avisos
    </a>

    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="space-y-6 p-6 md:p-8">
            <header>
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">{{ $aviso->tipo_texto }}</span>
                    <span class="text-xs text-slate-500">{{ $aviso->created_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 md:text-3xl dark:text-white">{{ $aviso->titulo }}</h1>
                @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
            </header>

            @if($aviso->descricao)
                <p class="text-lg leading-relaxed text-slate-700 dark:text-slate-300">{{ $aviso->descricao }}</p>
            @endif

            @if($aviso->conteudo)
                <div class="prose prose-emerald max-w-none dark:prose-invert">
                    {!! $aviso->conteudo !!}
                </div>
            @endif

            @if($aviso->imagem)
                <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700">
                    <img src="{{ asset('storage/'.$aviso->imagem) }}" alt="" class="max-h-96 w-full object-cover" loading="lazy" />
                </div>
            @endif

            @if($aviso->botao_exibir && $aviso->url_acao)
                <a href="{{ $aviso->url_acao }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">
                    {{ $aviso->texto_botao ?? 'Saiba mais' }}
                    <x-icon name="arrow-up-right-from-square" class="h-4 w-4" />
                </a>
            @endif
        </div>
    </article>
</x-ui.lideres::page-shell>
@endsection
