@extends('layouts.app')

@section('title', 'Avisos — Diretoria')

@section('content')
@php
    $filterClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-cyan-400';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('avisos::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-800 dark:text-cyan-400">Diretoria · Comunicação JUBAF</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/25">
                    <x-module-icon module="Avisos" class="h-7 w-7" />
                </span>
                Avisos e banners
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Comunicados oficiais no site, homepage e painéis — audiência por congregação quando aplicável.
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @can('create', \Modules\Avisos\App\Models\Aviso::class)
                <a href="{{ route('diretoria.avisos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Novo aviso
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    @if(isset($estatisticas))
        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
            <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Total</div>
                <div class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">{{ $estatisticas['total'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Ativos</div>
                <div class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $estatisticas['ativos'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estilo banner</div>
                <div class="mt-1 text-2xl font-bold text-cyan-600 dark:text-cyan-400">{{ $estatisticas['por_estilo']['banner'] ?? 0 }}</div>
            </div>
            <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estilo modal</div>
                <div class="mt-1 text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $estatisticas['por_estilo']['modal'] ?? 0 }}</div>
            </div>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] flex-1 sm:max-w-md">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400" for="search">Pesquisar</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <x-icon name="magnifying-glass" class="h-4 w-4" />
                    </span>
                    <input type="search" name="search" id="search" value="{{ request('search') }}" placeholder="Título ou descrição…" class="{{ $filterClass }} pl-10" />
                </div>
            </div>
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400" for="tipo">Tipo</label>
                <select name="tipo" id="tipo" class="{{ $filterClass }}">
                    <option value="">Todos</option>
                    <option value="info" @selected(request('tipo') == 'info')>Informação</option>
                    <option value="success" @selected(request('tipo') == 'success')>Sucesso</option>
                    <option value="warning" @selected(request('tipo') == 'warning')>Atenção</option>
                    <option value="danger" @selected(request('tipo') == 'danger')>Urgente</option>
                    <option value="promocao" @selected(request('tipo') == 'promocao')>Promoção</option>
                    <option value="novidade" @selected(request('tipo') == 'novidade')>Novidade</option>
                    <option value="anuncio" @selected(request('tipo') == 'anuncio')>Anúncio</option>
                </select>
            </div>
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400" for="posicao">Posição</label>
                <select name="posicao" id="posicao" class="{{ $filterClass }}">
                    <option value="">Todas</option>
                    <option value="topo" @selected(request('posicao') == 'topo')>Topo</option>
                    <option value="meio" @selected(request('posicao') == 'meio')>Meio</option>
                    <option value="rodape" @selected(request('posicao') == 'rodape')>Rodapé</option>
                    <option value="flutuante" @selected(request('posicao') == 'flutuante')>Flutuante</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/20 transition hover:bg-cyan-700">
                    <x-icon name="filter" class="h-4 w-4" style="solid" />
                    Aplicar
                </button>
                <a href="{{ route('diretoria.avisos.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-700">Limpar</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/80 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3.5">Aviso</th>
                        <th class="px-4 py-3.5">Tipo / posição</th>
                        <th class="px-4 py-3.5">Estado</th>
                        <th class="px-4 py-3.5">Vigência</th>
                        <th class="px-4 py-3.5 text-center">Views / cliques</th>
                        <th class="px-4 py-3.5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($avisos as $aviso)
                        <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-slate-800/50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-gray-100 dark:bg-slate-700">
                                        @if($aviso->imagem)
                                            <img class="h-10 w-10 object-cover" src="{{ Storage::url($aviso->imagem) }}" alt="">
                                        @else
                                            <x-icon name="bullhorn" class="h-5 w-5 text-gray-400" />
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $aviso->titulo }}</div>
                                        <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Str::limit(strip_tags((string) $aviso->descricao), 64) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="inline-flex rounded-full bg-cyan-100 px-2.5 py-0.5 text-xs font-semibold text-cyan-900 dark:bg-cyan-900/40 dark:text-cyan-200">{{ ucfirst($aviso->tipo) }}</span>
                                <div class="mt-1 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                    <x-icon name="location-dot" class="h-3 w-3" />
                                    {{ ucfirst($aviso->posicao) }}
                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                @can('update', $aviso)
                                    <form action="{{ route('diretoria.avisos.toggle-ativo', $aviso->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $aviso->ativo ? 'bg-cyan-600' : 'bg-gray-200 dark:bg-slate-700' }}">
                                            <span class="pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $aviso->ativo ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $aviso->ativo ? 'Ativo' : 'Inativo' }}</span>
                                @endcan
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-500 dark:text-gray-400">
                                @if($aviso->data_inicio)
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-emerald-600 dark:text-emerald-400">Início: {{ $aviso->data_inicio->format('d/m/Y') }}</span>
                                        @if($aviso->data_fim)
                                            <span class="text-red-600 dark:text-red-400">Fim: {{ $aviso->data_fim->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-gray-400">Sem fim</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400">Imediato</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center text-xs text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1" title="Visualizações"><x-icon name="eye" class="h-3 w-3" />{{ $aviso->visualizacoes }}</span>
                                <span class="mx-1 text-gray-300 dark:text-gray-600">·</span>
                                <span class="inline-flex items-center gap-1" title="Cliques"><x-icon name="arrow-pointer" class="h-3 w-3" />{{ $aviso->cliques }}</span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('diretoria.avisos.show', $aviso) }}" class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-cyan-600 dark:hover:bg-slate-700 dark:hover:text-cyan-400" title="Ver">
                                        <x-icon name="eye" class="h-5 w-5" />
                                    </a>
                                    @can('update', $aviso)
                                        <a href="{{ route('diretoria.avisos.edit', $aviso) }}" class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-amber-600 dark:hover:bg-slate-700" title="Editar">
                                            <x-icon name="pen-to-square" class="h-5 w-5" />
                                        </a>
                                    @endcan
                                    @can('delete', $aviso)
                                        <form action="{{ route('diretoria.avisos.destroy', $aviso->id) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este aviso?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-gray-400 transition hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/30" title="Excluir">
                                                <x-icon name="trash" class="h-5 w-5" />
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <x-module-icon module="Avisos" class="mx-auto mb-4 h-16 w-16 text-gray-300 dark:text-gray-600" />
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Nenhum aviso encontrado</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste os filtros ou crie o primeiro comunicado para o site JUBAF.</p>
                                @can('create', \Modules\Avisos\App\Models\Aviso::class)
                                    <a href="{{ route('diretoria.avisos.create') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                                        <x-icon name="plus" class="h-4 w-4" style="solid" />
                                        Criar primeiro aviso
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($avisos->hasPages())
        <div class="mt-2">
            {{ $avisos->links() }}
        </div>
    @endif
</div>
@endsection
