@extends('admin::layouts.admin')

@section('title', 'Avisos - Admin')

@section('content')
<div class="space-y-6 md:space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="avisos" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Avisos e Banners</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-gray-400" />
                <span class="text-gray-900 dark:text-gray-100 font-medium">Avisos</span>
            </nav>
        </div>
        <div class="flex items-center gap-2">
            @can('create', \Modules\Avisos\App\Models\Aviso::class)
            <a href="{{ route('admin.avisos.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">
                <x-icon name="plus" class="w-5 h-5" />
                <span>Novo Aviso</span>
            </a>
            @endcan
        </div>
    </div>

    <!-- Stats -->
    @if(isset($estatisticas))
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total de Avisos</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $estatisticas['total'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Ativos</div>
            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $estatisticas['ativos'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Estilo banner</div>
            <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $estatisticas['por_estilo']['banner'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Estilo modal</div>
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $estatisticas['por_estilo']['modal'] ?? 0 }}</div>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700 shadow-sm">
        <form action="{{ route('admin.avisos.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="col-span-1 md:col-span-2">
                <label for="search" class="sr-only">Buscar</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icon name="magnifying-glass" class="w-5 h-5 text-gray-400" />
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg leading-5 bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Buscar por título ou descrição...">
                </div>
            </div>
            <div>
                <select name="tipo" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white">
                    <option value="">Todos os Tipos</option>
                    <option value="info" {{ request('tipo') == 'info' ? 'selected' : '' }}>Informação</option>
                    <option value="success" {{ request('tipo') == 'success' ? 'selected' : '' }}>Sucesso</option>
                    <option value="warning" {{ request('tipo') == 'warning' ? 'selected' : '' }}>Atenção</option>
                    <option value="danger" {{ request('tipo') == 'danger' ? 'selected' : '' }}>Perigo</option>
                    <option value="promocao" {{ request('tipo') == 'promocao' ? 'selected' : '' }}>Promoção</option>
                    <option value="novidade" {{ request('tipo') == 'novidade' ? 'selected' : '' }}>Novidade</option>
                    <option value="anuncio" {{ request('tipo') == 'anuncio' ? 'selected' : '' }}>Anúncio</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="posicao" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-slate-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white">
                    <option value="">Todas Posições</option>
                    <option value="topo" {{ request('posicao') == 'topo' ? 'selected' : '' }}>Topo</option>
                    <option value="meio" {{ request('posicao') == 'meio' ? 'selected' : '' }}>Meio</option>
                    <option value="rodape" {{ request('posicao') == 'rodape' ? 'selected' : '' }}>Rodapé</option>
                    <option value="flutuante" {{ request('posicao') == 'flutuante' ? 'selected' : '' }}>Flutuante</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                    <x-icon name="filter" class="w-5 h-5" />
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aviso</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo/Posição</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vigência</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Views/Cliques</th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Ações</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($avisos as $aviso)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center overflow-hidden">
                                    @if($aviso->imagem)
                                        <img class="h-10 w-10 object-cover" src="{{ Storage::url($aviso->imagem) }}" alt="">
                                    @else
                                        <x-icon name="bullhorn" class="w-5 h-5 text-gray-400" />
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $aviso->titulo }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $aviso->descricao }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400">
                                    {{ ucfirst($aviso->tipo) }}
                                </span>
                                <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                    <x-icon name="location-dot" class="w-3 h-3" />
                                    {{ ucfirst($aviso->posicao) }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @can('update', $aviso)
                            <form action="{{ route('admin.avisos.toggle-ativo', $aviso->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $aviso->ativo ? 'bg-indigo-600' : 'bg-gray-200 dark:bg-slate-700' }}">
                                    <span class="translate-x-0 pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $aviso->ativo ? 'translate-x-5' : 'translate-x-0' }}">
                                        <span class="opacity-100 duration-200 ease-in-out absolute inset-0 flex h-full w-full items-center justify-center transition-opacity {{ $aviso->ativo ? 'opacity-0 duration-100 ease-out' : 'opacity-100 duration-200 ease-in' }}" aria-hidden="true">
                                            <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                                <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <span class="opacity-0 duration-100 ease-out absolute inset-0 flex h-full w-full items-center justify-center transition-opacity {{ $aviso->ativo ? 'opacity-100 duration-200 ease-in' : 'opacity-0 duration-100 ease-out' }}" aria-hidden="true">
                                            <svg class="h-3 w-3 text-indigo-600" fill="currentColor" viewBox="0 0 12 12">
                                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                            </svg>
                                        </span>
                                    </span>
                                </button>
                            </form>
                            @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $aviso->ativo ? 'Ativo' : 'Inativo' }}</span>
                            @endcan
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($aviso->data_inicio)
                                <div class="flex flex-col">
                                    <span class="text-xs text-emerald-600 dark:text-emerald-400">Início: {{ $aviso->data_inicio->format('d/m/Y') }}</span>
                                    @if($aviso->data_fim)
                                        <span class="text-xs text-red-600 dark:text-red-400">Fim: {{ $aviso->data_fim->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-xs text-gray-400">Indeterminado</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400">Imediato</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex justify-center gap-3">
                                <div class="flex items-center gap-1" title="Visualizações">
                                    <x-icon name="eye" class="w-3 h-3" />
                                    {{ $aviso->visualizacoes }}
                                </div>
                                <div class="flex items-center gap-1" title="Cliques">
                                    <x-icon name="arrow-pointer" class="w-3 h-3" />
                                    {{ $aviso->cliques }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.avisos.show', $aviso) }}" class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <x-icon name="eye" class="w-5 h-5" />
                                </a>
                                @can('update', $aviso)
                                <a href="{{ route('admin.avisos.edit', $aviso) }}" class="p-1 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                                    <x-icon name="pen-to-square" class="w-5 h-5" />
                                </a>
                                @endcan
                                @can('delete', $aviso)
                                <form action="{{ route('admin.avisos.destroy', $aviso->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este aviso?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                        <x-icon name="trash" class="w-5 h-5" />
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <x-icon module="avisos" class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" style="duotone" />
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum aviso encontrado</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-6">Comece criando um aviso para exibir no site</p>
                                @can('create', \Modules\Avisos\App\Models\Aviso::class)
                                <a href="{{ route('admin.avisos.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors">
                                    <x-icon name="plus" class="w-5 h-5" />
                                    <span>Criar Primeiro Aviso</span>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($avisos->hasPages())
    <div class="mt-4">
        {{ $avisos->links() }}
    </div>
    @endif
</div>
@endsection
