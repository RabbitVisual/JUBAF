@extends('layouts.app')

@section('title', 'Detalhes do aviso — Diretoria')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('avisos::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <a href="{{ route('diretoria.avisos.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
                <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
                Voltar à lista
            </a>
            <p class="mt-3 text-xs font-bold uppercase tracking-[0.18em] text-cyan-800 dark:text-cyan-400">Diretoria · Comunicação JUBAF</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/25">
                    <x-module-icon module="Avisos" class="h-7 w-7" />
                </span>
                <span class="min-w-0 break-words">{{ $aviso->titulo }}</span>
            </h1>
            @if($aviso->descricao)
                <p class="mt-2 max-w-3xl text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($aviso->descricao, 160) }}</p>
            @endif
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @can('update', $aviso)
                <a href="{{ route('diretoria.avisos.edit', $aviso) }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                    <x-icon name="pen-to-square" class="h-4 w-4" style="solid" />
                    Editar
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pré-visualização</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Como o aviso pode aparecer no site (posição fixa é simulada em fluxo normal).</p>
                <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50/80 p-4 dark:border-slate-600 dark:bg-slate-900/50">
                    <div class="preview-aviso relative min-h-[120px]">
                        @include('avisos::components.banner', ['aviso' => $aviso])
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Conteúdo</h2>
                <dl class="mt-4 space-y-4 text-sm">
                    @if($aviso->descricao)
                        <div>
                            <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Resumo</dt>
                            <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $aviso->descricao }}</dd>
                        </div>
                    @endif
                    @if($aviso->conteudo)
                        <div>
                            <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Texto completo</dt>
                            <dd class="mt-1 prose prose-sm max-w-none text-gray-800 dark:prose-invert dark:text-gray-200">
                                {!! $aviso->conteudo !!}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Configurações de exibição</h2>
                <dl class="mt-4 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Tipo</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                @if($aviso->tipo == 'info') bg-cyan-100 text-cyan-900 ring-1 ring-cyan-200 dark:bg-cyan-900/40 dark:text-cyan-100 dark:ring-cyan-800/50
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
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Posição</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $aviso->posicao_texto }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Estilo</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $aviso->estilo_texto }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Ordem</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $aviso->ordem }}</dd>
                    </div>
                </dl>
            </div>

            @if($aviso->url_acao)
                <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Chamada à ação</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">URL</dt>
                            <dd class="mt-1 break-all">
                                <a href="{{ $aviso->url_acao }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
                                    {{ $aviso->url_acao }}
                                </a>
                            </dd>
                        </div>
                        @if($aviso->texto_botao)
                            <div>
                                <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Texto do botão</dt>
                                <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $aviso->texto_botao }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-cyan-100 bg-white p-6 shadow-sm dark:border-cyan-900/40 dark:bg-slate-800 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Estado</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <form action="{{ route('diretoria.avisos.toggle-ativo', $aviso) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl px-3 py-2 text-sm font-semibold transition
                                @if($aviso->ativo) bg-emerald-100 text-emerald-900 ring-1 ring-emerald-200 hover:bg-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50
                                @else bg-slate-200 text-slate-800 hover:bg-slate-300 dark:bg-slate-700 dark:text-slate-200
                                @endif">
                                {{ $aviso->ativo ? 'Ativo na JUBAF' : 'Marcar como ativo' }}
                            </button>
                        </form>
                    </div>
                    <dl class="space-y-2 text-sm">
                        <div class="flex items-center justify-between gap-2">
                            <dt class="text-gray-500 dark:text-gray-400">Destaque</dt>
                            <dd>
                                @if($aviso->destacar)
                                    <span class="inline-flex rounded-lg bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-900 dark:bg-amber-900/50 dark:text-amber-100">Sim</span>
                                @else
                                    <span class="inline-flex rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-200">Não</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <dt class="text-gray-500 dark:text-gray-400">Fechar</dt>
                            <dd>
                                @if($aviso->dismissivel)
                                    <span class="inline-flex rounded-lg bg-sky-100 px-2 py-0.5 text-xs font-bold text-sky-900 dark:bg-sky-900/40 dark:text-sky-100">Permitido</span>
                                @else
                                    <span class="inline-flex rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700 dark:bg-slate-700 dark:text-slate-200">Fixo</span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-center justify-between gap-2 border-t border-gray-100 pt-2 dark:border-slate-600">
                            <dt class="text-gray-500 dark:text-gray-400">Visibilidade</dt>
                            <dd>
                                @if($estatisticas['esta_ativo'])
                                    <span class="inline-flex rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100">Visível</span>
                                @else
                                    <span class="inline-flex rounded-lg bg-slate-200 px-2 py-0.5 text-xs font-bold text-slate-800 dark:bg-slate-700 dark:text-slate-200">Oculto</span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Estatísticas</h2>
                <dl class="mt-4 space-y-4">
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Visualizações</dt>
                        <dd class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ number_format($estatisticas['visualizacoes'], 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Cliques</dt>
                        <dd class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ number_format($estatisticas['cliques'], 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Taxa de clique</dt>
                        <dd class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $estatisticas['taxa_clique'] }}%</dd>
                    </div>
                    @if($estatisticas['dias_restantes'] !== null)
                        <div>
                            <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Prazo</dt>
                            <dd class="mt-1 text-lg font-bold tabular-nums
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

            <div class="rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 sm:p-8">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Registo</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Criado por</dt>
                        <dd class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $aviso->usuario ? $aviso->usuario->name : 'Sistema' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Criado em</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $aviso->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Atualizado</dt>
                        <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $aviso->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if($aviso->data_inicio)
                        <div>
                            <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Início</dt>
                            <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $aviso->data_inicio->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($aviso->data_fim)
                        <div>
                            <dt class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Fim</dt>
                            <dd class="mt-1 text-gray-800 dark:text-gray-200">{{ $aviso->data_fim->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
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
