<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title')@else{{ \App\Support\SiteBranding::siteName() }}@endif — JUBAF</title>
    <meta name="description" content="{{ \App\Support\SiteBranding::siteTagline() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    @stack('head')

    <!-- Inicialização do tema ANTES do CSS para evitar FOUC -->
    <!-- Seguindo documentação oficial Tailwind CSS v4.1: https://tailwindcss.com/docs/dark-mode -->
    <script>
        (function() {
            'use strict';
            try {
                const savedTheme = localStorage.getItem('theme') || 'light';
                const html = document.documentElement;

                // Aplicar tema antes de qualquer CSS ser carregado
                if (savedTheme === 'dark') {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }

                // Função toggleTheme global (fallback até dark-mode.js carregar)
                // Esta função será sobrescrita pelo dark-mode.js quando carregar
                window.toggleTheme = function() {
                    const html = document.documentElement;
                    const isDark = html.classList.contains('dark');
                    const newTheme = isDark ? 'light' : 'dark';

                    // Salvar preferência
                    try {
                        localStorage.setItem('theme', newTheme);
                    } catch (e) {
                        // localStorage não disponível
                    }

                    // Aplicar/remover classe dark no elemento <html>
                    // Tailwind CSS v4.1 usa @custom-variant dark (&:where(.dark, .dark *))
                    if (newTheme === 'dark') {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }

                    // Forçar reflow para garantir que o navegador aplique as mudanças CSS imediatamente
                    void html.offsetHeight;

                    // Atualizar ícones (aguardar DOM estar disponível)
                    function updateIcons() {
                        const themeIconSun = document.getElementById('theme-icon-sun');
                        const themeIconMoon = document.getElementById('theme-icon-moon');

                        if (themeIconSun && themeIconMoon) {
                            if (newTheme === 'dark') {
                                themeIconSun.classList.add('hidden');
                                themeIconMoon.classList.remove('hidden');
                            } else {
                                themeIconSun.classList.remove('hidden');
                                themeIconMoon.classList.add('hidden');
                            }
                        }
                    }

                    // Tentar atualizar imediatamente
                    updateIcons();

                    // Se DOM não estiver pronto, aguardar
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', updateIcons);
                    }
                };
            } catch (e) {
                // Fallback silencioso se localStorage não estiver disponível
                console.warn('Theme initialization failed:', e);
            }
        })();
    </script>

    @php
        // Carregar assets do manifest.json em produção
        $manifestPath = public_path('build/manifest.json');
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
            $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
            $vendorCss = $manifest['_vendor-CfZ7kyuK.css']['file'] ?? null;
        }
    @endphp

    @if(isset($cssFile))
        <link rel="stylesheet" href="{{ asset('build/' . $cssFile) }}">
    @endif
    @if(isset($vendorCss))
        <link rel="stylesheet" href="{{ asset('build/' . $vendorCss) }}">
    @endif
    @if(isset($jsFile))
        <script type="module" src="{{ asset('build/' . $jsFile) }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('styles')
</head>
<body class="antialiased bg-white dark:bg-slate-900 text-gray-900 dark:text-white transition-colors duration-300">
    @yield('content')

    {{-- Avisos Flutuantes --}}
    @if(module_enabled('Avisos'))
        <x-avisos::avisos-por-posicao posicao="flutuante" />
    @endif

    {{-- Widget de Chat --}}
    @php
        $chatHomepageOnly = (bool) \App\Models\SystemConfig::get('integrations_chat_widget_homepage_only', false);
        $chatOnHomepage = request()->routeIs('homepage', 'bible.public.*');
    @endphp
    @if(module_enabled('Chat') && \Modules\Chat\App\Models\ChatConfig::isPublicEnabled() && (! $chatHomepageOnly || $chatOnHomepage))
        @include('chat::public.widget')
    @endif

    {{-- Scripts devem vir depois dos componentes para que @push funcione --}}
    @stack('scripts')

    {{-- Funções globais para avisos (garantir que estejam sempre disponíveis) --}}
    <script>
        (function() {
            // Garantir que as funções estejam disponíveis globalmente
            if (typeof window.avisosFunctionsDefined === 'undefined') {
                window.avisosFunctionsDefined = true;

                window.registrarClique = function(avisoId) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!csrfToken) {
                        console.warn('CSRF token não encontrado');
                        return;
                    }
                    fetch(`/api/avisos/${avisoId}/clique`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                        },
                    }).catch(() => {});
                };

                window.fecharAviso = function(avisoId) {
                    const avisoComponent = document.querySelector(`.aviso-${avisoId}`);
                    if (avisoComponent) {
                        avisoComponent.style.transition = 'opacity 0.3s ease-out';
                        avisoComponent.style.opacity = '0';
                        setTimeout(() => {
                            avisoComponent.remove();
                        }, 300);
                        localStorage.setItem(`aviso-${avisoId}-fechado`, 'true');
                    } else {
                        console.warn('Aviso não encontrado:', avisoId);
                    }
                };
            }
        })();
    </script>
    <!-- Global Loading Overlay -->
    <x-loading-overlay />
</body>
</html>
