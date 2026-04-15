@extends($layout)

@section('title', 'Detalhes do Usuário')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="user" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Perfil do <span class="text-indigo-600 dark:text-indigo-400">Usuário</span></span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route(user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" />
                <a href="{{ route($routePrefix.'.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Usuários</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" />
                <span class="text-gray-900 dark:text-white font-medium">{{ $user->name }}</span>
            </nav>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-600 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 focus:ring-4 focus:ring-gray-100 dark:focus:ring-slate-700 transition-all shadow-sm">
                <x-icon name="arrow-left" class="w-5 h-5" />
                Voltar
            </a>
            <a href="{{ route($routePrefix.'.edit', $user) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 transition-all shadow-md shadow-indigo-500/20 active:scale-95 group">
                <x-icon name="pen-to-square" class="w-5 h-5 group-hover:scale-110 transition-transform" />
                Editar Perfil
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Coluna Esquerda: Cartão de Perfil -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden relative group">
                 <div class="h-32 bg-gradient-to-br from-indigo-600 to-violet-700 relative">
                    <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
                <div class="px-8 pb-8 text-center relative">
                    <div class="relative -mt-16 mb-4 inline-block">
                        <div class="w-32 h-32 rounded-[2.5rem] border-8 border-white dark:border-slate-800 bg-slate-100 dark:bg-slate-700 relative overflow-hidden shadow-2xl group-hover:scale-105 transition-transform duration-300">
                            @if(user_photo_url($user))
                                <img src="{{ user_photo_url($user) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-indigo-500 bg-indigo-50 dark:bg-indigo-900/20">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        @if($user->active)
                            <div class="absolute bottom-2 right-2 w-8 h-8 rounded-2xl border-4 border-white dark:border-slate-800 bg-emerald-500 flex items-center justify-center shadow-sm" title="Usuário Ativo">
                                <x-icon name="check" class="w-3.5 h-3.5 text-white" />
                            </div>
                        @else
                             <div class="absolute bottom-2 right-2 w-8 h-8 rounded-2xl border-4 border-white dark:border-slate-800 bg-rose-500 flex items-center justify-center shadow-sm" title="Usuário Inativo">
                                <x-icon name="xmark" class="w-3.5 h-3.5 text-white" />
                            </div>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-6">{{ $user->email }}</p>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 dark:border-slate-700 pt-6">
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Membro desde</p>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">{{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-1">Último acesso</p>
                            <p class="font-bold text-gray-900 dark:text-white text-sm">Recently</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <x-icon name="shield-halved" style="duotone" class="w-4 h-4 text-indigo-500" />
                    Permissões Atribuídas
                </h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($user->roles as $role)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/20">
                            <x-icon name="user-tag" class="w-3 h-3" />
                            {{ $role->name }}
                        </span>
                    @empty
                        <span class="text-xs text-slate-400 italic">Nenhuma função atribuída.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Coluna Direita: Informações Detalhadas -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Detalhes de Contato -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="address-card" style="duotone" class="w-5 h-5 text-blue-500" />
                        Informações Pessoais
                    </h2>
                </div>
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Nome Completo</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">E-mail</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Telefone</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $user->phone ?? 'Não informado' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Telefone (função / igreja)</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $user->church_phone ?? 'Não informado' }}</p>
                    </div>
                    @if($user->birth_date)
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Data de nascimento</p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $user->birth_date->format('d/m/Y') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Status da Conta</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $user->active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->active ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $user->active ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Atividade Recente (Placeholder) -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                    <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                        <x-icon name="clock-rotate-left" style="duotone" class="w-5 h-5 text-orange-500" />
                        Registro de Atividades
                    </h2>
                </div>
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100 dark:border-slate-700">
                        <x-icon name="clipboard-list" style="duotone" class="w-8 h-8" />
                    </div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Nenhuma atividade recente registrada.</p>
                </div>
            </div>

             <!-- Delete Zone -->
            <div class="bg-rose-50 dark:bg-rose-900/10 rounded-3xl p-6 border border-rose-100 dark:border-rose-900/30">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-rose-900 dark:text-rose-200">Zona de Perigo</h3>
                        <p class="text-xs text-rose-700 dark:text-rose-300 mt-1 max-w-md">
                            A exclusão deste usuário é irreversível. Todos os dados associados serão removidos permanentemente.
                        </p>
                    </div>
                    @if(auth()->id() !== $user->id)
                    <form action="{{ route($routePrefix.'.destroy', $user) }}" method="POST" onsubmit="return confirm('Tem certeza absoluta? Esta ação não pode ser desfeita.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-rose-600 rounded-xl hover:bg-rose-700 focus:ring-4 focus:ring-rose-200 dark:focus:ring-rose-900 transition-all shadow-sm active:scale-95">
                            <x-icon name="trash" class="w-4 h-4" />
                            Excluir Usuário
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
