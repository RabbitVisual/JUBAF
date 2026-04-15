@extends('admin::layouts.admin')

@section('title', 'Criar Notificação')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="notificacoes" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>Nova Notificação</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route('admin.notificacoes.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors italic">Notificações</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Criar</span>
            </nav>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.notificacoes.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 dark:bg-slate-700 dark:text-gray-300 dark:border-slate-600 dark:hover:bg-slate-600 dark:focus:ring-slate-700 transition-colors">
                <x-icon name="arrow-left" class="w-5 h-5" style="duotone" />
                Voltar
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700/50 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 flex items-center gap-2">
            <x-icon name="pen-to-square" class="w-4 h-4 text-indigo-500" style="duotone" />
            <h3 class="text-[10px] font-black text-gray-400 dark:text-white uppercase tracking-widest italic">Preencha os dados da notificação</h3>
        </div>

        <div class="p-6 md:p-8">
            <form action="{{ route('admin.notificacoes.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Coluna Esquerda -->
                    <div class="space-y-6">
                        <div>
                            <label for="type" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">
                                Tipo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="tag" class="w-4 h-4 text-gray-400 font-sans" />
                                </div>
                                <select id="type" name="type" required class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans appearance-none">
                                    <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Informação</option>
                                    <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Sucesso</option>
                                    <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Aviso</option>
                                    <option value="error" {{ old('type') == 'error' ? 'selected' : '' }}>Erro</option>
                                    <option value="alert" {{ old('type') == 'alert' ? 'selected' : '' }}>Alerta</option>
                                    <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>Sistema</option>
                                </select>
                            </div>
                            @error('type')
                                <p class="mt-1 text-xs text-red-500 font-bold uppercase tracking-wide">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="title" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="heading" class="w-4 h-4 text-gray-400 font-sans" />
                                </div>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Ex: Manutenção Programada" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans">
                            </div>
                            @error('title')
                                <p class="mt-1 text-xs text-red-500 font-bold uppercase tracking-wide">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-3">
                                Destinatário <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-3 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="recipient_type" value="user" {{ old('recipient_type') == 'user' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-slate-600 focus:ring-indigo-500 dark:bg-slate-800">
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors font-medium">
                                        <x-icon name="user" class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" />
                                        <span>Usuário Específico</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="recipient_type" value="role" {{ old('recipient_type') == 'role' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-slate-600 focus:ring-indigo-500 dark:bg-slate-800">
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors font-medium">
                                        <x-icon name="user-shield" class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" />
                                        <span>Grupo de Permissão (Role)</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="radio" name="recipient_type" value="all" {{ old('recipient_type', 'all') == 'all' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 border-gray-300 dark:border-slate-600 focus:ring-indigo-500 dark:bg-slate-800">
                                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors font-medium">
                                        <x-icon name="users" class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" />
                                        <span>Todos os Usuários</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div id="user_select" class="hidden animate-fade-in-down">
                            <label for="user_id" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Selecione o Usuário</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="user" class="w-4 h-4 text-gray-400 font-sans" />
                                </div>
                                <select id="user_id" name="user_id" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans appearance-none">
                                    <option value="">Selecione um usuário...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="role_select" class="hidden animate-fade-in-down">
                            <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Selecione a Role</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="shield" class="w-4 h-4 text-gray-400 font-sans" />
                                </div>
                                <select id="role" name="role" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans appearance-none">
                                    <option value="">Selecione uma role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Coluna Direita -->
                    <div class="space-y-6">
                        <div>
                            <label for="message" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">
                                Mensagem <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea id="message" name="message" rows="5" required placeholder="Digite a mensagem da notificação..." class="block w-full px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans resize-none leading-relaxed">{{ old('message') }}</textarea>
                            </div>
                            @error('message')
                                <p class="mt-1 text-xs text-red-500 font-bold uppercase tracking-wide">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="module_source" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">Módulo (opcional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="layer-group" class="w-4 h-4 text-gray-400 font-sans" />
                                </div>
                                <select id="module_source" name="module_source" class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans appearance-none">
                                    <option value="">Nenhum (Geral)</option>
                                    @foreach($modules as $key => $label)
                                        <option value="{{ $key }}" {{ old('module_source') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="action_url" class="block text-[10px] font-black text-slate-400 uppercase tracking-widest italic mb-2">
                                URL de Ação (opcional)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <x-icon name="link" class="w-4 h-4 text-gray-400 font-sans" />
                                </div>
                                <input type="url" id="action_url" name="action_url" value="{{ old('action_url') }}" placeholder="https://..." class="block w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition-all font-sans">
                            </div>
                            @error('action_url')
                                <p class="mt-1 text-xs text-red-500 font-bold uppercase tracking-wide">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dica e Botões -->
                <div class="pt-6 border-t border-gray-100 dark:border-slate-700/50 space-y-6">
                    <div class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800/30 rounded-xl p-4 flex gap-4">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg h-fit">
                            <x-icon name="lightbulb" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" style="duotone" />
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-indigo-900 dark:text-indigo-200 mb-1">Como funcionam as notificações?</h4>
                            <p class="text-xs text-indigo-800/80 dark:text-indigo-300/80 leading-relaxed">
                                As notificações são enviadas em <strong>tempo real</strong> para os usuários conectados via WebSocket.
                                Caso o usuário não esteja online, ele verá a notificação assim que acessar o sistema.
                                Você pode segmentar o envio para um usuário específico, um grupo de permissão (role) ou enviar para toda a base.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.notificacoes.index') }}" class="px-6 py-3 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-100 dark:bg-slate-800 dark:border-slate-600 dark:hover:bg-slate-700 dark:focus:ring-slate-700 transition-all uppercase tracking-widest text-[10px]">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-all shadow-lg shadow-indigo-500/20 uppercase tracking-widest text-[10px]">
                            <x-icon name="paper-plane-top" class="w-5 h-5" style="duotone" />
                            Enviar Notificação
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const recipientType = document.querySelectorAll('input[name="recipient_type"]');
        const userSelect = document.getElementById('user_select');
        const roleSelect = document.getElementById('role_select');

        function toggleSelects(value) {
            // Hide all first with animation
            userSelect.classList.add('hidden');
            roleSelect.classList.add('hidden');

            // Remove required attribute to prevent validation errors on hidden fields
            const userSelectInput = userSelect.querySelector('select');
            const roleSelectInput = roleSelect.querySelector('select');

            if(userSelectInput) userSelectInput.required = false;
            if(roleSelectInput) roleSelectInput.required = false;

            if (value === 'user') {
                userSelect.classList.remove('hidden');
                if(userSelectInput) userSelectInput.required = true;
            } else if (value === 'role') {
                roleSelect.classList.remove('hidden');
                if(roleSelectInput) roleSelectInput.required = true;
            }
        }

        recipientType.forEach(radio => {
            radio.addEventListener('change', function() {
                toggleSelects(this.value);
            });
        });

        // Trigger on load
        const checked = document.querySelector('input[name="recipient_type"]:checked');
        if (checked) {
            toggleSelects(checked.value);
        }
    });
</script>
@endpush
@endsection
