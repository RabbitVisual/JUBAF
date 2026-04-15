// JavaScript do Painel Admin - JavaScript Puro
document.addEventListener('DOMContentLoaded', function() {
    // Alternar Sidebar Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                sidebar.classList.remove('-translate-x-full');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('hidden');
                    sidebarOverlay.style.display = 'block';
                }
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.add('hidden');
                    sidebarOverlay.style.display = 'none';
                }
                document.body.classList.remove('overflow-hidden');
            }
        });
    }
    
    // Fechar sidebar ao clicar no overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            if (sidebar) {
                sidebar.classList.add('-translate-x-full');
            }
            sidebarOverlay.classList.add('hidden');
            sidebarOverlay.style.display = 'none';
            document.body.classList.remove('overflow-hidden');
        });
    }
    
    // Fechar sidebar com tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar && !sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.add('-translate-x-full');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('hidden');
                sidebarOverlay.style.display = 'none';
            }
            document.body.classList.remove('overflow-hidden');
        }
    });
    
    // Dropdown functionality - Garantir que funcione no painel admin
    function initDropdowns() {
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        
        dropdowns.forEach(function(dropdown) {
            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            
            if (trigger && menu) {
                // Remover listeners anteriores se existirem
                const newTrigger = trigger.cloneNode(true);
                trigger.parentNode.replaceChild(newTrigger, trigger);
                
                // Adicionar listener
                newTrigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Fechar todos os outros dropdowns
                    const allDropdowns = document.querySelectorAll('[data-dropdown]');
                    allDropdowns.forEach(function(otherDropdown) {
                        if (otherDropdown !== dropdown) {
                            const otherMenu = otherDropdown.querySelector('[data-dropdown-menu]');
                            if (otherMenu) {
                                otherMenu.classList.add('hidden');
                            }
                        }
                    });
                    
                    // Alternar dropdown atual
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                    } else {
                        menu.classList.add('hidden');
                    }
                });
            }
        });
    }
    
    // Inicializar dropdowns imediatamente
    initDropdowns();
    
    // Re-inicializar após um pequeno delay para garantir que funcione
    setTimeout(function() {
        initDropdowns();
    }, 100);
    
    // Fechar dropdowns ao clicar fora
    document.addEventListener('click', function(e) {
        if (!e.target.closest('[data-dropdown]')) {
            const dropdowns = document.querySelectorAll('[data-dropdown]');
            dropdowns.forEach(function(dropdown) {
                const menu = dropdown.querySelector('[data-dropdown-menu]');
                if (menu) {
                    menu.classList.add('hidden');
                }
            });
        }
    });
    
    // Theme Toggle - Removido: O dark-mode.js centraliza o controle do tema
    // Não criar listeners duplicados aqui, apenas garantir que o tema seja aplicado
    // O dark-mode.js já cuida de tudo
    
    // Ocultar alertas automaticamente após 5 segundos
    const alerts = document.querySelectorAll('[data-auto-hide]');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 300);
        }, 5000);
    });

    // Scroll automático do sidebar para o item ativo
    function scrollToActiveSidebarItem() {
        const sidebarNav = document.querySelector('#adminSidebar nav');
        if (!sidebarNav) return;

        // Encontrar o link ativo (que tem bg-slate-700 na classe)
        let activeLink = null;
        const allLinks = sidebarNav.querySelectorAll('a[href]');
        
        allLinks.forEach(function(link) {
            // Verificar se o link tem a classe bg-slate-700 (ativo)
            if (link.classList.contains('bg-slate-700')) {
                activeLink = link;
            }
        });
        
        if (activeLink) {
            // Aguardar um pouco para garantir que o layout está renderizado
            setTimeout(function() {
                // Usar scrollIntoView com opções para centralizar o item
                activeLink.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'nearest'
                });
            }, 200);
        }
    }

    // Executar scroll ao carregar a página (com delay para garantir que o DOM está pronto)
    setTimeout(function() {
        scrollToActiveSidebarItem();
    }, 100);

    // Scroll ao clicar em um link do sidebar
    const sidebarLinks = document.querySelectorAll('#adminSidebar nav a[href]');
    sidebarLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const sidebarNav = document.querySelector('#adminSidebar nav');
            if (!sidebarNav) return;

            // Pequeno delay para permitir que a navegação comece
            setTimeout(function() {
                const navRect = sidebarNav.getBoundingClientRect();
                const linkRect = link.getBoundingClientRect();
                
                // Verificar se o link está completamente visível na viewport do nav
                const isFullyVisible = (
                    linkRect.top >= navRect.top &&
                    linkRect.bottom <= navRect.bottom
                );

                // Se não estiver completamente visível, rolar para ele
                if (!isFullyVisible) {
                    // Usar scrollIntoView para garantir que o item fique visível
                    link.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center',
                        inline: 'nearest'
                    });
                }
            }, 50);
        });
    });
});

