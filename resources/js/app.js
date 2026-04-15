import './bootstrap';
import './dark-mode';
import './chart-admin';
import './module-validations';
import './masks';
import './admin';
import './notifications';
import './qrcode';
import './dialogs'; // Importar sistema de diálogos profissionais
import './toast'; // Importar sistema de notificações toast
import './form-helpers'; // Helpers para substituir confirms inline
import './map'; // Importar sistema de mapas

// Alpine.js
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import offlineManager from './offline/manager';
import { registerBibleInterlinear } from './bible-interlinear';
import { registerBiblePublicIndex } from './bible-public-index';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.plugin(focus);

Alpine.data('offlineManager', offlineManager);
registerBibleInterlinear(Alpine);
registerBiblePublicIndex(Alpine);

Alpine.start();

// Flowbite 4.0 - Importar localmente (sem CDN)
import 'flowbite';

// Preline UI
import 'preline/preline';
// Preline Carousel Plugin
import '@preline/carousel';

// Função de alternar sidebar (para mobile)
document.addEventListener('DOMContentLoaded', function () {
    // Inicializar Preline UI
    if (typeof window.HSStaticMethods !== 'undefined') {
        window.HSStaticMethods.autoInit();
    }

    // Alternar sidebar mobile
    const mobileSidebar = document.getElementById('mobile-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    // Tornar toggleSidebar disponível globalmente
    window.toggleSidebar = function () {
        if (mobileSidebar && sidebarOverlay) {
            const isHidden = mobileSidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                mobileSidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                mobileSidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
    };

    // Fechar sidebar ao clicar no overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            window.toggleSidebar();
        });
    }

    // Fechar sidebar com tecla Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && mobileSidebar && !mobileSidebar.classList.contains('-translate-x-full')) {
            window.toggleSidebar();
        }
    });

    // Dropdown functionality (para funcionar no layout do usuário também)
    // Só inicializar se não estiver no painel admin (admin.js cuida disso)
    const isAdminPanel = window.location.pathname.includes('/admin') ||
        document.querySelector('nav[class*="admin"]') !== null ||
        document.getElementById('adminSidebar') !== null;

    if (!isAdminPanel) {
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        dropdowns.forEach(function (dropdown) {
            // Pular o dropdown de notificações (notifications.js cuida disso)
            if (dropdown.querySelector('#notifications-trigger')) {
                return;
            }

            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');

            if (trigger && menu) {
                // Verificar se já foi inicializado pelo admin.js
                if (trigger.hasAttribute('data-admin-dropdown-initialized')) {
                    return;
                }

                trigger.addEventListener('click', function (e) {
                    e.stopPropagation();

                    // Fechar outros dropdowns (exceto notificações)
                    dropdowns.forEach(function (otherDropdown) {
                        if (otherDropdown !== dropdown &&
                            !otherDropdown.querySelector('#notifications-trigger')) {
                            const otherMenu = otherDropdown.querySelector('[data-dropdown-menu]');
                            if (otherMenu) {
                                otherMenu.classList.add('hidden');
                            }
                        }
                    });

                    // Alternar dropdown atual
                    menu.classList.toggle('hidden');
                });
            }
        });

        // Fechar dropdowns ao clicar fora (exceto notificações)
        document.addEventListener('click', function (e) {
            if (!e.target.closest('[data-dropdown]')) {
                const dropdowns = document.querySelectorAll('[data-dropdown]');
                dropdowns.forEach(function (dropdown) {
                    // Não fechar dropdown de notificações (notifications.js cuida disso)
                    if (dropdown.querySelector('#notifications-trigger')) {
                        return;
                    }
                    const menu = dropdown.querySelector('[data-dropdown-menu]');
                    if (menu) {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    }
});
