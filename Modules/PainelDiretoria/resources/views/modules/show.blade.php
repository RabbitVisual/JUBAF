@extends('paineldiretoria::components.layouts.app')

@section('title', 'Detalhes do Módulo: ' . $module['name'])

@section('content')
<div class="mx-auto max-w-7xl space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    @include('paineldiretoria::partials.modules-subnav', ['active' => 'detalhe'])

    <div class="flex flex-col gap-4 rounded-3xl border border-violet-200/60 bg-gradient-to-br from-violet-50/80 to-white p-6 shadow-sm dark:border-violet-900/30 dark:from-violet-950/20 dark:to-slate-900 sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div class="min-w-0">
            <nav aria-label="breadcrumb" class="mb-2 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                <a href="{{ route('diretoria.dashboard') }}" class="font-medium text-violet-700 hover:underline dark:text-violet-400">Painel</a>
                <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                <a href="{{ route('diretoria.modules.index') }}" class="font-medium text-violet-700 hover:underline dark:text-violet-400">Módulos</a>
                <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                <span class="font-semibold text-gray-900 dark:text-white">Detalhe</span>
            </nav>
            <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-600 to-indigo-600 text-white shadow-md">
                    <x-icon name="cube" class="h-6 w-6" style="duotone" />
                </span>
                <span class="text-indigo-600 dark:text-indigo-400">{{ $module['name'] }}</span>
            </h1>
        </div>
        <a href="{{ route('diretoria.modules.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
            <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
            Lista de módulos
        </a>
    </div>

    <!-- Status Banner -->
    <div class="rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center gap-6 shadow-sm border {{ $module['enabled'] ? 'bg-emerald-50/50 dark:bg-emerald-900/10 border-emerald-100 dark:border-emerald-900/20' : 'bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700' }}">
        <div class="w-16 h-16 rounded-2xl {{ $module['enabled'] ? 'bg-gradient-to-br from-emerald-500 to-teal-600' : 'bg-gradient-to-br from-slate-400 to-slate-500' }} flex items-center justify-center shadow-lg flex-shrink-0">
            <x-icon name="{{ $module['enabled'] ? 'check-circle' : 'ban' }}" class="w-8 h-8 text-white" style="duotone" />
        </div>
        <div class="flex-1">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $module['name'] }}</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ $module['description'] ?? 'Sem descrição disponível.' }}</p>
                </div>
                <div>
                     @if($module['enabled'])
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-900 text-emerald-600 dark:text-emerald-400 shadow-sm border border-emerald-100 dark:border-emerald-900/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Módulo Ativo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 shadow-sm border border-slate-200 dark:border-slate-700">
                             <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            Módulo Inativo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(!empty($adminShortcuts))
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                <x-icon name="arrow-up-right-from-square" style="duotone" class="w-5 h-5 text-indigo-500" />
                Atalhos no painel admin
            </h2>
        </div>
        <div class="p-6 md:p-8 flex flex-wrap gap-3">
            @foreach($adminShortcuts as $link)
                <a href="{{ $link['url'] }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/25 border border-indigo-100 dark:border-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition-colors">
                    {{ $link['label'] }}
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="circle-info" style="duotone" class="w-5 h-5 text-indigo-500" />
                        Ficha Técnica
                    </h2>
                </div>
                <div class="p-6 md:p-8">
                    <dl class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Identificador (Alias)</dt>
                                <dd>
                                    <code class="px-3 py-1.5 bg-slate-100 dark:bg-slate-900 rounded-lg text-sm font-mono text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">{{ $module['alias'] }}</code>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Versão Instalada</dt>
                                <dd>
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg font-mono text-sm font-semibold border border-blue-100 dark:border-blue-900/30">
                                        <x-icon name="code-branch" class="w-3.5 h-3.5" />
                                        v{{ $module['version'] ?? '1.0.0' }}
                                    </span>
                                </dd>
                            </div>
                        </div>

                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100 dark:border-slate-700">
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Namespace Principal</dt>
                                <dd>
                                    <code class="block px-3 py-2 bg-slate-100 dark:bg-slate-900 rounded-lg text-xs font-mono text-slate-600 dark:text-slate-400 break-all border border-slate-200 dark:border-slate-700">{{ $module['namespace'] }}</code>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Diretório Raiz</dt>
                                <dd>
                                    <code class="block px-3 py-2 bg-slate-100 dark:bg-slate-900 rounded-lg text-xs font-mono text-slate-600 dark:text-slate-400 break-all border border-slate-200 dark:border-slate-700">{{ $module['path'] }}</code>
                                </dd>
                            </div>
                        </div>

                        @if((isset($module['author']) && $module['author'] !== 'N/A') || (isset($module['company']) && $module['company'] !== 'N/A'))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100 dark:border-slate-700">
                             @if(isset($module['author']) && $module['author'] !== 'N/A')
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Desenvolvedor</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <x-icon name="user-gear" class="w-4 h-4 text-slate-400" />
                                    {{ $module['author'] }}
                                </dd>
                            </div>
                            @endif
                            @if(isset($module['company']) && $module['company'] !== 'N/A')
                            <div>
                                <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Organização</dt>
                                <dd class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <x-icon name="building" class="w-4 h-4 text-slate-400" />
                                    {{ $module['company'] }}
                                </dd>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if(isset($module['keywords']) && is_array($module['keywords']) && count($module['keywords']) > 0)
                        <div class="pt-6 border-t border-gray-100 dark:border-slate-700">
                            <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Tags & Palavras-chave</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($module['keywords'] as $keyword)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/40">
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

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Estatísticas -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="chart-pie" style="duotone" class="w-5 h-5 text-emerald-500" />
                        Métricas Internas
                    </h2>
                </div>
                <div class="p-6">
                    @if(!empty($stats) && count($stats) > 2)
                        <div class="space-y-4">
                            @foreach($stats as $key => $value)
                                @if($key !== 'enabled' && $key !== 'version')
                                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-700 last:border-0 last:pb-0">
                                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </span>
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">
                                            @if(is_numeric($value))
                                                {{ number_format((float)$value, 0, ',', '.') }}
                                            @elseif(is_bool($value))
                                                <span class="inline-flex items-center gap-1.5 {{ $value ? 'text-emerald-600' : 'text-slate-400' }}">
                                                    <x-icon name="{{ $value ? 'check' : 'xmark' }}" class="w-3.5 h-3.5" />
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
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <x-icon name="chart-simple" style="duotone" class="w-8 h-8" />
                            </div>
                            <p class="text-sm font-medium text-slate-500">Nenhuma estatística disponível para este módulo.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ações Administrativas -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="gear" style="duotone" class="w-5 h-5 text-slate-500" />
                        Controles
                    </h2>
                </div>
                <div class="p-6">
                    @if($module['enabled'])
                        <button
                            type="button"
                            data-modal-target="disable-modal"
                            data-modal-toggle="disable-modal"
                            class="w-full inline-flex items-center justify-center gap-3 px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition-all shadow-sm active:scale-95 group"
                        >
                            <x-icon name="power-off" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                            Desabilitar Módulo
                        </button>
                        <p class="mt-4 text-xs text-center text-slate-400">
                            A desativação interromperá imediatamente todas as rotas e serviços deste módulo.
                        </p>
                    @else
                        <button
                            type="button"
                            data-modal-target="enable-modal"
                            data-modal-toggle="enable-modal"
                            class="w-full inline-flex items-center justify-center gap-3 px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-all shadow-sm active:scale-95 group"
                        >
                            <x-icon name="play" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                            Habilitar Módulo
                        </button>
                        <p class="mt-4 text-xs text-center text-slate-400">
                            O módulo será carregado na próxima requisição do sistema.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modais de Confirmação -->
@if(!$module['enabled'])
<!-- Enable Modal -->
<div id="enable-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-black/30">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-slate-800 border border-gray-100 dark:border-slate-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-slate-700 dark:hover:text-white" data-modal-hide="enable-modal">
                <x-icon name="xmark" class="w-3 h-3" />
                <span class="sr-only">Fechar</span>
            </button>
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 dark:text-emerald-400">
                     <x-icon name="play" style="duotone" class="w-8 h-8 ml-1" />
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Ativar Módulo?</h3>
                <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">Você está prestes a habilitar o módulo <strong class="text-gray-900 dark:text-white">{{ $module['name'] }}</strong>.</p>

                <div class="flex gap-3 justify-center">
                    <button data-modal-hide="enable-modal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-slate-800 dark:text-gray-400 dark:border-slate-600 dark:hover:text-white dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </button>
                    <form action="{{ route('diretoria.modules.enable', $module['name']) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white bg-emerald-600 hover:bg-emerald-700 focus:ring-4 focus:outline-none focus:ring-emerald-300 font-bold rounded-xl text-sm inline-flex items-center px-6 py-2.5 text-center dark:bg-emerald-600 dark:hover:bg-emerald-700 dark:focus:ring-emerald-800 shadow-lg shadow-emerald-500/30 transition-all active:scale-95">
                            Sim, Habilitar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($module['enabled'])
<!-- Disable Modal -->
<div id="disable-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-black/30">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl dark:bg-slate-800 border border-gray-100 dark:border-slate-700">
            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-slate-700 dark:hover:text-white" data-modal-hide="disable-modal">
                <x-icon name="xmark" class="w-3 h-3" />
                <span class="sr-only">Fechar</span>
            </button>
            <div class="p-8 text-center">
                 <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/30 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-600 dark:text-rose-400">
                     <x-icon name="power-off" style="duotone" class="w-8 h-8" />
                </div>
                <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-white">Desativar Módulo?</h3>
                <p class="mb-6 text-sm text-slate-500 dark:text-slate-400">Tem certeza que deseja desabilitar <strong class="text-gray-900 dark:text-white">{{ $module['name'] }}</strong>? Funcionalidades dependentes pararão de funcionar.</p>

                <div class="flex gap-3 justify-center">
                    <button data-modal-hide="disable-modal" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-slate-800 dark:text-gray-400 dark:border-slate-600 dark:hover:text-white dark:hover:bg-slate-700 transition-colors">
                        Cancelar
                    </button>
                    <form action="{{ route('diretoria.modules.disable', $module['name']) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-white bg-rose-600 hover:bg-rose-700 focus:ring-4 focus:outline-none focus:ring-rose-300 font-bold rounded-xl text-sm inline-flex items-center px-6 py-2.5 text-center dark:bg-rose-600 dark:hover:bg-rose-700 dark:focus:ring-rose-800 shadow-lg shadow-rose-500/30 transition-all active:scale-95">
                            Sim, Desabilitar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
