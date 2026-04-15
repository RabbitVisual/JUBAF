// Dark Mode Toggle - Tailwind CSS v4.1
// Implementação seguindo a documentação oficial: https://tailwindcss.com/docs/dark-mode
// Compatível com Laravel 12x e Vite

(function() {
    'use strict';

    // Função para aplicar tema (seguindo documentação oficial Tailwind CSS v4.1)
    function applyTheme(theme) {
        const html = document.documentElement;

        // Salvar preferência no localStorage
        try {
            localStorage.setItem('theme', theme);
        } catch (e) {
            // localStorage não disponível, continuar sem salvar
        }

        // Aplicar/remover classe 'dark' no elemento <html>
        // Tailwind CSS v4.1 usa @custom-variant dark (&:where(.dark, .dark *))
        if (theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Forçar reflow para garantir que o navegador aplique as mudanças CSS
        void html.offsetHeight;

        // Atualizar ícones imediatamente
        updateIcons(theme);

        // Disparar evento customizado para outros scripts
        window.dispatchEvent(new CustomEvent('themeChanged', {
            detail: { theme }
        }));
    }

    // Função para alternar tema (global, compatível com onclick inline)
    function toggleTheme() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        const newTheme = isDark ? 'light' : 'dark';

        // Aplicar tema imediatamente
        applyTheme(newTheme);
    }

    // Atualizar ícones do tema
    function updateIcons(theme) {
        // Função auxiliar para atualizar ícones
        function doUpdateIcons() {
            // Ícones do navbar principal (theme-icon-sun e theme-icon-moon)
            const themeIconSun = document.getElementById('theme-icon-sun');
            const themeIconMoon = document.getElementById('theme-icon-moon');

            if (themeIconSun && themeIconMoon) {
                if (theme === 'dark') {
                    themeIconSun.classList.add('hidden');
                    themeIconMoon.classList.remove('hidden');
                } else {
                    themeIconSun.classList.remove('hidden');
                    themeIconMoon.classList.add('hidden');
                }
            }

            // Ícones do admin panel (sunIcon e moonIcon) - compatibilidade com versão antiga
            const sunIcon = document.getElementById('sunIcon');
            const moonIcon = document.getElementById('moonIcon');

            if (sunIcon && moonIcon) {
                if (theme === 'dark') {
                    sunIcon.classList.remove('opacity-100', 'scale-100');
                    sunIcon.classList.add('opacity-0', 'scale-0');
                    moonIcon.classList.remove('opacity-0', 'scale-0');
                    moonIcon.classList.add('opacity-100', 'scale-100');
                } else {
                    sunIcon.classList.remove('opacity-0', 'scale-0');
                    sunIcon.classList.add('opacity-100', 'scale-100');
                    moonIcon.classList.remove('opacity-100', 'scale-100');
                    moonIcon.classList.add('opacity-0', 'scale-0');
                }
            }
        }

        // Tentar atualizar imediatamente
        doUpdateIcons();

        // Se DOM não estiver pronto, aguardar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', doUpdateIcons);
        }
    }

    // Inicializar tema salvo (sincronizar ícones com estado atual)
    function initTheme() {
        // Atualizar ícones baseado no estado atual do HTML
        const html = document.documentElement;
        const hasDarkClass = html.classList.contains('dark');
        const currentTheme = hasDarkClass ? 'dark' : 'light';

        // Atualizar ícones
        updateIcons(currentTheme);
    }

    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initTheme();
        });
    } else {
        // DOM já está pronto
        initTheme();
    }

    // Expor funções globalmente (IMPORTANTE: sobrescrever função do script inline)
    // Usar atribuição direta para garantir que funcione com onclick inline
    window.toggleTheme = toggleTheme;
    window.applyTheme = applyTheme;

    // Garantir que a função está disponível imediatamente (antes do DOM estar pronto)
    // Isso é crítico para onclick inline funcionar
    if (typeof window.toggleTheme === 'undefined' || typeof window.toggleTheme !== 'function') {
        window.toggleTheme = toggleTheme;
    }
})();
