@extends('paineldiretoria::components.layouts.app')

@section('title', 'Meu Perfil')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 pb-16 font-sans md:space-y-8 animate-fade-in">
        @include('paineldiretoria::partials.profile-subnav', ['active' => 'perfil'])

        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <form id="profileForm" action="{{ route('diretoria.profile.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <nav aria-label="breadcrumb" class="flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <a href="{{ route('diretoria.dashboard') }}" class="font-medium text-emerald-700 transition hover:underline dark:text-emerald-400">Painel da diretoria</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-60" style="duotone" />
                    <span class="font-semibold text-slate-700 dark:text-slate-200">Perfil</span>
                </nav>

                <x-profile.avatar-studio
                    :user="$user"
                    accent="emerald"
                    variant="hero"
                    :showIdentity="true"
                />
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-1">
                    <x-profile.panel-quick-links context="diretoria" accent="emerald" />

                    <p class="text-xs leading-relaxed text-slate-500 dark:text-slate-400">
                        A capa e a foto principal aparecem no bloco acima, no mesmo estilo de rede social. Os formulários ao lado
                        atualizam os seus dados de contacto e segurança.
                    </p>

                    @if ($user->roles->count() > 0)
                        <div
                            class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                            <h3
                                class="mb-4 flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <x-icon name="shield-halved" style="duotone" class="h-4 w-4 text-emerald-600" />
                                Funções
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($user->roles as $role)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-800 dark:border-emerald-900/30 dark:bg-emerald-950/40 dark:text-emerald-200">
                                        <x-icon name="user-tag" class="h-3 w-3" />
                                        {{ jubaf_role_label($role->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-8 lg:col-span-2">
                        <div
                            class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                            <div
                                class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                                <h2
                                    class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                    <x-icon name="address-card" style="duotone" class="w-5 h-5 text-emerald-600" />
                                    Informações Pessoais
                                </h2>
                            </div>
                            <div class="p-6 md:p-8 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first_name"
                                            class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Nome <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icon name="user"
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                                            </div>
                                            <input type="text" id="first_name" name="first_name"
                                                value="{{ old('first_name', $user->first_name) }}" required maxlength="120"
                                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('first_name') border-red-500 @enderror">
                                        </div>
                                        @error('first_name')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="last_name"
                                            class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            Sobrenome
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icon name="user"
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                                            </div>
                                            <input type="text" id="last_name" name="last_name"
                                                value="{{ old('last_name', $user->last_name) }}" maxlength="120"
                                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('last_name') border-red-500 @enderror">
                                        </div>
                                        @error('last_name')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email"
                                            class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            E-mail de Login <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icon name="envelope"
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                                            </div>
                                            <input type="email" id="email" name="email"
                                                value="{{ old('email', $user->email) }}" required
                                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('email') border-red-500 @enderror">
                                        </div>
                                        @error('email')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="cpf"
                                            class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                            CPF
                                        </label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icon name="fingerprint"
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                                            </div>
                                            <input type="text" id="cpf" name="cpf"
                                                value="{{ old('cpf', $user->cpf ? format_cpf_pt($user->cpf) : '') }}"
                                                inputmode="numeric" autocomplete="off" maxlength="18"
                                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('cpf') border-red-500 @enderror"
                                                placeholder="000.000.000-00">
                                        </div>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Gestão centralizada; altere
                                            aqui no seu perfil de diretoria ou via utilizadores.</p>
                                        @error('cpf')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="phone"
                                        class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Telefone de Contato
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <x-icon name="phone"
                                                class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                                        </div>
                                        <input type="text" id="phone" name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('phone') border-red-500 @enderror"
                                            placeholder="(00) 00000-0000">
                                    </div>
                                    @error('phone')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="church_phone"
                                        class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Telefone institucional (função na igreja)
                                    </label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <x-icon name="phone"
                                                class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-600 transition-colors" />
                                        </div>
                                        <input type="text" id="church_phone" name="church_phone"
                                            value="{{ old('church_phone', $user->church_phone) }}"
                                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('church_phone') border-red-500 @enderror"
                                            placeholder="Contacto na função, não o da sede">
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Dados da entidade igreja ficam
                                        no cadastro de igrejas; aqui é só o seu contacto na função.</p>
                                    @error('church_phone')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="birth_date"
                                        class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        Data de nascimento
                                    </label>
                                    <input type="date" id="birth_date" name="birth_date"
                                        value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all dark:text-white @error('birth_date') border-red-500 @enderror">
                                    @error('birth_date')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if ($user->church)
                                    <div
                                        class="md:col-span-2 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700">
                                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Igreja
                                            vinculada</p>
                                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                            {{ $user->church->name }}</p>
                                        <p class="text-xs text-slate-500 mt-1">A alteração do vínculo é feita pela diretoria ou
                                            secretaria.</p>
                                    </div>
                                @endif

                                <div class="md:col-span-2 pt-2 border-t border-gray-100 dark:border-slate-700">
                                    <h3
                                        class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                                        <x-icon name="heart-pulse" style="duotone" class="w-4 h-4 text-rose-500" />
                                        Contacto de emergência
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="emergency_contact_name"
                                                class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nome</label>
                                            <input type="text" id="emergency_contact_name" name="emergency_contact_name"
                                                value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}"
                                                maxlength="120"
                                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white @error('emergency_contact_name') border-red-500 @enderror">
                                            @error('emergency_contact_name')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="emergency_contact_phone"
                                                class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Telefone</label>
                                            <input type="text" id="emergency_contact_phone" name="emergency_contact_phone"
                                                value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}"
                                                maxlength="32"
                                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white @error('emergency_contact_phone') border-red-500 @enderror">
                                            @error('emergency_contact_phone')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="emergency_contact_relationship"
                                                class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Parentesco
                                                / relação</label>
                                            <input type="text" id="emergency_contact_relationship"
                                                name="emergency_contact_relationship"
                                                value="{{ old('emergency_contact_relationship', $user->emergency_contact_relationship) }}"
                                                maxlength="80" placeholder="Ex.: mãe, cônjuge"
                                                class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm dark:text-white @error('emergency_contact_relationship') border-red-500 @enderror">
                                            @error('emergency_contact_relationship')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                            <div
                                class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between bg-gray-50/50 dark:bg-slate-900/50">
                                <h2
                                    class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-2">
                                    <x-icon name="lock" style="duotone" class="w-5 h-5 text-rose-500" />
                                    Segurança da Conta
                                </h2>
                            </div>
                            <div class="p-6 md:p-8 space-y-6">
                                <div
                                    class="p-4 bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20 rounded-xl mb-4">
                                    <div class="flex gap-3">
                                        <x-icon name="triangle-exclamation" class="w-5 h-5 text-amber-500 flex-shrink-0" />
                                        <p class="text-sm text-amber-700 dark:text-amber-400/90">
                                            Preencha os campos abaixo apenas se desejar alterar sua senha. Caso contrário, deixe
                                            em branco.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password"
                                            class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nova
                                            Senha</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icon name="key"
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-rose-500 transition-colors" />
                                            </div>
                                            <input type="password" id="password" name="password"
                                                autocomplete="new-password"
                                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400 @error('password') border-red-500 @enderror">
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation"
                                            class="block mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Confirmar
                                            Nova Senha</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <x-icon name="key"
                                                    class="w-5 h-5 text-slate-400 group-focus-within:text-rose-500 transition-colors" />
                                            </div>
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                autocomplete="new-password"
                                                class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all dark:text-white placeholder:text-slate-400">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700">
                            <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <x-icon name="circle-info" class="w-5 h-5 text-gray-400" style="duotone" />
                                <span>Última atualização: {{ $user->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                                <a href="{{ route('diretoria.dashboard') }}"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:hover:bg-slate-600 transition-all">
                                    Cancelar
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-300/80 dark:focus:ring-emerald-900/50 shadow-lg shadow-emerald-600/20 transition-all">
                                    <x-icon name="floppy-disk" style="duotone" class="w-5 h-5" />
                                    Salvar Alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const phoneInput = document.getElementById('phone');
                if (phoneInput) {
                    phoneInput.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value.length <= 10) {
                            value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                        } else {
                            value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                        }
                        e.target.value = value;
                    });
                }

                const form = document.getElementById('profileForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const password = document.getElementById('password').value;
                        const passwordConfirmation = document.getElementById('password_confirmation').value;
                        if (password && password !== passwordConfirmation) {
                            e.preventDefault();
                            alert('As senhas não coincidem!');
                            return false;
                        }
                        if (password && password.length < 8) {
                            e.preventDefault();
                            alert('A senha deve ter no mínimo 8 caracteres!');
                            return false;
                        }
                    });
                }
            });
        </script>
    @endpush
