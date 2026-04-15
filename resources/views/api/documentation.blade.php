<!DOCTYPE html>
<html lang="pt-BR" class="h-full scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Documentação da API — {{ \App\Support\SiteBranding::siteName() }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Scroll suave */
        html {
            scroll-behavior: smooth;
        }

        /* Estilos para códigos */
        .code-block {
            position: relative;
        }

        .code-block pre {
            margin: 0;
            overflow-x: auto;
        }

        /* Animações */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Scrollbar customizada */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgb(241 245 249);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(203 213 225);
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgb(148 163 184);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgb(30 41 59);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgb(51 65 85);
        }

        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgb(71 85 105);
        }
    </style>
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-4">
                    <a href="@auth
                        @php $dash = get_dashboard_route(); @endphp
                        {{ \Illuminate\Support\Facades\Route::has($dash) ? route($dash) : route('login') }}
                    @else{{ route('login') }}
                    @endauth" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        <div>
                            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">API</div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white">{{ \App\Support\SiteBranding::siteName() }}</div>
                        </div>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="toggleTheme()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Alternar tema">
                        <svg id="theme-icon-sun" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <svg id="theme-icon-moon" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>
                    @auth
                    <a href="{{ route('admin.api.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Gerenciar API
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-indigo-800 dark:from-indigo-900 dark:via-purple-900 dark:to-indigo-950 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="text-center animate-fade-in-up">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                    </svg>
                </div>
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">Documentação da API</h1>
                <p class="text-xl lg:text-2xl text-indigo-100 dark:text-indigo-200 mb-2">{{ \App\Support\SiteBranding::siteName() }} — API da plataforma</p>
                <p class="text-indigo-200 dark:text-indigo-300 text-sm lg:text-base mt-4">
                    Versão 1.0.0 | Base URL: <code class="bg-white/20 backdrop-blur-sm px-2 py-1 rounded text-sm font-mono">{{ url('/api/v1') }}</code>
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="lg:sticky lg:top-20 lg:h-[calc(100vh-5rem)] lg:overflow-y-auto custom-scrollbar">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 18.75h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        Navegação
                    </h2>
                    <nav class="space-y-2">
                        <a href="#intro" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            Introdução
                        </a>
                        <a href="#auth" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                            Autenticação
                        </a>
                        <a href="#demandas" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            Demandas
                        </a>
                        <a href="#ordens" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0020 18a2.65 2.65 0 00-.75-1.837l-4.582-4.583M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655-5.653a2.548 2.548 0 00-4.64 4.646l5.655 6.856a2.548 2.548 0 003.586.03l3.64-3.643a2.548 2.548 0 00-.03-3.586l-3.64-3.643a2.548 2.548 0 00-3.586.03l-3.64 3.643z" />
                            </svg>
                            Ordens
                        </a>
                        <a href="#localidades" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            Localidades
                        </a>
                        <a href="#materiais" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                            Materiais
                        </a>
                        <a href="#equipes" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            Equipes
                        </a>
                        <a href="#status-codes" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            Status HTTP
                        </a>
                        <a href="#token-management" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                            Tokens
                        </a>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="lg:col-span-3 space-y-8">
                <!-- Introdução -->
                <section id="intro" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Introdução</h2>
                    </div>

                    <div class="prose prose-indigo dark:prose-invert max-w-none">
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Bem-vindo à documentação da API REST da {{ \App\Support\SiteBranding::siteName() }}. Esta API expõe recursos públicos e integrações autorizadas da plataforma.
                        </p>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 p-4 rounded-r-lg mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <div>
                                    <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Informações Importantes</h3>
                                    <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-disc list-inside">
                                        <li>Todas as requisições devem usar o header <code class="bg-blue-100 dark:bg-blue-900/50 px-1.5 py-0.5 rounded text-xs font-mono">Accept: application/json</code></li>
                                        <li>Requisições autenticadas devem incluir <code class="bg-blue-100 dark:bg-blue-900/50 px-1.5 py-0.5 rounded text-xs font-mono">Authorization: Bearer {token}</code></li>
                                        <li>As respostas são sempre em formato JSON</li>
                                        <li>O sistema usa paginação padrão de 20 itens por página</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Autenticação -->
                <section id="auth" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Autenticação</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        A API utiliza autenticação via Bearer Token (Laravel Sanctum). Primeiro, você precisa fazer login para obter um token de acesso.
                    </p>

                    <!-- Login Endpoint -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">POST</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/auth/login</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">PÚBLICO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Autentica um usuário e retorna um token de acesso.</p>

                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                    Parâmetros
                                </h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Obrigatório</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">email</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">string</td>
                                                <td class="px-4 py-3 whitespace-nowrap"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Sim</span></td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">Email do usuário</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">password</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">string</td>
                                                <td class="px-4 py-3 whitespace-nowrap"><span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">Sim</span></td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">Senha do usuário</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Resposta de Sucesso (200)
                                </h4>
                                <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                                    <pre class="text-sm text-gray-100 font-mono"><code>{
  "success": true,
  "message": "Login realizado com sucesso",
  "timestamp": "2025-12-03T14:54:26.000000Z",
  "data": {
    "user": {
      "id": 1,
      "name": "João Silva",
      "email": "joao@example.com"
    },
    "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
    "token_type": "Bearer"
  }
}</code></pre>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                                    </svg>
                                    Exemplo cURL
                                </h4>
                                <div class="bg-gray-900 rounded-lg p-4 overflow-x-auto">
                                    <pre class="text-sm text-gray-100 font-mono"><code>curl -X POST "{{ url('/api/v1/auth/login') }}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"usuario@example.com","password":"senha123"}'</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Me Endpoint -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/auth/me</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Retorna informações do usuário autenticado.</p>
                            <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-400 p-4 rounded-r-lg">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-amber-900 dark:text-amber-100 mb-1">Requer Autenticação</p>
                                        <p class="text-sm text-amber-800 dark:text-amber-200">Inclua o header <code class="bg-amber-100 dark:bg-amber-900/50 px-1.5 py-0.5 rounded text-xs font-mono">Authorization: Bearer {token}</code></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Endpoint -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">POST</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/auth/logout</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300">Revoga o token de acesso atual.</p>
                        </div>
                    </div>
                </section>

                <!-- Demandas -->
                <section id="demandas" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Demandas</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">Endpoints para gerenciar demandas da população.</p>

                    <!-- GET /demandas -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/demandas</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Lista todas as demandas com filtros opcionais.</p>

                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Parâmetros de Query (opcionais):</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Parâmetro</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Descrição</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">status</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">string</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">aberta, em_andamento, concluida, cancelada</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">tipo</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">string</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">agua, luz, estrada, poco</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">prioridade</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">string</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">baixa, media, alta, urgente</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">localidade_id</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">integer</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">ID da localidade</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">search</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">string</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">Buscar por código, nome ou motivo</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">per_page</code></td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">integer</td>
                                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">Itens por página (padrão: 20)</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- POST /demandas -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">POST</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/demandas</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Cria uma nova demanda.</p>
                        </div>
                    </div>

                    <!-- GET /demandas/stats -->
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/demandas/stats</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300">Retorna estatísticas de demandas.</p>
                        </div>
                    </div>
                </section>

                <!-- Ordens -->
                <section id="ordens" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0020 18a2.65 2.65 0 00-.75-1.837l-4.582-4.583M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655-5.653a2.548 2.548 0 00-4.64 4.646l5.655 6.856a2.548 2.548 0 003.586.03l3.64-3.643a2.548 2.548 0 00-.03-3.586l-3.64-3.643a2.548 2.548 0 00-3.586.03l-3.64 3.643z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Ordens de Serviço</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">Endpoints para gerenciar ordens de serviço.</p>

                    <div class="space-y-4">
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                    <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/ordens</code>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                                </div>
                            </div>
                            <div class="p-4 lg:p-6">
                                <p class="text-gray-600 dark:text-gray-300">Lista todas as ordens de serviço.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Localidades -->
                <section id="localidades" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Localidades</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">Endpoints públicos para consultar localidades.</p>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/localidades</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">PÚBLICO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300">Lista todas as localidades (endpoint público, não requer autenticação).</p>
                        </div>
                    </div>
                </section>

                <!-- Materiais -->
                <section id="materiais" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Materiais</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">Endpoints para consultar materiais e estoque.</p>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/materiais</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300">Lista todos os materiais disponíveis.</p>
                        </div>
                    </div>
                </section>

                <!-- Equipes -->
                <section id="equipes" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Equipes</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">Endpoints para consultar equipes de trabalho.</p>

                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">GET</span>
                                <code class="text-sm font-mono text-gray-900 dark:text-gray-100">/api/v1/equipes</code>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">PRIVADO</span>
                            </div>
                        </div>
                        <div class="p-4 lg:p-6">
                            <p class="text-gray-600 dark:text-gray-300">Lista todas as equipes.</p>
                        </div>
                    </div>
                </section>

                <!-- Status Codes -->
                <section id="status-codes" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Códigos de Status HTTP</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Código</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded font-mono">200</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Sucesso</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-2 py-1 rounded font-mono">201</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Criado com sucesso</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-2 py-1 rounded font-mono">400</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Requisição inválida</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 px-2 py-1 rounded font-mono">401</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Não autenticado</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-2 py-1 rounded font-mono">403</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Acesso negado</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 px-2 py-1 rounded font-mono">404</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Recurso não encontrado</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 px-2 py-1 rounded font-mono">422</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Erro de validação</td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"><code class="text-sm bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-2 py-1 rounded font-mono">500</code></td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">Erro interno do servidor</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Token Management -->
                <section id="token-management" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 lg:p-8 animate-fade-in-up">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Gerenciamento de Tokens</h2>
                    </div>

                    <p class="text-gray-600 dark:text-gray-300 mb-6">Os tokens de API são gerenciados através do painel administrativo.</p>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 dark:border-blue-400 p-4 rounded-r-lg mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Como obter um token:</h3>
                                <ol class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-decimal list-inside">
                                    <li>Acesse o painel administrativo</li>
                                    <li>Vá em "Gerenciamento de API"</li>
                                    <li>Crie um novo token</li>
                                    <li>Copie o token gerado (ele só será exibido uma vez)</li>
                                    <li>Use o token no header <code class="bg-blue-100 dark:bg-blue-900/50 px-1.5 py-0.5 rounded text-xs font-mono">Authorization: Bearer {token}</code></li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-500 dark:border-amber-400 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <div>
                                <h3 class="font-semibold text-amber-900 dark:text-amber-100 mb-2">Segurança:</h3>
                                <ul class="text-sm text-amber-800 dark:text-amber-200 space-y-1 list-disc list-inside">
                                    <li>Nunca compartilhe seu token</li>
                                    <li>Use HTTPS em produção</li>
                                    <li>Configure IP whitelist quando possível</li>
                                    <li>Revogue tokens não utilizados</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">© {{ date('Y') }} {{ \App\Support\SiteBranding::siteName() }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">Documentação gerada automaticamente</p>
            </div>
        </div>
    </footer>

    <script>
        // Toggle theme function
        function toggleTheme() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            const newTheme = isDark ? 'light' : 'dark';

            if (newTheme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }

            try {
                localStorage.setItem('theme', newTheme);
            } catch (e) {}

            // Update icons
            const sunIcon = document.getElementById('theme-icon-sun');
            const moonIcon = document.getElementById('theme-icon-moon');
            if (sunIcon && moonIcon) {
                if (newTheme === 'dark') {
                    sunIcon.classList.remove('hidden');
                    moonIcon.classList.add('hidden');
                } else {
                    sunIcon.classList.add('hidden');
                    moonIcon.classList.remove('hidden');
                }
            }
        }

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
