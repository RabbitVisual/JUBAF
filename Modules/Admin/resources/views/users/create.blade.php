@extends('admin::components.layouts.master')

@section('title', 'Novo Membro')

@section('content')
<div class="space-y-8">
    <!-- Hero -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white shadow-xl border border-gray-700/50">
        <div class="absolute inset-0 dash-pattern opacity-10"></div>
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-600/20 to-transparent"></div>
        <div class="relative p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <span class="px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-wider">Membros</span>
                    <span class="px-3 py-1 rounded-full bg-green-500/20 border border-green-400/30 text-green-300 text-xs font-bold uppercase tracking-wider">Cadastro</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Novo Membro</h1>
                <p class="text-gray-300 max-w-xl">Cadastre um novo membro no sistema. Preencha os campos obrigatórios. O membro receberá notificação se você vincular parentes que já estão no sistema.</p>
            </div>
            <div class="flex-shrink-0 flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold hover:bg-white/20 transition-colors">
                    <x-icon name="arrow-left" class="w-5 h-5" />
                    Voltar
                </a>
                <button type="submit" form="user-create-form" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white text-gray-900 font-bold hover:bg-gray-100 transition-all shadow-lg shadow-white/10">
                    <x-icon name="check" class="w-5 h-5 text-blue-600" />
                    Salvar
                </button>
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <div class="flex">
                <x-icon name="circle-exclamation" class="w-6 h-6 text-red-500 mr-3 shrink-0" />
                <div>
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Corrija os erros abaixo:</h3>
                    <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4 flex items-start gap-3">
        <x-icon name="information-circle" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
        <p class="text-sm text-blue-800 dark:text-blue-200">Preencha os campos obrigatórios. O membro receberá notificação se você vincular parentes que já estão no sistema.</p>
    </div>

    <form id="user-create-form" action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-address-form onsubmit="window.dispatchEvent(new CustomEvent('loading-overlay:show', { detail: { message: 'Salvando membro...' } }))">
        @csrf

        <!-- Section 1: Identificação -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-50 dark:bg-blue-900/20 rounded-bl-full -mr-8 -mt-8"></div>
            <div class="relative flex items-start gap-4 mb-6">
                <div class="w-12 h-12 rounded-3xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <x-icon name="user" class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Identificação Pessoal</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Dados básicos do membro.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <!-- Sobrenome -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sobrenome *</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <!-- CPF -->
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF</label>
                    <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}" data-mask="cpf"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <!-- Data Nascimento -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Nascimento</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <!-- Gênero -->
                 <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gênero</label>
                    <select name="gender" id="gender" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Selecione...</option>
                        <option value="M" {{ (isset($user) ? $user->gender : old('gender')) == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ (isset($user) ? $user->gender : old('gender')) == 'F' ? 'selected' : '' }}>Feminino</option>
                    </select>
                </div>
                 <!-- Estado Civil -->
                 <div>
                    <label for="marital_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado Civil</label>
                    <select name="marital_status" id="marital_status" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Selecione...</option>
                        <option value="solteiro" {{ old('marital_status') == 'solteiro' ? 'selected' : '' }}>Solteiro(a)</option>
                        <option value="casado" {{ old('marital_status') == 'casado' ? 'selected' : '' }}>Casado(a)</option>
                        <option value="divorciado" {{ old('marital_status') == 'divorciado' ? 'selected' : '' }}>Divorciado(a)</option>
                        <option value="viuvo" {{ old('marital_status') == 'viuvo' ? 'selected' : '' }}>Viúvo(a)</option>
                         <option value="uniao_estavel" {{ old('marital_status') == 'uniao_estavel' ? 'selected' : '' }}>União Estável</option>
                    </select>
                </div>
                <!-- Foto -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Foto de Perfil</label>
                    <div class="flex items-center gap-4">
                        <div class="shrink-0">
                            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                <x-icon name="user" class="w-8 h-8" />
                            </div>
                        </div>
                        <label class="cursor-pointer bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 border border-gray-300 dark:border-gray-600 py-2 px-4 rounded-xl font-medium shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <span>Upload de foto</span>
                            <input type="file" name="photo" class="hidden" accept="image/*">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Contato e Endereço -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-xl text-purple-600 dark:text-purple-400">
                    <x-icon name="phone" class="w-6 h-6" /> <!-- Assuming phone or map-pin icon -->
                </div>
                <div>
                     <h2 class="text-lg font-bold text-gray-900 dark:text-white">Contato e Endereço</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Informações de localização e contato.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                 <!-- Celular -->
                 <div>
                    <label for="cellphone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Celular (WhatsApp)</label>
                    <input type="text" name="cellphone" id="cellphone" value="{{ old('cellphone') }}" data-mask="phone"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                 <!-- Telefone Fixo -->
                 <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone Fixo</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" data-mask="phone"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                <!-- CEP -->
                 <div>
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP</label>
                     <div class="relative">
                        <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" data-mask="cep"
                               class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500 pr-10">
                        <div id="zip_code-loading" class="absolute inset-y-0 right-0 flex items-center pr-3 hidden">
                            <x-icon name="arrows-rotate" class="w-4 h-4 text-purple-500 animate-spin" />
                        </div>
                    </div>
                    <p id="zip_code-error" class="mt-1 text-xs text-red-500 hidden"></p>
                    <p id="zip_code-success" class="mt-1 text-xs text-green-500 hidden"></p>
                </div>
                 <!-- Endereço -->
                 <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Endereço</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                 <!-- Número -->
                 <div>
                    <label for="address_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número</label>
                    <input type="text" name="address_number" id="address_number" value="{{ old('address_number') }}"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                 <!-- Complemento -->
                 <div>
                    <label for="address_complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Complemento</label>
                    <input type="text" name="address_complement" id="address_complement" value="{{ old('address_complement') }}"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                 <!-- Bairro -->
                 <div>
                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bairro</label>
                    <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}"
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-purple-500 focus:border-purple-500">
                </div>
                 <!-- Cidade / UF -->
                 <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" readonly
                               class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 cursor-not-allowed focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div>
                        <label for="state" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">UF</label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}" maxlength="2" readonly
                               class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200 cursor-not-allowed focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
            </div>
        </div>


        <!-- Section 4: Função e igreja (JUBAF) -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
            <div class="flex items-start gap-4 mb-6">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl text-indigo-600 dark:text-indigo-400">
                    <x-icon name="id-badge" class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Função e igreja local</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Papel no sistema e vínculo com a igreja federada (ASBAF), conforme cadastro JUBAF.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Função *</label>
                    <select name="role_id" id="role_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecione...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ (string) old('role_id') === (string) $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(isset($churches) && $churches->isNotEmpty())
                    <div>
                        <label for="church_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Igreja local (ASBAF)</label>
                        <select name="church_id" id="church_id" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Não vinculado</option>
                            @foreach($churches as $c)
                                <option value="{{ $c->id }}" {{ (string) old('church_id') === (string) $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="md:col-span-2 flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">Membro ativo (pode acessar o sistema)</label>
                </div>
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações internas</label>
                    <textarea name="notes" id="notes" rows="3" placeholder="Notas visíveis apenas para administradores"
                              class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 5: Segurança -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 md:p-8">
             <div class="flex items-start gap-4 mb-6">
                 <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-xl text-red-600 dark:text-red-400">
                    <x-icon name="lock" class="w-6 h-6" />
                </div>
                <div>
                     <h2 class="text-lg font-bold text-gray-900 dark:text-white">Segurança (Senha)</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Defina a senha de acesso inicial do membro.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Senha *</label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirmar Senha *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4 pt-4">
             <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-linear-to-r from-blue-600 to-blue-700 text-white rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 transition-all transform hover:scale-[1.02]">
                Salvar Membro
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
</script>
@endpush
@endsection

