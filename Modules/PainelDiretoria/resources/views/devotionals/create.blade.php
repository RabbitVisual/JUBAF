@extends($layout)

@section('title', 'Novo devocional')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
        @include('paineldiretoria::partials.devotionals-subnav', ['active' => 'nova'])

        <header
            class="overflow-hidden rounded-3xl border border-amber-200/80 bg-gradient-to-br from-amber-50/90 via-white to-orange-50/30 p-6 shadow-sm dark:border-amber-900/30 dark:from-amber-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-amber-700 dark:text-amber-400">Passo a
                        passo · 5 etapas</p>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Novo
                        devocional</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                        Preencha identidade, leitura bíblica (com assistente ligado ao módulo Bíblia), reflexão, mídia e
                        autoria. À direita, uma pré-visualização aproximada do cartão público.
                    </p>
                    <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500"
                        aria-label="breadcrumb">
                        <a href="{{ route('diretoria.dashboard') }}"
                            class="transition hover:text-amber-700 dark:hover:text-amber-400">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        <a href="{{ route($routePrefix . '.index') }}"
                            class="transition hover:text-amber-700 dark:hover:text-amber-400">Devocionais</a>
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
                    @include('paineldiretoria::devotionals._form', [
                        'devotional' => $devotional,
                        'users' => $users,
                        'boardMembers' => $boardMembers,
                        'routePrefix' => $routePrefix,
                    ])
                    @include('paineldiretoria::devotionals.partials.form-footer', [
                        'routePrefix' => $routePrefix,
                        'submitLabel' => 'Criar devocional',
                    ])
                </div>
                <div class="lg:col-span-5 xl:col-span-4">
                    @include('paineldiretoria::devotionals.partials.live-preview', ['devotional' => $devotional])
                </div>
            </div>
        </form>
    </div>
@endsection
