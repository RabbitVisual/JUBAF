@extends('layouts.app')

@section('title', 'Detalhes do Aviso - Admin')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="avisos" class="w-6 h-6 md:w-7 md:h-7 text-white" />
                </div>
                <span>{{ $aviso->titulo }}</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-4 h-4" />
                <a href="{{ route('admin.avisos.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Avisos</a>
                <x-icon name="chevron-right" class="w-4 h-4" />
                <span class="text-gray-900 dark:text-white font-medium">Detalhes</span>
            </nav>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.avisos.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" />
                Voltar
            </a>
            @can('update', $aviso)
            <a href="{{ route('admin.avisos.edit', $aviso) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                <x-icon name="pen-to-square" class="w-5 h-5" />
                Editar
            </a>
            @endcan
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
        <div class="flex items-center">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Conteúdo Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Preview do Aviso -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview do Aviso</h3>
                <div class="border border-gray-200 dark:border-slate-700 rounded-lg p-4 bg-gray-50 dark:bg-slate-900 relative min-h-[200px]">
                    <div class="preview-aviso">
                        @include('avisos::components.banner', ['aviso' => $aviso])
                    </div>
                </div>
            </div>

            <!-- Informações Básicas -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Básicas</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Título</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $aviso->titulo }}</dd>
                    </div>
                    
                    @if($aviso->descricao)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Descrição</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $aviso->descricao }}</dd>
                    </div>
                    @endif

                    @if($aviso->conteudo)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Conteúdo</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white prose dark:prose-invert max-w-none">
                            {!! $aviso->conteudo !!}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Configurações -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Configurações</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($aviso->tipo == 'info') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                @elseif($aviso->tipo == 'success') bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200
                                @elseif($aviso->tipo == 'warning') bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200
                                @elseif($aviso->tipo == 'danger') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($aviso->tipo == 'promocao') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                @elseif($aviso->tipo == 'novidade') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endif">
                                {{ $aviso->tipo_texto }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Posição</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $aviso->posicao_texto }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estilo</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $aviso->estilo_texto }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ordem</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $aviso->ordem }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status</h3>
                
                <div class="space-y-4">
                    <div>
                        <form action="{{ route('admin.avisos.toggle-ativo', $aviso) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors
                                @if($aviso->ativo) bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-900 dark:text-emerald-200 dark:hover:bg-emerald-800
                                @else bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800
                                @endif">
                                {{ $aviso->ativo ? 'Ativo' : 'Inativo' }}
                            </button>
                        </form>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Destacar:</span>
                        @if($aviso->destacar)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Sim</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Não</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Dismissível:</span>
                        @if($aviso->dismissivel)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Sim</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Não</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Estado Atual:</span>
                        @if($estatisticas['esta_ativo'])
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">Visível</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">Oculto</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Estatísticas</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Visualizações</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($estatisticas['visualizacoes'], 0, ',', '.') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliques</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($estatisticas['cliques'], 0, ',', '.') }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Taxa de Clique</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $estatisticas['taxa_clique'] }}%</dd>
                    </div>

                    @if($estatisticas['dias_restantes'] !== null)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Dias Restantes</dt>
                        <dd class="mt-1 text-2xl font-bold 
                            @if($estatisticas['dias_restantes'] > 0) text-gray-900 dark:text-white
                            @else text-red-600 dark:text-red-400
                            @endif">
                            @if($estatisticas['dias_restantes'] > 0)
                                {{ number_format($estatisticas['dias_restantes'], 0, ',', '.') }} {{ $estatisticas['dias_restantes'] == 1 ? 'dia' : 'dias' }}
                            @else
                                Expirado
                            @endif
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Adicionais</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado por</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $aviso->usuario ? $aviso->usuario->name : 'Sistema' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $aviso->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última atualização</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $aviso->updated_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>

                    @if($aviso->data_inicio)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Início</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $aviso->data_inicio->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    @endif

                    @if($aviso->data_fim)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Fim</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $aviso->data_fim->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Call to Action -->
            @if($aviso->url_acao)
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Call to Action</h3>
                
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">URL</dt>
                        <dd class="mt-1">
                            <a href="{{ $aviso->url_acao }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-sm text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 break-all">
                                {{ $aviso->url_acao }}
                            </a>
                        </dd>
                    </div>

                    @if($aviso->texto_botao)
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Texto do Botão</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $aviso->texto_botao }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Forçar posicionamento relativo no preview do admin */
    .preview-aviso .aviso-flutuante,
    .preview-aviso [class*="fixed"] {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        bottom: auto !important;
        z-index: auto !important;
    }
</style>
@endpush
@endsection

