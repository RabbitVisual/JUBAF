@extends('paineldiretoria::components.layouts.app')

@section('title', 'Novo slide do carrossel')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    @include('paineldiretoria::partials.carousel-subnav', ['active' => 'nova'])

    <header class="overflow-hidden rounded-3xl border border-pink-100/90 bg-gradient-to-br from-pink-50/90 via-white to-white p-6 shadow-sm dark:border-pink-900/25 dark:from-pink-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-pink-600 dark:text-pink-400">Passo a passo · 4 etapas</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Criar novo slide</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Preencha o conteúdo na ordem sugerida: primeiro o texto, depois a imagem, o botão opcional e por fim a visibilidade. Em telas grandes, a coluna à direita mostra uma pré-visualização aproximada do destaque na home.
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route('diretoria.dashboard') }}" class="transition hover:text-pink-600 dark:hover:text-pink-400">Diretoria</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <a href="{{ route('diretoria.carousel.index') }}" class="transition hover:text-pink-600 dark:hover:text-pink-400">Carrossel</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Novo slide</span>
                </nav>
            </div>
            <div class="shrink-0">
                <a href="{{ route('diretoria.carousel.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                    <x-icon name="arrow-left" class="h-4 w-4" style="solid" />
                    Voltar à lista
                </a>
            </div>
        </div>
    </header>

    <form id="carousel-slide-form" action="{{ route('diretoria.carousel.store') }}" method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
            <div class="flex flex-col gap-8 lg:col-span-7 xl:col-span-8">
                @include('paineldiretoria::carousel.partials.form-inner', ['carousel' => null, 'isEdit' => false])
                @include('paineldiretoria::carousel.partials.form-footer', ['submitLabel' => 'Criar slide'])
            </div>
            <div class="lg:col-span-5 xl:col-span-4">
                @include('paineldiretoria::carousel.partials.live-preview')
            </div>
        </div>
    </form>
</div>
@push('scripts')
@include('paineldiretoria::carousel.partials.form-scripts')
@endpush
@endsection
