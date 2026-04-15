@extends($layout)

@section('title', 'Gerenciar Permissões')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="key" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Controle de <span class="text-indigo-600 dark:text-indigo-400">Permissões</span></span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route(user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" />
                <span class="text-gray-900 dark:text-white font-medium">Segurança & Acesso</span>
            </nav>
        </div>
        <a href="{{ route($rolesRoutePrefix.'.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 focus:ring-4 focus:ring-gray-100 dark:focus:ring-slate-700 transition-all shadow-sm">
            <x-icon name="user-shield" class="w-5 h-5" style="duotone" />
            Gerenciar Funções (Roles)
        </a>
    </div>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-2">Total de Permissões</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalPermissions }}</span>
                    <span class="text-xs text-slate-500">registros</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 dark:bg-blue-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Módulos Seguros</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($permissions) }}</span>
                    <span class="text-xs text-slate-500">áreas</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider mb-2">Status do Sistema</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                        <x-icon name="check-circle" class="w-4 h-4" style="solid" />
                        Operacional
                    </span>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Todas as permissões ativas</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 dark:bg-purple-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-purple-500 uppercase tracking-wider mb-2">Média por Módulo</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($permissions) > 0 ? round($totalPermissions / count($permissions)) : 0 }}</span>
                    <span class="text-xs text-slate-500">regras/módulo</span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8">
        <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4 flex items-center gap-2">
            <x-icon name="plus" class="w-4 h-4 text-indigo-500" />
            Nova permissão customizada
        </h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Use o formato <code class="text-xs bg-slate-100 dark:bg-slate-900 px-1 rounded">modulo.acao</code>. Permissões criadas aqui podem ser editadas ou removidas. As do sistema (seed) estão protegidas.</p>
        <form action="{{ route($routePrefix.'.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3 sm:items-end">
            @csrf
            <div class="flex-1 min-w-0">
                <label for="perm_name" class="block text-xs font-medium text-slate-500 mb-1">Nome técnico</label>
                <input type="text" id="perm_name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="ex.: relatorios.export"
                    class="w-full rounded-xl border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-4 py-2.5 text-sm">
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700">
                Criar permissão
            </button>
        </form>
        @error('name')
            <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Permissões por Módulo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($permissions as $module => $modulePermissions)
        @php
            $moduleDisplay = str_replace(['-', '_'], ' ', $module);
            $moduleDisplay = ucwords($moduleDisplay);
        @endphp
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition-all duration-300 group flex flex-col h-full">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                        <x-icon name="cube" style="duotone" class="w-5 h-5 text-indigo-500" />
                        {{ $moduleDisplay }}
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                        {{ count($modulePermissions) }}
                    </span>
                </div>
            </div>
            <div class="p-6 flex-grow">
                <ul class="space-y-3">
                    @foreach($modulePermissions as $permission)
                    <li class="flex flex-col gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50 hover:bg-white dark:hover:bg-slate-800 hover:border-indigo-200 dark:hover:border-indigo-900/50 hover:shadow-sm transition-all duration-200">
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 p-1 bg-emerald-100 dark:bg-emerald-900/30 rounded-full shrink-0">
                                <x-icon name="check" class="w-3 h-3 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white break-words">
                                    {{ $permission['display_name'] ?? $permission['name'] }}
                                </p>
                                <p class="text-[10px] text-slate-400 mt-0.5 font-mono break-all">
                                    {{ $permission['name'] }}
                                </p>
                                @if($permission['is_system'] ?? true)
                                    <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 bg-slate-200/80 dark:bg-slate-700 px-2 py-0.5 rounded">Sistema</span>
                                @else
                                    <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wide text-amber-700 bg-amber-100 dark:bg-amber-900/40 dark:text-amber-200 px-2 py-0.5 rounded">Custom</span>
                                @endif
                            </div>
                        </div>
                        @if(!($permission['is_system'] ?? true))
                        <div class="flex flex-wrap gap-2 pl-10">
                            <a href="{{ route($routePrefix.'.edit', $permission['id']) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Editar</a>
                            <form action="{{ route($routePrefix.'.destroy', $permission['id']) }}" method="POST" class="inline" onsubmit="return confirm('Remover esta permissão?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:underline">Excluir</button>
                            </form>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @empty
        <div class="col-span-full py-24 text-center">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                <x-icon name="shield-slash" style="duotone" class="w-10 h-10" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nenhuma permissão encontrada</h3>
            <p class="text-sm text-slate-500 mt-1">O sistema não possui permissões registradas.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
