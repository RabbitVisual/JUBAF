@extends($layout)

@section('title', 'Detalhes do Módulo: ' . $module['name'])

@section('content')
@php
    $dashRoute = user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Módulo · Ficha técnica</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">{{ $module['name'] }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    {{ $module['description'] ?? 'Sem descrição disponível.' }}
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route($dashRoute) }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Admin</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <a href="{{ route($routePrefix.'.index') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Módulos</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Detalhes técnicos</span>
                </nav>
            </div>
            <div class="shrink-0">
                <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                    <x-icon name="arrow-left" class="h-5 w-5" style="solid" />
                    Voltar à lista
                </a>
            </div>
        </div>
    </header>

    <div class="flex flex-col gap-6 rounded-3xl border p-6 shadow-sm md:flex-row md:items-center md:p-8 {{ $module['enabled'] ? 'border-emerald-200/80 bg-emerald-50/50 dark:border-emerald-900/30 dark:bg-emerald-950/20' : 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-800/80' }}">
        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl shadow-lg {{ $module['enabled'] ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-slate-400 to-slate-500' }}">
            <x-icon name="{{ $module['enabled'] ? 'check-circle' : 'ban' }}" class="h-8 w-8 text-white" style="duotone" />
        </div>
        <div class="min-w-0 flex-1">
            <div class="flex flex-col justify-between gap-4 md:flex-row md:items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $module['name'] }}</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Alias: <code class="rounded bg-white/80 px-1.5 py-0.5 font-mono text-xs dark:bg-slate-900/80">{{ $module['alias'] }}</code></p>
                </div>
                <div>
                    @if ($module['enabled'])
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-100 bg-white px-4 py-2 text-sm font-bold text-emerald-600 shadow-sm dark:border-emerald-900/30 dark:bg-slate-900 dark:text-emerald-400">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Módulo ativo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-500 shadow-sm dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                            <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                            Módulo inativo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (! empty($adminShortcuts))
        <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
            <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                    <x-icon name="arrow-up-right-from-square" style="duotone" class="h-5 w-5 text-indigo-500" />
                    Atalhos no painel admin
                </h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Links rápidos para ecrãs deste módulo</p>
            </div>
            <div class="flex flex-wrap gap-3 p-6 md:p-8">
                @foreach ($adminShortcuts as $link)
                    <a href="{{ $link['url'] }}" class="inline-flex items-center gap-2 rounded-xl border border-indigo-100 bg-indigo-50 px-4 py-2.5 text-sm font-semibold text-indigo-700 transition-colors hover:bg-indigo-100 dark:border-indigo-900/40 dark:bg-indigo-900/25 dark:text-indigo-300 dark:hover:bg-indigo-900/40">
                        {{ $link['label'] }}
                        <x-icon name="arrow-right" class="h-4 w-4" />
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-icon name="circle-info" style="duotone" class="h-5 w-5 text-indigo-500" />
                        Ficha técnica
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Namespace, caminhos e metadados</p>
                </div>
                <div class="p-6 md:p-8">
                    <dl class="space-y-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <dt class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Identificador (alias)</dt>
                                <dd>
                                    <code class="rounded-lg border border-slate-200 bg-slate-100 px-3 py-1.5 font-mono text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">{{ $module['alias'] }}</code>
                                </dd>
                            </div>
                            <div>
                                <dt class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Versão instalada</dt>
                                <dd>
                                    <span class="inline-flex items-center gap-2 rounded-lg border border-blue-100 bg-blue-50 px-3 py-1.5 font-mono text-sm font-semibold text-blue-700 dark:border-blue-900/30 dark:bg-blue-900/20 dark:text-blue-300">
                                        <x-icon name="code-branch" class="h-3.5 w-3.5" />
                                        v{{ $module['version'] ?? '1.0.0' }}
                                    </span>
                                </dd>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 border-t border-gray-100 pt-6 dark:border-slate-700 md:grid-cols-2">
                            <div>
                                <dt class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Namespace principal</dt>
                                <dd>
                                    <code class="block break-all rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 font-mono text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">{{ $module['namespace'] }}</code>
                                </dd>
                            </div>
                            <div>
                                <dt class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Diretório raiz</dt>
                                <dd>
                                    <code class="block break-all rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 font-mono text-xs text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">{{ $module['path'] }}</code>
                                </dd>
                            </div>
                        </div>

                        @if ((isset($module['author']) && $module['author'] !== 'N/A') || (isset($module['company']) && $module['company'] !== 'N/A'))
                            <div class="grid grid-cols-1 gap-6 border-t border-gray-100 pt-6 dark:border-slate-700 md:grid-cols-2">
                                @if (isset($module['author']) && $module['author'] !== 'N/A')
                                    <div>
                                        <dt class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Desenvolvedor</dt>
                                        <dd class="flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white">
                                            <x-icon name="user-gear" class="h-4 w-4 text-slate-400" />
                                            {{ $module['author'] }}
                                        </dd>
                                    </div>
                                @endif
                                @if (isset($module['company']) && $module['company'] !== 'N/A')
                                    <div>
                                        <dt class="mb-2 text-xs font-semibold uppercase tracking-wider text-slate-500">Organização</dt>
                                        <dd class="flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white">
                                            <x-icon name="building" class="h-4 w-4 text-slate-400" />
                                            {{ $module['company'] }}
                                        </dd>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if (isset($module['keywords']) && is_array($module['keywords']) && count($module['keywords']) > 0)
                            <div class="border-t border-gray-100 pt-6 dark:border-slate-700">
                                <dt class="mb-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Tags e palavras-chave</dt>
                                <dd class="flex flex-wrap gap-2">
                                    @foreach ($module['keywords'] as $keyword)
                                        <span class="inline-flex items-center rounded-full border border-indigo-100 bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:border-indigo-900/40 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            #{{ $keyword }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="space-y-8">
            <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-icon name="chart-pie" style="duotone" class="h-5 w-5 text-emerald-500" />
                        Métricas internas
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Valores reportados pelo módulo</p>
                </div>
                <div class="p-6">
                    @if (! empty($stats) && count($stats) > 2)
                        <div class="space-y-4">
                            @foreach ($stats as $key => $value)
                                @if ($key !== 'enabled' && $key !== 'version')
                                    <div class="flex items-center justify-between border-b border-gray-100 pb-4 last:border-0 last:pb-0 dark:border-slate-700">
                                        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            @if (is_numeric($value))
                                                {{ number_format((float) $value, 0, ',', '.') }}
                                            @elseif (is_bool($value))
                                                <span class="inline-flex items-center gap-1.5 {{ $value ? 'text-emerald-600' : 'text-slate-400' }}">
                                                    <x-icon name="{{ $value ? 'check' : 'xmark' }}" class="h-3.5 w-3.5" />
                                                    {{ $value ? 'Sim' : 'Não' }}
                                                </span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-300 dark:bg-slate-800">
                                <x-icon name="chart-simple" style="duotone" class="h-8 w-8" />
                            </div>
                            <p class="text-sm font-medium text-slate-500">Nenhuma estatística disponível para este módulo.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-icon name="gear" style="duotone" class="h-5 w-5 text-slate-500" />
                        Controles
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Habilitar ou desabilitar o módulo</p>
                </div>
                <div class="p-6">
                    @if ($module['enabled'])
                        <button
                            type="button"
                            data-modal-target="disable-modal"
                            data-modal-toggle="disable-modal"
                            class="group inline-flex w-full items-center justify-center gap-3 rounded-xl bg-rose-600 px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-white shadow-sm transition-all hover:bg-rose-700 active:scale-95"
                        >
                            <x-icon name="power-off" class="h-5 w-5 transition-transform group-hover:scale-110" />
                            Desabilitar módulo
                        </button>
                        <p class="mt-4 text-center text-xs text-slate-400">
                            A desativação interrompe imediatamente rotas e serviços deste módulo.
                        </p>
                    @else
                        <button
                            type="button"
                            data-modal-target="enable-modal"
                            data-modal-toggle="enable-modal"
                            class="group inline-flex w-full items-center justify-center gap-3 rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-white shadow-sm transition-all hover:bg-emerald-700 active:scale-95"
                        >
                            <x-icon name="play" class="h-5 w-5 transition-transform group-hover:scale-110" />
                            Habilitar módulo
                        </button>
                        <p class="mt-4 text-center text-xs text-slate-400">
                            O módulo será carregado na próxima requisição do sistema.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if (! $module['enabled'])
    <div id="enable-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden bg-black/30 backdrop-blur-sm md:inset-0">
        <div class="relative max-h-full w-full max-w-md p-4">
            <div class="relative rounded-2xl border border-gray-100 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-800">
                <button type="button" class="absolute end-2.5 top-3 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-slate-700 dark:hover:text-white" data-modal-hide="enable-modal">
                    <x-icon name="xmark" class="h-3 w-3" />
                    <span class="sr-only">Fechar</span>
                </button>
                <div class="p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                        <x-icon name="play" style="duotone" class="ml-1 h-8 w-8" />
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Ativar módulo?</h3>
                    <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">Você está prestes a habilitar o módulo <strong class="text-gray-900 dark:text-white">{{ $module['name'] }}</strong>.</p>

                    <div class="flex justify-center gap-3">
                        <button data-modal-hide="enable-modal" type="button" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-400 dark:hover:bg-slate-700 dark:hover:text-white">
                            Cancelar
                        </button>
                        <form action="{{ route($routePrefix.'.enable', $module['name']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-xl bg-emerald-600 px-6 py-2.5 text-center text-sm font-bold text-white shadow-lg shadow-emerald-500/30 transition-all hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300 active:scale-95 dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800">
                                Sim, habilitar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($module['enabled'])
    <div id="disable-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden bg-black/30 backdrop-blur-sm md:inset-0">
        <div class="relative max-h-full w-full max-w-md p-4">
            <div class="relative rounded-2xl border border-gray-100 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-800">
                <button type="button" class="absolute end-2.5 top-3 inline-flex h-8 w-8 items-center justify-center rounded-lg bg-transparent text-sm text-gray-400 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-slate-700 dark:hover:text-white" data-modal-hide="disable-modal">
                    <x-icon name="xmark" class="h-3 w-3" />
                    <span class="sr-only">Fechar</span>
                </button>
                <div class="p-8 text-center">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400">
                        <x-icon name="power-off" style="duotone" class="h-8 w-8" />
                    </div>
                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Desativar módulo?</h3>
                    <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">Tem certeza que deseja desabilitar <strong class="text-gray-900 dark:text-white">{{ $module['name'] }}</strong>? Funcionalidades dependentes pararão de funcionar.</p>

                    <div class="flex justify-center gap-3">
                        <button data-modal-hide="disable-modal" type="button" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-400 dark:hover:bg-slate-700 dark:hover:text-white">
                            Cancelar
                        </button>
                        <form action="{{ route($routePrefix.'.disable', $module['name']) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-xl bg-rose-600 px-6 py-2.5 text-center text-sm font-bold text-white shadow-lg shadow-rose-500/30 transition-all hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-300 active:scale-95 dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-800">
                                Sim, desabilitar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
