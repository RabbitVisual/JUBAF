@extends($layout)

@section('title', 'Detalhes da Role')

@section('content')
@php
    $tier = jubaf_role_tier($role->name);
    $tierBadges = [
        'super_admin' => 'Super admin · /admin',
        'directorate' => 'Diretoria · /co-admin',
        'operational' => 'Operacional',
        'custom' => 'Personalizada',
    ];
@endphp
<div class="space-y-6 md:space-y-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 via-indigo-600 to-violet-700 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 ring-1 ring-white/20">
                    <x-icon name="eye" class="w-6 h-6 text-white" style="duotone" />
                </div>
                <span>{{ jubaf_role_label($role->name) }}</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                <a href="{{ route(user_can_access_admin_panel() ? 'admin.dashboard' : 'diretoria.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 shrink-0" />
                <a href="{{ route($routePrefix.'.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Funções</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 shrink-0" />
                <span class="text-gray-900 dark:text-white font-medium font-mono text-xs">{{ $role->name }}</span>
            </nav>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 dark:text-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:hover:bg-slate-600 transition-all shadow-sm">
                <x-icon name="arrow-left" class="w-5 h-5" />
                Lista
            </a>
            <a href="{{ route($routePrefix.'.edit', $role) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md">
                <x-icon name="pen-to-square" class="w-5 h-5" />
                Editar permissões
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Permissões da função</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold">{{ $tierBadges[$tier] ?? 'Função' }}</span>
                        @if(jubaf_role_is_protected($role->name))
                            <span class="ml-2 text-amber-600 dark:text-amber-400 font-semibold">· Protegida</span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500"></div>
                    <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Ativo</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-slate-600"></div>
                    <span class="text-[10px] font-black text-gray-500 dark:text-gray-400 uppercase tracking-widest">Restrito</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-900/30">
                <div class="flex items-center gap-4">
                    <div class="text-center bg-white dark:bg-slate-800 px-4 py-2 rounded-lg shadow-sm border border-indigo-50 ring-1 ring-black/5">
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter">Atribuídas</p>
                        <p class="text-xl font-black text-indigo-600 dark:text-indigo-400">{{ $role->permissions->count() }}</p>
                    </div>
                    <div class="text-center bg-white dark:bg-slate-800 px-4 py-2 rounded-lg shadow-sm border border-indigo-50 ring-1 ring-black/5">
                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-tighter">Do Sistema</p>
                        <p class="text-xl font-black text-gray-500 dark:text-gray-400">{{ \Spatie\Permission\Models\Permission::count() }}</p>
                    </div>
                </div>
                <a href="{{ route($permissionsRoutePrefix.'.index') }}" class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 uppercase tracking-widest bg-white dark:bg-slate-800 px-3 py-2 rounded-lg shadow-sm border border-indigo-50 ring-1 ring-black/5 text-center">
                    Gerenciar Mapa Global →
                </a>
            </div>
            <div class="space-y-8 max-h-[600px] overflow-y-auto custom-scrollbar pr-2">
                @foreach($permissions as $module => $modulePermissions)
                @php
                    $moduleDisplay = str_replace(['-', '_'], ' ', $module);
                    $moduleDisplay = ucwords($moduleDisplay);
                    $modulePermissionsCount = collect($modulePermissions)->filter(fn($p) => $role->hasPermissionTo($p['name']))->count();
                    $allAssigned = $modulePermissionsCount === count($modulePermissions);
                @endphp
                    <div class="pb-8 border-b border-gray-100 dark:border-slate-700 last:border-0 last:pb-0">
                        <div class="flex items-center justify-between mb-5">
                            <h6 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-3 uppercase tracking-wider">
                                <div class="p-2 {{ $allAssigned ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' }} rounded-xl ring-1 ring-black/5 transition-transform duration-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                    </svg>
                                </div>
                                {{ $moduleDisplay }}
                            </h6>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $allAssigned ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300' : 'bg-gray-100 text-gray-500 dark:bg-slate-700/50 dark:text-gray-400' }} ring-1 ring-black/5">
                                {{ $modulePermissionsCount }} / {{ count($modulePermissions) }} Privilégios
                            </span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($modulePermissions as $permission)
                                @php $isAssigned = $role->hasPermissionTo($permission['name']); @endphp
                                <div class="flex items-start gap-3 p-3 border {{ $isAssigned ? 'border-emerald-100 bg-emerald-50/20 dark:border-emerald-900/30' : 'border-gray-100 dark:border-slate-800' }} rounded-xl transition-all duration-300 group hover:shadow-sm">
                                    @if($isAssigned)
                                        <div class="mt-0.5 flex-shrink-0 w-5 h-5 bg-emerald-100 dark:bg-emerald-900/50 rounded-lg flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-black text-gray-900 dark:text-white tracking-tight">{{ $permission['display_name'] ?? $permission['name'] }}</p>
                                            <p class="text-[9px] text-emerald-600/70 dark:text-emerald-400/50 font-mono mt-0.5 break-all">{{ $permission['name'] }}</p>
                                        </div>
                                    @else
                                        <div class="mt-0.5 flex-shrink-0 w-5 h-5 bg-gray-100 dark:bg-slate-700/50 rounded-lg flex items-center justify-center text-gray-400 dark:text-gray-600">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0 opacity-40">
                                            <p class="text-xs font-bold text-gray-400 dark:text-gray-500 tracking-tight">{{ $permission['display_name'] ?? $permission['name'] }}</p>
                                            <p class="text-[9px] text-gray-400 dark:text-gray-600 font-mono mt-0.5 break-all line-through">{{ $permission['name'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>
@endpush
@endsection
