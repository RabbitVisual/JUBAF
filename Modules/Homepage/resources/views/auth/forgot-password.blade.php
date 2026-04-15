@extends('homepage::layouts.homepage')

@section('title', 'Recuperar Senha')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-200 via-slate-100 to-blue-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Theme Toggle -->
    <div class="fixed top-4 right-4 z-50">
        <button type="button" id="darkModeToggle" onclick="toggleTheme()" class="relative w-12 h-12 rounded-full bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg border border-gray-200 dark:border-slate-700" aria-label="Alternar tema">
            <span id="theme-icon-sun" class="absolute transition-all duration-300">
                <x-icon name="sun" class="w-5 h-5 text-yellow-500" />
            </span>
            <span id="theme-icon-moon" class="absolute transition-all duration-300 hidden">
                <x-icon name="moon" class="w-5 h-5 text-blue-400" />
            </span>
        </button>
    </div>

    <div class="max-w-2xl w-full">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-slate-700">
            <div class="text-center mb-8">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-16 mx-auto mb-4 dark:hidden object-contain">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-16 mx-auto mb-4 hidden dark:block object-contain">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Recuperar Senha</h3>
                <p class="text-gray-600 dark:text-gray-300">Escolha uma forma de recuperação</p>
            </div>

            @if(session('status'))
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                    <div class="flex items-start gap-3">
                        <x-icon name="circle-check" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                        <div class="flex-1">
                            <p class="text-sm text-blue-800 dark:text-blue-200">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                    <div class="flex items-start gap-3">
                        <x-icon name="circle-exclamation" class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
                        <div class="flex-1">
                            <h4 class="font-semibold text-red-900 dark:text-red-200 mb-2">Erro na solicitação</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-300">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabs -->
            <div class="mb-6">
                <div class="flex gap-2 p-1 bg-gray-100 dark:bg-slate-700 rounded-xl">
                    <button type="button" id="tab-email" class="recovery-tab flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300 active" data-tab="email">
                        <div class="flex items-center justify-center gap-2">
                            <x-icon name="envelope" class="w-5 h-5 tab-icon" />
                            <span>E-mail</span>
                        </div>
                    </button>
                    <button type="button" id="tab-cpf" class="recovery-tab flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300" data-tab="cpf">
                        <div class="flex items-center justify-center gap-2">
                            <x-icon name="id-card" class="w-5 h-5 tab-icon" />
                            <span>CPF + Data Nascimento</span>
                        </div>
                    </button>
                </div>
            </div>

            <form method="POST" action="{{ route('password.email') }}" id="recovery-form" class="space-y-6">
                @csrf
                <input type="hidden" name="recovery_type" id="recovery_type" value="email">

                <!-- Email Tab Content -->
                <div id="content-email" class="tab-content">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            E-mail Cadastrado
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-icon name="envelope" class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="seu@email.com"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Enviaremos um link de redefinição de senha para o e-mail cadastrado.
                        </p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- CPF Tab Content -->
                <div id="content-cpf" class="tab-content hidden">
                    <div class="space-y-4">
                        <div>
                            <label for="cpf" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                CPF
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="id-card" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input
                                    type="text"
                                    id="cpf"
                                    name="cpf"
                                    value="{{ old('cpf') }}"
                                    placeholder="000.000.000-00"
                                    class="cpf-mask block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors"
                                />
                            </div>
                            @error('cpf')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_nascimento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Data de Nascimento
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="calendar-days" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input
                                    type="date"
                                    id="data_nascimento"
                                    name="data_nascimento"
                                    value="{{ old('data_nascimento') }}"
                                    max="{{ date('Y-m-d') }}"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors"
                                />
                            </div>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Informe a data de nascimento cadastrada no sistema.
                            </p>
                            @error('data_nascimento')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- reCAPTCHA Error -->
                @error('recaptcha')
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                        <div class="flex items-start gap-3">
                            <x-icon name="circle-exclamation" class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
                            <p class="text-sm text-red-700 dark:text-red-300">{{ $message }}</p>
                        </div>
                    </div>
                @enderror

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-900 via-[#0047AB] to-slate-900 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-950 hover:via-[#003d96] hover:to-slate-950 hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl"
                >
                    <x-icon name="paper-plane" class="w-5 h-5" />
                    Enviar Link de Redefinição
                </button>

                <!-- reCAPTCHA Component -->
                <x-recaptcha action="forgot_password" />
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <x-icon name="arrow-left" class="w-4 h-4" />
                    Voltar para o login
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Estilos para abas inativas */
    button.recovery-tab {
        color: #6b7280 !important;
        background-color: transparent !important;
        position: relative;
    }

    button.recovery-tab:hover:not(.active) {
        background-color: rgba(0, 0, 0, 0.05) !important;
        color: #4b5563 !important;
    }

    /* Estilos para aba ativa */
    button.recovery-tab.active,
    button.recovery-tab.active:focus,
    button.recovery-tab.active:hover {
        background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%) !important;
        background-color: #10b981 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4), 0 2px 4px rgba(16, 185, 129, 0.2) !important;
        transform: translateY(-1px) !important;
        border: 2px solid rgba(16, 185, 129, 0.3) !important;
    }

    button.recovery-tab.active .tab-icon,
    button.recovery-tab.active .tab-icon svg {
        color: white !important;
        stroke: white !important;
        fill: none !important;
    }

    button.recovery-tab:not(.active) .tab-icon,
    button.recovery-tab:not(.active) .tab-icon svg {
        color: #6b7280 !important;
        stroke: #6b7280 !important;
    }

    button.recovery-tab.active span {
        color: white !important;
    }

    /* Dark mode */
    .dark button.recovery-tab {
        color: #9ca3af !important;
    }

    .dark button.recovery-tab:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #d1d5db !important;
    }

    .dark button.recovery-tab.active,
    .dark button.recovery-tab.active:focus,
    .dark button.recovery-tab.active:hover {
        background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%) !important;
        background-color: #10b981 !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.5), 0 2px 4px rgba(16, 185, 129, 0.3) !important;
    }

    .dark button.recovery-tab.active .tab-icon,
    .dark button.recovery-tab.active .tab-icon svg {
        color: white !important;
        stroke: white !important;
        fill: none !important;
    }

    .dark button.recovery-tab:not(.active) .tab-icon,
    .dark button.recovery-tab:not(.active) .tab-icon svg {
        color: #9ca3af !important;
        stroke: #9ca3af !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar máscara de CPF
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    }

    // Tab Switching
    const tabs = document.querySelectorAll('.recovery-tab');
    const contents = document.querySelectorAll('.tab-content');
    const recoveryTypeInput = document.getElementById('recovery_type');
    const emailInput = document.getElementById('email');
    const cpfInputField = document.getElementById('cpf');
    const dataNascimentoInput = document.getElementById('data_nascimento');

    if (tabs.length && contents.length && recoveryTypeInput) {
        function updateTabVisuals(activeTab) {
            tabs.forEach(tab => {
                if (tab === activeTab) {
                    tab.classList.add('active');
                    tab.style.display = 'none';
                    tab.offsetHeight;
                    tab.style.display = '';
                } else {
                    tab.classList.remove('active');
                }
            });
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const targetTab = this.dataset.tab;

                updateTabVisuals(this);

                setTimeout(() => {
                    contents.forEach(c => c.classList.add('hidden'));
                    const targetContent = document.getElementById(`content-${targetTab}`);
                    if (targetContent) {
                        targetContent.classList.remove('hidden');
                    }

                    recoveryTypeInput.value = targetTab;

                    if (targetTab === 'email') {
                        if (emailInput) {
                            emailInput.setAttribute('required', 'required');
                        }
                        if (cpfInputField) {
                            cpfInputField.removeAttribute('required');
                            cpfInputField.value = '';
                        }
                        if (dataNascimentoInput) {
                            dataNascimentoInput.removeAttribute('required');
                            dataNascimentoInput.value = '';
                        }
                        if (emailInput) emailInput.focus();
                    } else {
                        if (cpfInputField) {
                            cpfInputField.setAttribute('required', 'required');
                        }
                        if (dataNascimentoInput) {
                            dataNascimentoInput.setAttribute('required', 'required');
                        }
                        if (emailInput) {
                            emailInput.removeAttribute('required');
                            emailInput.value = '';
                        }
                        if (cpfInputField) cpfInputField.focus();
                    }
                }, 10);
            });
        });

        const initialActiveTab = document.querySelector('.recovery-tab.active');
        if (initialActiveTab) {
            updateTabVisuals(initialActiveTab);
        } else if (tabs.length > 0) {
            tabs[0].classList.add('active');
        }
    }

    // Form Validation
    const form = document.getElementById('recovery-form');
    if (form && recoveryTypeInput) {
        form.addEventListener('submit', function(e) {
            const recoveryType = recoveryTypeInput.value;

            if (recoveryType === 'email') {
                if (!emailInput || !emailInput.value) {
                    e.preventDefault();
                    if (emailInput) emailInput.focus();
                    return false;
                }
            } else {
                if (!cpfInputField) {
                    e.preventDefault();
                    alert('CPF não encontrado. Por favor, recarregue a página.');
                    return false;
                }
                const cpfValue = cpfInputField.value.replace(/\D/g, '');
                if (!cpfValue || cpfValue.length !== 11) {
                    e.preventDefault();
                    alert('Por favor, informe um CPF válido com 11 dígitos.');
                    cpfInputField.focus();
                    return false;
                }
                if (!dataNascimentoInput || !dataNascimentoInput.value) {
                    e.preventDefault();
                    alert('Por favor, informe a data de nascimento.');
                    if (dataNascimentoInput) dataNascimentoInput.focus();
                    return false;
                }
            }
        });
    }
});
</script>
@endpush
@endsection
