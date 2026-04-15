@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon name="user-gear" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Meu <span class="text-indigo-600 dark:text-indigo-400">Perfil</span></span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400" />
                <span class="text-gray-900 dark:text-white font-medium">Configurações da Conta</span>
            </nav>
        </div>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna da Esquerda: Avatar e Status -->
            <div class="lg:col-span-1 space-y-8">
                <x-profile.avatar-studio :user="$user" accent="indigo" variant="card" />

                <x-profile.panel-quick-links context="admin" accent="indigo" />

                <div class="rounded-3xl border border-gray-100 bg-white p-6 text-center shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <h2 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <p class="mb-4 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        @forelse($user->roles as $role)
                            {{ jubaf_role_label($role->name) }}@if(!$loop->last), @endif
                        @empty
                            Super Admin
                        @endforelse
                    </p>
                    <div class="rounded-xl bg-slate-50 px-4 py-2 text-xs font-medium text-slate-500 dark:bg-slate-900/50 dark:text-slate-400">
                        Última atualização: {{ $user->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <!-- Roles do Usuário -->
                @if($user->roles->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <x-icon name="shield-halved" style="duotone" class="w-4 h-4 text-indigo-500" />
                        Permissões Atribuídas
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/20">
                                <x-icon name="user-tag" class="w-3 h-3" />
                                {{ jubaf_role_label($role->name) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Coluna da Direita: Formulário -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informações Pessoais -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                        <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <x-icon name="address-card" style="duotone" class="w-5 h-5 text-blue-500" />
                            Informações Pessoais
                        </h2>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Nome <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-icon name="user" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                    </div>
                                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required maxlength="120"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                </div>
                                @error('first_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="last_name" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    Sobrenome
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-icon name="user" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                    </div>
                                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" maxlength="120"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                </div>
                                @error('last_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                    E-mail de Login <span class="text-red-500">*</span>
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-icon name="envelope" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                </div>
                            </div>

                            <div>
                                <label for="cpf" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">CPF</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-icon name="fingerprint" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                    </div>
                                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $user->cpf ? format_cpf_pt($user->cpf) : '') }}" maxlength="18" autocomplete="off"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                </div>
                                @error('cpf')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Data de nascimento</label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
                                @error('birth_date')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        @if($user->church)
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Igreja vinculada</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->church->name }}</p>
                        </div>
                        @endif

                        <div class="md:col-span-2 pt-2 border-t border-gray-100 dark:border-slate-700">
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <x-icon name="heart-pulse" style="duotone" class="w-4 h-4 text-rose-500" />
                                Contacto de emergência
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="emergency_contact_name" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nome</label>
                                    <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" maxlength="120"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
                                    @error('emergency_contact_name')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="emergency_contact_phone" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Telefone</label>
                                    <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" maxlength="32"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
                                    @error('emergency_contact_phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="emergency_contact_relationship" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Parentesco / relação</label>
                                    <input type="text" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $user->emergency_contact_relationship) }}" maxlength="80" placeholder="Ex.: mãe, cônjuge"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white">
                                    @error('emergency_contact_relationship')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="phone" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Telefone de Contato
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="phone" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                </div>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="phone-mask w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400"
                                    placeholder="(00) 00000-0000">
                            </div>
                        </div>

                        <div>
                            <label for="church_phone" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Telefone (função / igreja)
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="phone" class="w-5 h-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors" />
                                </div>
                                <input type="text" id="church_phone" name="church_phone" value="{{ old('church_phone', $user->church_phone) }}"
                                    class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                            </div>
                            @error('church_phone')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Segurança -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                        <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                            <x-icon name="lock" style="duotone" class="w-5 h-5 text-rose-500" />
                            Segurança da Conta
                        </h2>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        <div class="p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20 rounded-xl mb-4">
                            <div class="flex gap-3">
                                <x-icon name="triangle-exclamation" class="w-5 h-5 text-amber-500 flex-shrink-0" />
                                <p class="text-sm text-amber-700 dark:text-amber-400/90">
                                    Preencha os campos abaixo apenas se desejar alterar sua senha atual. Caso contrário, deixe em branco.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nova Senha</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-icon name="key" class="w-5 h-5 text-slate-400 group-focus-within:text-rose-500 transition-colors" />
                                    </div>
                                    <input type="password" id="password" name="password" autocomplete="new-password"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Confirmar Nova Senha</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-icon name="key" class="w-5 h-5 text-slate-400 group-focus-within:text-rose-500 transition-colors" />
                                    </div>
                                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="flex items-center justify-end gap-3 pt-4">
                     <a href="{{ route('admin.dashboard') }}" class="px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 active:scale-95 group">
                        <x-icon name="floppy-disk" style="duotone" class="w-5 h-5 group-hover:rotate-12 transition-transform" />
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.querySelector('.phone-mask');
        if (phoneInput) {
            phoneInput.addEventListener('input', function (e) {
                var x = e.target.value.replace(/\D/g, '').match(/(\d{0,2})(\d{0,5})(\d{0,4})/);
                e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            });
        }
    });
</script>
@endpush
@endsection
