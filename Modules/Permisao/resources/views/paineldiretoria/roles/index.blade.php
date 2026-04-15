@extends($layout)

@section('title', 'Gerenciar Funções')

@section('content')
@php
    $tierSections = [
        'super_admin' => [
            'title' => 'Super administração',
            'subtitle' => 'Único perfil com acesso ao painel /admin (usuários, módulos, backup, auditoria).',
            'badge' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200',
        ],
        'directorate' => [
            'title' => 'Diretoria',
            'subtitle' => 'Presidente, vices, secretários e tesoureiros — painel /diretoria.',
            'badge' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/40 dark:text-violet-200',
        ],
        'operational' => [
            'title' => 'Operacional',
            'subtitle' => 'Líderes de igrejas locais e jovens (Unijovem).',
            'badge' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200',
        ],
        'custom' => [
            'title' => 'Funções personalizadas',
            'subtitle' => 'Criadas pela equipe; ajuste permissões conforme a necessidade.',
            'badge' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200',
        ],
    ];
    $privilegedCount = $roles->filter(fn ($r) => jubaf_role_tier($r->name) === 'super_admin')->count();
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10 animate-fade-in font-sans">
    @include('permisao::paineldiretoria.partials.subnav', ['active' => 'roles'])

    @include('permisao::paineldiretoria.partials.rbac-context', ['step' => 'roles'])

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Painel diretoria</p>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2 mt-1">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="user-shield" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Controle de <span class="text-indigo-600 dark:text-indigo-400">funções</span></span>
            </h1>
            <p class="max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400 mb-2">
                Aqui define-se <strong class="font-medium text-gray-800 dark:text-gray-200">o perfil</strong>: cada função junta várias permissões.
                Depois, em Utilizadores, atribui-se a função certa a cada pessoa.
            </p>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('diretoria.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Diretoria</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" />
                <a href="{{ route('diretoria.seguranca.hub') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Segurança</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" />
                <span class="text-gray-900 dark:text-white font-medium">Funções (RBAC)</span>
            </nav>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route($permissionsRoutePrefix.'.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 focus:ring-4 focus:ring-gray-100 dark:focus:ring-slate-700 transition-all shadow-sm">
                <x-icon name="key" class="w-5 h-5" style="duotone" />
                Ver Permissões
            </a>
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 transition-all shadow-md shadow-indigo-500/20 active:scale-95">
                <x-icon name="plus" class="w-5 h-5" />
                Nova Função
            </a>
        </div>
    </div>

    <div class="rounded-2xl border border-indigo-100 dark:border-indigo-900/40 bg-indigo-50/60 dark:bg-indigo-950/20 px-4 py-3 text-sm text-indigo-900 dark:text-indigo-100">
        <p class="font-semibold mb-1">Organização dos painéis</p>
            <p class="text-indigo-800/90 dark:text-indigo-200/90 leading-relaxed">
            O endereço <code class="text-xs bg-white/60 dark:bg-slate-800/80 px-1.5 py-0.5 rounded">/admin</code> é exclusivo do <strong>Super Administrador</strong>.
            A <strong>diretoria</strong> (Presidente, Vice, Secretário, Tesoureiro) utiliza apenas o painel
            <code class="text-xs bg-white/60 dark:bg-slate-800/80 px-1.5 py-0.5 rounded">/diretoria</code>.
            Ao abrir uma função e marcar permissões, está a dizer: “quem tiver esta função pode fazer isto”.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-2">Total de Funções</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $roles->count() }}</span>
                    <span class="text-xs text-slate-500">registradas</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-50 dark:bg-purple-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-purple-500 uppercase tracking-wider mb-2">Super administração</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ $privilegedCount }}</span>
                    <span class="text-xs text-purple-600 dark:text-purple-400 font-medium flex items-center gap-1">
                        <x-icon name="shield-check" class="w-3 h-3" style="solid" />
                        Painel /admin
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-blue-50 dark:bg-blue-900/10 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Média de Permissões</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $roles->count() > 0 ? round($roles->sum(fn ($r) => $r->permissions->count()) / $roles->count()) : 0 }}
                    </span>
                    <span class="text-xs text-slate-500">por função</span>
                </div>
            </div>
        </div>
    </div>

    @foreach($tierSections as $tierKey => $meta)
        @php
            $bucket = $roles->filter(fn ($r) => jubaf_role_tier($r->name) === $tierKey)->values();
        @endphp
        @if($bucket->isEmpty())
            @continue
        @endif

        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 border-b border-gray-200 dark:border-slate-700 pb-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold {{ $meta['badge'] }}">{{ $meta['title'] }}</span>
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 max-w-3xl">{{ $meta['subtitle'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($bucket as $role)
                    <div class="group bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all flex flex-col h-full relative overflow-hidden">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 shrink-0 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                    @if(jubaf_role_tier($role->name) === 'super_admin')
                                        <x-icon name="crown" style="duotone" class="w-6 h-6" />
                                    @else
                                        <x-icon name="user-tag" style="duotone" class="w-6 h-6" />
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate" title="{{ $role->name }}">
                                        {{ jubaf_role_label($role->name) }}
                                    </h3>
                                    <p class="text-xs font-mono text-slate-500 truncate" title="{{ $role->name }}">{{ $role->name }}</p>
                                    <span class="text-xs font-medium text-slate-400">Guard {{ $role->guard_name }}</span>
                                </div>
                            </div>

                            <div class="relative shrink-0">
                                <button id="dropdownMenuIconButton{{ $role->id }}" data-dropdown-toggle="dropdownDots{{ $role->id }}" class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50 dark:bg-slate-800 dark:hover:bg-slate-700 dark:focus:ring-gray-600" type="button">
                                    <x-icon name="ellipsis-vertical" class="w-5 h-5" />
                                </button>
                            </div>
                        </div>

                        @if($desc = \App\Support\JubafRoleRegistry::description($role->name))
                            <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">{{ $desc }}</p>
                        @endif

                        <div class="grid grid-cols-2 gap-4 py-4 border-t border-b border-gray-100 dark:border-slate-700 mb-4 bg-gray-50/50 dark:bg-slate-900/30 -mx-6 px-6">
                            <div class="text-center">
                                <span class="block text-2xl font-bold text-gray-900 dark:text-white">{{ $role->users->count() }}</span>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Usuários</span>
                            </div>
                            <div class="text-center border-l border-gray-200 dark:border-slate-700">
                                <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $role->permissions->count() }}</span>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Permissões</span>
                            </div>
                        </div>

                        <div class="flex-grow">
                            @if($role->permissions->count() > 0)
                                <div class="flex flex-wrap gap-1.5 mb-2 max-h-24 overflow-hidden relative">
                                    @foreach($role->permissions->take(5) as $permission)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                    @if($role->permissions->count() > 5)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-300">
                                            +{{ $role->permissions->count() - 5 }}
                                        </span>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-slate-400 italic">Nenhuma permissão específica atribuída.</p>
                            @endif
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between">
                            <a href="{{ route($routePrefix.'.show', $role->id) }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors flex items-center gap-1">
                                Ver Detalhes
                                <x-icon name="arrow-right" class="w-4 h-4" />
                            </a>
                        </div>

                        <div id="dropdownDots{{ $role->id }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-xl shadow-xl w-44 dark:bg-slate-800 dark:divide-slate-700 border border-gray-100 dark:border-slate-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownMenuIconButton{{ $role->id }}">
                                <li>
                                    <a href="{{ route($routePrefix.'.edit', $role->id) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center gap-2">
                                        <x-icon name="pen-to-square" class="w-4 h-4 text-slate-400" />
                                        Editar permissões
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route($routePrefix.'.show', $role->id) }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center gap-2">
                                        <x-icon name="eye" class="w-4 h-4 text-slate-400" />
                                        Visualizar
                                    </a>
                                </li>
                            </ul>
                            @if(! jubaf_role_is_protected($role->name))
                                <div class="py-2">
                                    <form action="{{ route($routePrefix.'.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta função?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-gray-100 dark:hover:bg-slate-700 flex items-center gap-2">
                                            <x-icon name="trash" class="w-4 h-4" />
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    @if($roles->isEmpty())
        <div class="py-24 text-center">
            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4">
                <x-icon name="shield-slash" style="duotone" class="w-10 h-10" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Nenhuma função encontrada</h3>
            <p class="text-sm text-slate-500 mt-1">Execute as seeders ou crie uma nova função.</p>
            <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 mt-4 px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all shadow-md">
                <x-icon name="plus" class="w-5 h-5" />
                Criar Primeira Função
            </a>
        </div>
    @endif
</div>
@endsection
