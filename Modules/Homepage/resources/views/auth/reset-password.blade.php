@extends('homepage::layouts.homepage')

@section('title', 'Redefinir Senha')

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

    <div class="max-w-6xl w-full grid lg:grid-cols-2 gap-8 items-center">
        <!-- Left Side - Branding -->
        <div class="jubaf-hero-surface hidden lg:flex flex-col items-center justify-center p-8 rounded-2xl shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M50 50 L70 30 L90 50 L70 70 Z\'/%3E%3Cpath d=\'M30 30 L50 10 L70 30 L50 50 Z\'/%3E%3Cpath d=\'M30 70 L50 50 L70 70 L50 90 Z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-size: 100px 100px;"></div>
            </div>

            <div class="relative z-10 text-center space-y-6">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="w-full max-w-xs mx-auto mb-6 drop-shadow-2xl object-contain">
                <h2 class="text-3xl font-bold mb-4">Redefinir Sua Senha</h2>
                <p class="text-lg text-white/90 leading-relaxed">
                    Crie uma nova senha segura para proteger sua conta e manter seus dados seguros.
                </p>

                <div class="mt-8 space-y-4 text-left">
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                        <x-icon name="shield-halved" class="w-6 h-6 text-yellow-300 flex-shrink-0 mt-0.5" />
                        <div>
                            <strong class="block mb-1">Segurança</strong>
                            <p class="text-sm text-white/80">Sua senha será criptografada e protegida</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                        <x-icon name="key" class="w-6 h-6 text-blue-300 flex-shrink-0 mt-0.5" />
                        <div>
                            <strong class="block mb-1">Acesso Seguro</strong>
                            <p class="text-sm text-white/80">Use uma senha forte com no mínimo 8 caracteres</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-4 rounded-xl">
                        <x-icon name="lock" class="w-6 h-6 text-green-300 flex-shrink-0 mt-0.5" />
                        <div>
                            <strong class="block mb-1">Proteção</strong>
                            <p class="text-sm text-white/80">Mantenha sua conta protegida com uma senha única</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Reset Password Form -->
        <div class="w-full max-w-md mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl p-8 border border-gray-100 dark:border-slate-700">
                <div class="text-center mb-8">
                    <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-16 mx-auto mb-4 dark:hidden object-contain">
                    <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="{{ \App\Support\SiteBranding::siteName() }}" class="h-16 mx-auto mb-4 hidden dark:block object-contain">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Redefinir Senha</h3>
                    <p class="text-gray-600 dark:text-gray-300">Digite sua nova senha abaixo</p>
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
                                <h4 class="font-semibold text-red-900 dark:text-red-200 mb-2">Erro ao redefinir senha</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-300">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" id="reset-password-form" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            E-mail
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-icon name="envelope" class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email', $email) }}"
                                required
                                readonly
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-400 cursor-not-allowed"
                            />
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Este é o e-mail associado à sua conta.
                        </p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nova Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nova Senha
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-icon name="lock" class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                autofocus
                                placeholder="Digite sua nova senha"
                                class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors"
                            />
                            <button
                                type="button"
                                id="toggle-password"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                aria-label="Mostrar senha"
                            >
                                <span id="eye-icon">
                                    <x-icon name="eye" class="w-5 h-5" />
                                </span>
                                <span id="eye-slash-icon" class="hidden">
                                    <x-icon name="eye-slash" class="w-5 h-5" />
                                </span>
                            </button>
                        </div>
                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                A senha deve conter:
                            </p>
                            <ul class="text-xs text-gray-500 dark:text-gray-400 space-y-1 ml-4">
                                <li class="flex items-center gap-2">
                                    <x-icon name="check" class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                                    No mínimo 8 caracteres
                                </li>
                                <li class="flex items-center gap-2">
                                    <x-icon name="check" class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                                    Letras maiúsculas e minúsculas
                                </li>
                                <li class="flex items-center gap-2">
                                    <x-icon name="check" class="w-3 h-3 text-blue-600 dark:text-blue-400" />
                                    Números e caracteres especiais
                                </li>
                            </ul>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirmar Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Confirmar Nova Senha
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-icon name="lock" class="w-5 h-5 text-gray-400" />
                            </div>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                placeholder="Digite a senha novamente para confirmar"
                                class="block w-full pl-10 pr-12 py-3 border border-gray-300 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-colors"
                            />
                            <button
                                type="button"
                                id="toggle-password-confirmation"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                aria-label="Mostrar senha"
                            >
                                <span id="eye-icon-confirmation">
                                    <x-icon name="eye" class="w-5 h-5" />
                                </span>
                                <span id="eye-slash-icon-confirmation" class="hidden">
                                    <x-icon name="eye-slash" class="w-5 h-5" />
                                </span>
                            </button>
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Digite a mesma senha novamente para confirmar.
                        </p>
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
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
                        <x-icon name="key" class="w-5 h-5" />
                        Redefinir Senha
                    </button>

                    <!-- reCAPTCHA Component -->
                    <x-recaptcha action="reset_password" />
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Password Visibility
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const togglePasswordConfirmationBtn = document.getElementById('toggle-password-confirmation');
    const eyeIcon = document.getElementById('eye-icon');
    const eyeSlashIcon = document.getElementById('eye-slash-icon');
    const eyeIconConfirmation = document.getElementById('eye-icon-confirmation');
    const eyeSlashIconConfirmation = document.getElementById('eye-slash-icon-confirmation');

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

    if (togglePasswordConfirmationBtn && passwordConfirmationInput && eyeIconConfirmation && eyeSlashIconConfirmation) {
        togglePasswordConfirmationBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const isPassword = passwordConfirmationInput.type === 'password';
            passwordConfirmationInput.type = isPassword ? 'text' : 'password';

            if (isPassword) {
                eyeIconConfirmation.classList.add('hidden');
                eyeSlashIconConfirmation.classList.remove('hidden');
            } else {
                eyeIconConfirmation.classList.remove('hidden');
                eyeSlashIconConfirmation.classList.add('hidden');
            }
        });
    }

    // Validação de senha em tempo real
    if (passwordInput && passwordConfirmationInput) {
        passwordConfirmationInput.addEventListener('input', function() {
            if (passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationInput.setCustomValidity('As senhas não coincidem');
            } else {
                passwordConfirmationInput.setCustomValidity('');
            }
        });

        passwordInput.addEventListener('input', function() {
            if (passwordConfirmationInput.value && passwordInput.value !== passwordConfirmationInput.value) {
                passwordConfirmationInput.setCustomValidity('As senhas não coincidem');
            } else {
                passwordConfirmationInput.setCustomValidity('');
            }
        });
    }

    // Validação do formulário
    const form = document.getElementById('reset-password-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (passwordInput.value !== passwordConfirmationInput.value) {
                e.preventDefault();
                alert('As senhas não coincidem. Por favor, verifique e tente novamente.');
                passwordConfirmationInput.focus();
                return false;
            }

            if (passwordInput.value.length < 8) {
                e.preventDefault();
                alert('A senha deve ter no mínimo 8 caracteres.');
                passwordInput.focus();
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
