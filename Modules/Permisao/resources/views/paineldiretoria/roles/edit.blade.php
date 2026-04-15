@extends($layout)

@section('title', 'Editar Role')

@section('content')
@php $roleLocked = jubaf_role_is_protected($role->name); @endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('permisao::paineldiretoria.partials.subnav', ['active' => 'roles'])

    @include('permisao::paineldiretoria.partials.rbac-context', ['step' => 'roles'])

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Painel diretoria</p>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2 mt-1">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 via-indigo-600 to-violet-700 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 ring-1 ring-white/20">
                    <x-icon name="pen-to-square" class="w-6 h-6 text-white" style="duotone" />
                </div>
                <span>Editar <span class="text-indigo-600 dark:text-indigo-400">{{ jubaf_role_label($role->name) }}</span></span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 flex-wrap">
                <a href="{{ route('diretoria.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Diretoria</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 shrink-0" />
                <a href="{{ route('diretoria.seguranca.hub') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Segurança</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 shrink-0" />
                <a href="{{ route($routePrefix.'.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Funções</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 shrink-0" />
                <span class="text-gray-900 dark:text-white font-medium truncate max-w-[12rem] sm:max-w-none" title="{{ $role->name }}">{{ $role->name }}</span>
            </nav>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 dark:text-gray-300 dark:bg-slate-700 dark:border-slate-600 dark:hover:bg-slate-600 transition-all shadow-sm">
            <x-icon name="arrow-left" class="w-5 h-5" />
            Voltar
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-3">
            <div class="p-2 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </div>
            <h2 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">Atualizar Configurações</h2>
        </div>
        <form action="{{ route($routePrefix.'.update', $role) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="flex items-center gap-2 mb-2 text-sm font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        Identificador técnico (slug) <span class="text-red-500 font-black">*</span>
                    </label>
                    @if($roleLocked)
                        <input type="hidden" name="name" value="{{ $role->name }}">
                        <input type="text" id="name" value="{{ $role->name }}" readonly class="bg-slate-100 border border-slate-200 text-slate-600 text-sm rounded-xl block w-full p-3 dark:bg-slate-900 dark:border-slate-600 dark:text-slate-300 cursor-not-allowed">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Funções do sistema não podem ter o identificador alterado. O nome exibido aos usuários vem do catálogo JUBAF.</p>
                    @else
                        <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}" required class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 block w-full p-3 dark:bg-slate-700 dark:border-slate-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-amber-500/20 dark:focus:border-amber-500 transition-all shadow-sm @error('name') border-red-500 dark:border-red-600 @enderror">
                    @endif
                    @error('name')
                        <p class="mt-2 text-sm font-black text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center gap-2 text-sm font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            Mapa de Privilégios
                        </label>
                        <a href="{{ route($permissionsRoutePrefix.'.index') }}" class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 font-black uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/30 px-3 py-1 rounded-lg ring-1 ring-indigo-100 dark:ring-indigo-800/50 transition-all">
                            Gesto de Permissões →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[500px] overflow-y-auto border border-gray-100 dark:border-slate-700 rounded-2xl p-4 bg-gray-50/30 dark:bg-slate-900/20 custom-scrollbar">
                        @foreach($permissions as $module => $modulePermissions)
                        @php
                            $moduleDisplay = str_replace(['-', '_'], ' ', $module);
                            $moduleDisplay = ucwords($moduleDisplay);
                            $hasAll = collect($modulePermissions)->every(fn($p) => $role->hasPermissionTo($p['name']));
                        @endphp
                            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-4 hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-300 group shadow-sm">
                                <div class="flex items-center justify-between mb-4 border-b border-gray-50 dark:border-slate-700/50 pb-3">
                                    <h4 class="text-xs font-black text-gray-900 dark:text-white flex items-center gap-2 uppercase tracking-wider">
                                        <div class="p-1.5 {{ $hasAll ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400' }} rounded-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                                            </svg>
                                        </div>
                                        {{ $moduleDisplay }}
                                    </h4>
                                    <span class="text-[10px] font-black {{ $hasAll ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20' : 'text-gray-400 bg-gray-50 dark:bg-slate-900/50' }} px-2 py-0.5 rounded-lg ring-1 ring-black/5">{{ count($modulePermissions) }} privilégios</span>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    @foreach($modulePermissions as $permission)
                                        @php $isSet = $role->hasPermissionTo($permission['name']); @endphp
                                        <label class="flex items-start gap-3 cursor-pointer {{ $isSet ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : 'hover:bg-gray-50 dark:hover:bg-slate-700/50' }} p-2 rounded-lg transition-all group/perm relative ring-1 {{ $isSet ? 'ring-indigo-100 dark:ring-indigo-900/30' : 'ring-transparent hover:ring-gray-100' }}">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission['id'] }}" id="perm_{{ $permission['id'] }}" {{ $isSet ? 'checked' : '' }} class="w-4 h-4 mt-0.5 text-indigo-600 bg-gray-100 border-gray-300 rounded-lg focus:ring-indigo-500/20 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600 transition-all cursor-pointer">
                                            <div class="flex-1 min-w-0">
                                                <span class="text-xs font-bold {{ $isSet ? 'text-indigo-700 dark:text-indigo-400' : 'text-gray-700 dark:text-gray-300' }} group-hover/perm:text-indigo-600 dark:group-hover/perm:text-indigo-400 transition-colors block">
                                                    {{ $permission['display_name'] ?? $permission['name'] }}
                                                </span>
                                                <p class="text-[9px] text-gray-400 dark:text-gray-500 font-mono mt-0.5 break-all opacity-0 group-hover/perm:opacity-100 transition-opacity">{{ $permission['name'] }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-black text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 uppercase tracking-widest transition-all">
                        Descartar
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 text-sm font-black text-white bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl hover:from-amber-600 hover:to-orange-700 focus:ring-4 focus:ring-amber-300 dark:focus:ring-amber-900 transition-all shadow-lg shadow-amber-200 dark:shadow-none transform hover:scale-[1.03] active:scale-95 uppercase tracking-wide">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0h-1.5A2.25 2.25 0 0012 1.5h-1.5m9 0h-1.5A2.25 2.25 0 0012 1.5H6A2.25 2.25 0 003.75 3.75v1.5" />
                        </svg>
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </form>
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
