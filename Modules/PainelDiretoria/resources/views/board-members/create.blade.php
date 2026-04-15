@extends('paineldiretoria::components.layouts.app')

@section('title', 'Novo membro da diretoria')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('paineldiretoria::partials.board-members-subnav', ['active' => 'nova'])

        <header
            class="overflow-hidden rounded-3xl border border-indigo-200/80 bg-gradient-to-br from-indigo-50/90 via-white to-violet-50/30 p-6 shadow-sm dark:border-indigo-900/35 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-indigo-700 dark:text-indigo-400">Passo a
                        passo · 4 etapas</p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Novo membro
                        da diretoria</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Defina o perfil público, foto, ordem na página <strong class="font-semibold text-gray-800 dark:text-slate-200">/equipe/diretoria</strong> e dados opcionais. A coluna à direita mostra uma pré-visualização do cartão.
                    </p>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500"
                        aria-label="breadcrumb">
                        <a href="{{ route('diretoria.dashboard') }}"
                            class="transition hover:text-indigo-700 dark:hover:text-indigo-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <a href="{{ route($routePrefix . '.index') }}"
                            class="transition hover:text-indigo-700 dark:hover:text-indigo-400">Membros</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <span class="font-medium text-gray-800 dark:text-slate-300">Novo</span>
                    </nav>
                </div>
                <div class="shrink-0">
                    <a href="{{ route($routePrefix . '.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                        <x-icon name="arrow-left" class="h-4 w-4" style="solid" />
                        Voltar à lista
                    </a>
                </div>
            </div>
        </header>

        <form action="{{ route($routePrefix . '.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                <div class="flex flex-col gap-8 lg:col-span-7 xl:col-span-8">
                    @include('paineldiretoria::board-members._form', [
                        'boardMember' => $boardMember,
                        'users' => $users,
                        'routePrefix' => $routePrefix,
                    ])
                    @include('paineldiretoria::board-members.partials.form-footer', [
                        'routePrefix' => $routePrefix,
                        'submitLabel' => 'Criar membro',
                    ])
                </div>
                <div class="lg:col-span-5 xl:col-span-4">
                    @include('paineldiretoria::board-members.partials.live-preview', ['boardMember' => $boardMember])
                </div>
            </div>
        </form>
    </div>
@endsection
