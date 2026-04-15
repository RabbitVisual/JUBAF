@extends('homepage::layouts.homepage')

@section('title', 'Login')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-slate-200 via-slate-100 to-blue-100 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <!-- Theme Toggle -->
        <div class="fixed top-4 right-4 z-50">
            <button type="button" id="darkModeToggle" onclick="toggleTheme()"
                class="relative w-12 h-12 rounded-full bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center justify-center transition-all duration-300 hover:scale-110 shadow-lg border border-gray-200 dark:border-slate-700"
                aria-label="Alternar tema">
                <span id="theme-icon-sun" class="absolute transition-all duration-300">
                    <x-icon name="sun" class="w-5 h-5 text-yellow-500" />
                </span>
                <span id="theme-icon-moon" class="absolute transition-all duration-300 hidden">
                    <x-icon name="moon" class="w-5 h-5 text-blue-400" />
                </span>
            </button>
        </div>

        <div class="max-w-6xl w-full grid lg:grid-cols-2 gap-8 items-center">
            <!-- Left Side - Branding -->
            <div
                class="jubaf-hero-surface hidden lg:flex flex-col items-center justify-center p-8 rounded-2xl shadow-2xl relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute inset-0"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M50 50 L70 30 L90 50 L70 70 Z\'/%3E%3Cpath d=\'M30 30 L50 10 L70 30 L50 50 Z\'/%3E%3Cpath d=\'M30 70 L50 50 L70 70 L50 90 Z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-size: 100px 100px;">
                    </div>
                </div>

                <div class="relative z-10 text-center space-y-6">
                    <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}"
                        alt="{{ \App\Support\SiteBranding::siteName() }}"
                        class="w-full max-w-xs mx-auto mb-6 drop-shadow-2xl object-contain">
                    <h2 class="text-3xl font-bold mb-4">Bem-vindo</h2>
                    <p class="text-lg text-white/90 leading-relaxed">
                        {{ \App\Support\SiteBranding::siteTagline() }}
                    </p>

                    <div class="mt-8 space-y-4 text-left">
                        <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                            <x-icon name="heart" class="w-6 h-6 text-sky-200 flex-shrink-0 mt-0.5" />
                            <div>
                                <strong class="block mb-1">SOMOS UM</strong>
                                <p class="text-sm text-white/80">Um só corpo em Cristo</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                            <x-icon name="users" class="w-6 h-6 text-sky-200 flex-shrink-0 mt-0.5" />
                            <div>
                                <strong class="block mb-1">Juventude Batista</strong>
                                <p class="text-sm text-white/80">Feira de Santana e região</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                            <x-icon name="calendar-days" class="w-6 h-6 text-sky-200 flex-shrink-0 mt-0.5" />
                            <div>
                                <strong class="block mb-1">Eventos & vida em grupo</strong>
                                <p class="text-sm text-white/80">Caminhada espiritual juntos</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="w-full max-w-md mx-auto">
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-slate-700">
                    <div class="text-center mb-8">
                        <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}"
                            alt="{{ \App\Support\SiteBranding::siteName() }}"
                            class="h-16 mx-auto mb-4 dark:hidden object-contain">
                        <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}"
                            alt="{{ \App\Support\SiteBranding::siteName() }}"
                            class="h-16 mx-auto mb-4 hidden dark:block object-contain">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Acessar painel</h3>
                        <p class="text-gray-600 dark:text-gray-300">Entre com suas credenciais</p>
                    </div>

                    @if ($errors->any())
                        <div
                            class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                            <div class="flex items-start gap-3">
                                <x-icon name="circle-exclamation"
                                    class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
                                <div class="flex-1">
                                    <h4 class="font-semibold text-red-900 dark:text-red-200 mb-2">Erro ao fazer login</h4>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-300">
                                        @foreach ($errors->all() as $error)
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
                            <button type="button" id="tab-email"
                                class="login-tab flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300 active"
                                data-tab="email">
                                <div class="flex items-center justify-center gap-2">
                                    <x-icon name="envelope" class="w-5 h-5 tab-icon" />
                                    <span>E-mail</span>
                                </div>
                            </button>
                            <button type="button" id="tab-cpf"
                                class="login-tab flex-1 py-3 px-4 rounded-lg font-semibold transition-all duration-300"
                                data-tab="cpf">
                                <div class="flex items-center justify-center gap-2">
                                    <x-icon name="id-card" class="w-5 h-5 tab-icon" />
                                    <span>CPF</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login') }}" id="login-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="login_type" id="login_type" value="email">

                        <!-- Email Tab Content -->
                        <div id="content-email" class="tab-content">
                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    E-mail
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="envelope" class="w-5 h-5 text-gray-400" />
                                    </div>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                        autofocus placeholder="seu@email.com"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors" />
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- CPF Tab Content -->
                        <div id="content-cpf" class="tab-content hidden">
                            <div>
                                <label for="cpf"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    CPF
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-icon name="id-card" class="w-5 h-5 text-gray-400" />
                                    </div>
                                    <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}"
                                        placeholder="000.000.000-00"
                                        class="cpf-mask block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors" />
                                </div>
                                @error('cpf')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Senha
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <x-icon name="lock" class="w-5 h-5 text-gray-400" />
                                </div>
                                <input type="password" id="password" name="password" required
                                    placeholder="Digite sua senha"
                                    class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors" />
                                <button type="button" id="toggle-password"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                    aria-label="Mostrar senha">
                                    <span id="eye-icon">
                                        <x-icon name="eye" class="w-5 h-5" />
                                    </span>
                                    <span id="eye-slash-icon" class="hidden">
                                        <x-icon name="eye-slash" class="w-5 h-5" />
                                    </span>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- reCAPTCHA Error -->
                        @error('recaptcha')
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                <div class="flex items-start gap-3">
                                    <x-icon name="circle-exclamation"
                                        class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" />
                                    <p class="text-sm text-red-700 dark:text-red-300">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="remember" name="remember"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 dark:bg-slate-700 border-gray-300 dark:border-slate-600 rounded focus:ring-blue-600 focus:ring-2" />
                                <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                                    Lembrar-me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                Esqueceu a senha?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-900 via-[#0047AB] to-slate-900 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-950 hover:via-[#003d96] hover:to-slate-950 hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                            <x-icon name="right-to-bracket" class="w-5 h-5" />
                            Entrar
                        </button>

                        <!-- reCAPTCHA Component -->
                        <x-recaptcha action="login" />
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ route('homepage') }}"
                            class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            <x-icon name="arrow-left" class="w-4 h-4" />
                            Voltar para a página inicial
                        </a>
                    </div>
                </div>

                <!-- Botões de Auto-Login (Apenas em desenvolvimento) -->
                @if (config('app.env') !== 'production')
                    <div
                        class="mt-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4">
                        <p
                            class="text-center text-sm font-semibold text-yellow-900 dark:text-yellow-200 mb-3 flex items-center justify-center gap-2">
                            <x-icon name="information-circle" class="w-5 h-5" />
                            Contas Demo (Teste)
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <a href="{{ route('auto-login', 'admin') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 text-blue-600 dark:text-blue-400 border-2 border-blue-600 rounded-xl font-semibold hover:bg-blue-50 dark:hover:bg-slate-600 hover:scale-105 transition-all duration-300 text-sm">
                                <x-icon name="user-shield" class="w-4 h-4" />
                                Super Admin
                            </a>
                            <a href="{{ route('auto-login', 'coadmin') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 text-cyan-600 dark:text-cyan-400 border-2 border-cyan-500 rounded-xl font-semibold hover:bg-cyan-50 dark:hover:bg-slate-600 hover:scale-105 transition-all duration-300 text-sm">
                                <x-icon name="user" class="w-4 h-4" />
                                Diretoria (demo)
                            </a>
                            <a href="{{ route('auto-login', 'jovens') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 text-violet-600 dark:text-violet-400 border-2 border-violet-500 rounded-xl font-semibold hover:bg-violet-50 dark:hover:bg-slate-600 hover:scale-105 transition-all duration-300 text-sm">
                                <x-icon name="users" class="w-4 h-4" />
                                Jovens (Unijovem)
                            </a>
                            <a href="{{ route('auto-login', 'lider') }}"
                                class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 text-amber-600 dark:text-amber-400 border-2 border-amber-500 rounded-xl font-semibold hover:bg-amber-50 dark:hover:bg-slate-600 hover:scale-105 transition-all duration-300 text-sm">
                                <x-icon name="users" class="w-4 h-4" />
                                Lider
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Estilos para abas inativas */
            button.login-tab {
                color: #6b7280 !important;
                background-color: transparent !important;
                position: relative;
            }

            button.login-tab:hover:not(.active) {
                background-color: rgba(0, 0, 0, 0.05) !important;
                color: #4b5563 !important;
            }

            /* Estilos para aba ativa - ALTA ESPECIFICIDADE */
            button.login-tab.active,
            button.login-tab.active:focus,
            button.login-tab.active:hover {
                background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%) !important;
                background-color: #10b981 !important;
                color: white !important;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4), 0 2px 4px rgba(16, 185, 129, 0.2) !important;
                transform: translateY(-1px) !important;
                border: 2px solid rgba(16, 185, 129, 0.3) !important;
            }

            button.login-tab.active .tab-icon,
            button.login-tab.active .tab-icon svg {
                color: white !important;
                stroke: white !important;
                fill: none !important;
            }

            button.login-tab:not(.active) .tab-icon,
            button.login-tab:not(.active) .tab-icon svg {
                color: #6b7280 !important;
                stroke: #6b7280 !important;
            }

            button.login-tab.active span {
                color: white !important;
            }

            /* Dark mode */
            .dark button.login-tab {
                color: #9ca3af !important;
            }

            .dark button.login-tab:hover:not(.active) {
                background-color: rgba(255, 255, 255, 0.05) !important;
                color: #d1d5db !important;
            }

            .dark button.login-tab.active,
            .dark button.login-tab.active:focus,
            .dark button.login-tab.active:hover {
                background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%) !important;
                background-color: #10b981 !important;
                color: white !important;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.5), 0 2px 4px rgba(16, 185, 129, 0.3) !important;
            }

            .dark button.login-tab.active .tab-icon,
            .dark button.login-tab.active .tab-icon svg {
                color: white !important;
                stroke: white !important;
                fill: none !important;
            }

            .dark button.login-tab:not(.active) .tab-icon,
            .dark button.login-tab:not(.active) .tab-icon svg {
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
                    // Se jQuery e mask plugin estiverem disponíveis
                    if (typeof $ !== 'undefined' && $.fn.mask) {
                        $(cpfInput).mask('000.000.000-00');
                    } else {
                        // Fallback: máscara manual
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
                }

                // Tab Switching
                const tabs = document.querySelectorAll('.login-tab');
                const contents = document.querySelectorAll('.tab-content');
                const loginTypeInput = document.getElementById('login_type');
                const emailInput = document.getElementById('email');
                const cpfInputField = document.getElementById('cpf');

                if (tabs.length && contents.length && loginTypeInput) {
                    // Função para atualizar o estado visual das abas
                    function updateTabVisuals(activeTab) {
                        tabs.forEach(tab => {
                            if (tab === activeTab) {
                                tab.classList.add('active');
                                // Forçar re-render para garantir que o CSS seja aplicado
                                tab.style.display = 'none';
                                tab.offsetHeight; // Trigger reflow
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

                            // Update tabs visual state - PRIMEIRO
                            updateTabVisuals(this);

                            // Pequeno delay para garantir que o DOM atualizou
                            setTimeout(() => {
                                // Update contents
                                contents.forEach(c => c.classList.add('hidden'));
                                const targetContent = document.getElementById(
                                    `content-${targetTab}`);
                                if (targetContent) {
                                    targetContent.classList.remove('hidden');
                                }

                                // Update login type
                                loginTypeInput.value = targetTab;

                                // Update required attributes
                                if (targetTab === 'email' && emailInput) {
                                    emailInput.setAttribute('required', 'required');
                                    if (cpfInputField) {
                                        cpfInputField.removeAttribute('required');
                                        cpfInputField.value = '';
                                    }
                                    emailInput.focus();
                                } else if (cpfInputField) {
                                    cpfInputField.setAttribute('required', 'required');
                                    if (emailInput) {
                                        emailInput.removeAttribute('required');
                                        emailInput.value = '';
                                    }
                                    cpfInputField.focus();
                                }
                            }, 10);
                        });
                    });

                    // Garantir que a aba inicial esteja com o estado visual correto
                    const initialActiveTab = document.querySelector('.login-tab.active');
                    if (initialActiveTab) {
                        updateTabVisuals(initialActiveTab);
                    } else if (tabs.length > 0) {
                        // Se nenhuma aba estiver ativa, ativar a primeira
                        tabs[0].classList.add('active');
                    }
                }

                // Toggle Password Visibility
                const passwordInput = document.getElementById('password');
                const togglePasswordBtn = document.getElementById('toggle-password');
                const eyeIcon = document.getElementById('eye-icon');
                const eyeSlashIcon = document.getElementById('eye-slash-icon');

                if (togglePasswordBtn && passwordInput && eyeIcon && eyeSlashIcon) {
                    togglePasswordBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const isPassword = passwordInput.type === 'password';
                        passwordInput.type = isPassword ? 'text' : 'password';

                        if (isPassword) {
                            eyeIcon.classList.add('hidden');
                            eyeSlashIcon.classList.remove('hidden');
                        } else {
                            eyeIcon.classList.remove('hidden');
                            eyeSlashIcon.classList.add('hidden');
                        }
                    });
                }

                // Form Validation
                const form = document.getElementById('login-form');
                if (form && loginTypeInput) {
                    form.addEventListener('submit', function(e) {
                        const loginType = loginTypeInput.value;

                        if (loginType === 'email') {
                            if (!emailInput || !emailInput.value) {
                                e.preventDefault();
                                if (emailInput) emailInput.focus();
                                return false;
                            }
                        } else {
                            if (!cpfInputField) {
                                e.preventDefault();
                                if (window.showAlert) {
                                    window.showAlert('CPF não encontrado. Por favor, recarregue a página.',
                                        'Erro', 'error');
                                } else {
                                    alert('CPF não encontrado. Por favor, recarregue a página.');
                                }
                                return false;
                            }
                            const cpfValue = cpfInputField.value.replace(/\D/g, '');
                            if (!cpfValue || cpfValue.length !== 11) {
                                e.preventDefault();
                                if (window.showAlert) {
                                    window.showAlert('Por favor, informe um CPF válido com 11 dígitos.',
                                        'CPF Inválido', 'warning');
                                } else {
                                    alert('Por favor, informe um CPF válido com 11 dígitos.');
                                }
                                cpfInputField.focus();
                                return false;
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
