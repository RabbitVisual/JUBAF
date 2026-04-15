@extends('layouts.app')

@section('title', 'Criar Token de API')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="plus" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Criar Token</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route('admin.api.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">API</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Novo Token</span>
            </nav>
        </div>
        <a href="{{ route('admin.api.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden font-sans">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="key" class="w-4 h-4 text-indigo-500" style="duotone" />
            <h2 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Informações do Token</h2>
        </div>

        <form action="{{ route('admin.api.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">
                <!-- Usuário -->
                <div>
                    <label for="user_id" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        Usuário <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 dark:bg-slate-900 dark:border-slate-700 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500 @error('user_id') border-red-500 dark:border-red-600 @enderror font-sans shadow-sm transition-all hover:bg-white dark:hover:bg-slate-800">
                        <option value="">Selecione um usuário...</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="mt-2 text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nome do Token -->
                <div>
                    <label for="name" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        Nome do Token <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="tag" class="w-4 h-4 text-gray-400" style="duotone" />
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans shadow-sm hover:bg-white dark:hover:bg-slate-800 @error('name') border-red-500 dark:border-red-600 @enderror" placeholder="Ex: App Mobile Android">
                    </div>
                    @error('name')
                    <p class="mt-2 text-xs font-bold text-red-600 dark:text-red-400 uppercase tracking-wide">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div>
                    <label for="description" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        Descrição (Opcional)
                    </label>
                    <textarea name="description" id="description" rows="3" class="block w-full p-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans shadow-sm hover:bg-white dark:hover:bg-slate-800 placeholder-gray-400" placeholder="Descreva a finalidade deste token...">{{ old('description') }}</textarea>
                </div>

                <!-- Permissões (Abilities) -->
                <div>
                    <label class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        Permissões e Escopo
                    </label>
                    <div class="p-4 bg-gray-50 dark:bg-slate-900/50 rounded-2xl border border-gray-200 dark:border-slate-700 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-white dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-slate-700 group">
                            <input type="checkbox" name="abilities[]" value="*" checked class="w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-slate-700 dark:border-slate-600">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition-colors">Acesso Total (*)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-white dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-slate-700 group">
                            <input type="checkbox" name="abilities[]" value="demandas:read" class="w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-slate-700 dark:border-slate-600">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition-colors">Ler Demandas</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-white dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-slate-700 group">
                            <input type="checkbox" name="abilities[]" value="demandas:write" class="w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-slate-700 dark:border-slate-600">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition-colors">Escrever Demandas</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 rounded-xl hover:bg-white dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-gray-200 dark:hover:border-slate-700 group">
                            <input type="checkbox" name="abilities[]" value="ordens:read" class="w-5 h-5 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-slate-700 dark:border-slate-600">
                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 transition-colors">Ler Ordens</span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Data de Expiração -->
                    <div>
                        <label for="expires_at" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                            Data de Expiração (Opcional)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <x-icon name="calendar" class="w-4 h-4 text-gray-400" style="duotone" />
                            </div>
                            <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at') }}" min="{{ date('Y-m-d') }}" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans shadow-sm hover:bg-white dark:hover:bg-slate-800">
                        </div>
                    </div>

                    <!-- Rate Limit -->
                    <div>
                        <label for="rate_limit" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                            Limite (Req/Min)
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <x-icon name="gauge" class="w-4 h-4 text-gray-400" style="duotone" />
                            </div>
                            <input type="number" name="rate_limit" id="rate_limit" value="{{ old('rate_limit', 60) }}" min="1" max="1000" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans shadow-sm hover:bg-white dark:hover:bg-slate-800">
                        </div>
                    </div>
                </div>

                <!-- IP Whitelist -->
                <div>
                    <label for="ip_whitelist" class="block mb-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic">
                        IP Whitelist (Opcional - Separar por vírgula)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="globe" class="w-4 h-4 text-gray-400" style="duotone" />
                        </div>
                        <input type="text" name="ip_whitelist" id="ip_whitelist" value="{{ old('ip_whitelist') }}" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans shadow-sm hover:bg-white dark:hover:bg-slate-800 placeholder-gray-400" placeholder="Ex: 192.168.1.1, 10.0.0.5">
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-slate-700">
                    <a href="{{ route('admin.api.index') }}" class="px-6 py-3 text-sm font-bold text-slate-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-600 dark:hover:bg-slate-700 transition-all uppercase tracking-widest">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest">
                        <x-icon name="check" class="w-5 h-5" />
                        Criar Token
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
