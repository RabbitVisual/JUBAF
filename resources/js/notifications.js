// Sistema de Notificações em Tempo Real com WebSockets e Fallback para Polling
(function () {
    'use strict';

    let pollingInterval = null;
    let lastCheckTime = null;
    let websocketConnection = null;
    let websocketReconnectAttempts = 0;
    const POLLING_INTERVAL = 30000; // 30 segundos
    const MAX_RECONNECT_ATTEMPTS = 5;
    const RECONNECT_DELAY = 3000; // 3 segundos

    // Configuração de Broadcasting
    const BROADCAST_DRIVER = window.BROADCAST_DRIVER || 'log';
    const USE_WEBSOCKETS = BROADCAST_DRIVER === 'pusher' || BROADCAST_DRIVER === 'redis' || BROADCAST_DRIVER === 'ably';
    const PUSHER_KEY = window.PUSHER_APP_KEY || null;
    const PUSHER_CLUSTER = window.PUSHER_APP_CLUSTER || 'mt1';

    // Detectar painel atual (filtro opcional na API)
    function getCurrentPanel() {
        const p = window.location.pathname;
        if (p.startsWith('/admin')) {
            return 'admin';
        }
        if (p.startsWith('/jovens')) {
            return 'jovens';
        }
        if (p.startsWith('/lideres')) {
            return 'lideres';
        }
        if (p.startsWith('/diretoria')) {
            return 'diretoria';
        }
        return null;
    }

    // Detectar URL base automaticamente
    function getBaseUrl() {
        return '';
    }

    // Elementos DOM
    const trigger = document.getElementById('notifications-trigger');
    const dropdown = document.getElementById('notifications-dropdown');
    const badge = document.getElementById('notifications-badge');
    const countSpan = document.getElementById('notifications-count');
    const listContainer = document.getElementById('notifications-items');
    const emptyState = document.getElementById('notifications-empty');
    const loadingState = document.getElementById('notifications-loading');
    const markAllReadBtn = document.getElementById('mark-all-read-btn');

    if (!trigger || !dropdown) {
        return; // Componente não está na página
    }

    // Configurar CSRF token para requisições
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Headers padrão
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    if (csrfToken) {
        headers['X-CSRF-TOKEN'] = csrfToken;
    }

    // Função para fazer requisições
    async function fetchAPI(endpoint, options = {}) {
        const cleanEndpoint = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;

        // Adicionar painel se não estiver presente
        const panel = getCurrentPanel();
        let finalEndpoint = cleanEndpoint;
        if (panel && !cleanEndpoint.includes('panel=')) {
            const separator = cleanEndpoint.includes('?') ? '&' : '?';
            finalEndpoint = `${cleanEndpoint}${separator}panel=${panel}`;
        }

        const url = `/api/notificacoes${finalEndpoint}`;

        try {
            const fetchOptions = {
                method: options.method || 'GET',
                headers: {
                    ...headers,
                    ...(options.headers || {}),
                },
                credentials: 'same-origin',
            };

            if (options.body) {
                fetchOptions.body = typeof options.body === 'string' ? options.body : JSON.stringify(options.body);
                fetchOptions.headers['Content-Type'] = 'application/json';
            } else if (options.method && options.method !== 'GET') {
                fetchOptions.headers['Content-Type'] = 'application/json';
            } else {
                delete fetchOptions.headers['Content-Type'];
            }

            const response = await fetch(url, fetchOptions);

            // Se for 401 (Unauthorized), retornar nulo silenciosamente (usuário não logado)
            if (response.status === 401) {
                return null;
            }

            if (!response.ok) {
                const errorText = await response.text();
                // Não logar erro 401 como erro crítico no console
                if (response.status !== 401) {
                    console.error('Erro na resposta:', response.status, errorText, 'URL:', url);
                }
                throw new Error(`Erro HTTP! status: ${response.status}`);
            }

            return await response.json();
        } catch (error) {
            // Silenciar erros de rede ou 401 para evitar poluição no console de guests
            if (error.message.includes('401')) return null;
            console.error('Erro na requisição:', error.message, 'URL:', url);
            return null;
        }
    }

    // Atualizar contador de notificações
    async function updateCount() {
        const data = await fetchAPI('/count');
        if (data && data.success) {
            const count = data.count || 0;
            updateBadge(count);
        }
    }

    // Atualizar badge
    function updateBadge(count) {
        if (!badge || !countSpan) return;

        if (count > 0) {
            badge.classList.remove('hidden');
            badge.classList.remove('h-2', 'w-2');
            badge.classList.add('h-5', 'w-5');
            if (countSpan) {
                countSpan.textContent = count > 99 ? '99+' : count;
            }
        } else {
            badge.classList.add('hidden');
        }
    }

    // Carregar notificações
    async function loadNotifications() {
        if (loadingState) {
            loadingState.classList.remove('hidden');
        }
        if (emptyState) {
            emptyState.classList.add('hidden');
        }
        if (listContainer) {
            listContainer.innerHTML = '';
        }

        const data = await fetchAPI('/unread?limit=10');

        if (loadingState) {
            loadingState.classList.add('hidden');
        }

        if (!data || !data.success || !data.data || data.data.length === 0) {
            if (emptyState) {
                emptyState.classList.remove('hidden');
            }
            if (markAllReadBtn) {
                markAllReadBtn.classList.add('hidden');
            }
            return;
        }

        if (emptyState) {
            emptyState.classList.add('hidden');
        }
        if (markAllReadBtn) {
            markAllReadBtn.classList.remove('hidden');
        }

        renderNotifications(data.data);
    }

    // Renderizar notificações
    function renderNotifications(notifications) {
        if (!listContainer) return;

        listContainer.innerHTML = notifications.map(notification => {
            const typeColors = {
                'info': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'success': 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
                'warning': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                'error': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'alert': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
                'system': 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
            };

            const colorClass = typeColors[notification.type] || typeColors.info;
            const iconName = notification.type_icon || 'information-circle';

            return `
                <div
                    class="p-4 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer notification-item"
                    data-id="${notification.id}"
                    ${notification.action_url ? `onclick="window.location.href='${notification.action_url}'"` : ''}
                >
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-10 h-10 rounded-full ${colorClass} flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                ${escapeHtml(notification.title)}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                                ${escapeHtml(notification.message)}
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                ${notification.created_at_human}
                            </p>
                        </div>
                        <button
                            class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 mark-read-btn"
                            data-id="${notification.id}"
                            onclick="event.stopPropagation(); markAsRead(${notification.id})"
                            title="Marcar como lida"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Adicionar notificação à lista (para WebSockets)
    function addNotificationToList(notification) {
        if (!listContainer) return;

        const typeColors = {
            'info': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
            'success': 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200',
            'warning': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'error': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'alert': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200',
            'system': 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200',
        };

        const colorClass = typeColors[notification.type] || typeColors.info;

        const notificationHtml = `
            <div
                class="p-4 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors cursor-pointer notification-item animate-fade-in"
                data-id="${notification.id}"
                ${notification.action_url ? `onclick="window.location.href='${notification.action_url}'"` : ''}
            >
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full ${colorClass} flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                            ${escapeHtml(notification.title)}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">
                            ${escapeHtml(notification.message)}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                            ${notification.created_at_human || 'Agora'}
                        </p>
                    </div>
                    <button
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 mark-read-btn"
                        data-id="${notification.id}"
                        onclick="event.stopPropagation(); markAsRead(${notification.id})"
                        title="Marcar como lida"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `;

        if (emptyState) {
            emptyState.classList.add('hidden');
        }
        if (markAllReadBtn) {
            markAllReadBtn.classList.remove('hidden');
        }

        listContainer.insertAdjacentHTML('afterbegin', notificationHtml);

        // Atualizar contador
        updateCount();
    }

    // Marcar como lida
    async function markAsRead(id) {
        const data = await fetchAPI(`/${id}/read`, {
            method: 'POST',
        });

        if (data && data.success) {
            // Remover item da lista
            const item = document.querySelector(`.notification-item[data-id="${id}"]`);
            if (item) {
                item.style.transition = 'opacity 0.3s';
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                    // Verificar se não há mais notificações
                    if (listContainer && listContainer.children.length === 0) {
                        if (emptyState) emptyState.classList.remove('hidden');
                        if (markAllReadBtn) markAllReadBtn.classList.add('hidden');
                    }
                }, 300);
            }

            // Atualizar contador
            await updateCount();
        }
    }

    // Marcar todas como lidas
    async function markAllAsRead() {
        const data = await fetchAPI('/read-all', {
            method: 'POST',
        });

        if (data && data.success) {
            await loadNotifications();
            await updateCount();
        }
    }

    // Escape HTML
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Inicializar WebSockets (Pusher)
    function initializeWebSockets() {
        if (!USE_WEBSOCKETS || !PUSHER_KEY) {
            return false;
        }

        // Verificar se Pusher está disponível
        if (typeof Pusher === 'undefined') {
            return false;
        }

        try {
            const userId = window.USER_ID || null;
            if (!userId) {
                return false;
            }

            const pusher = new Pusher(PUSHER_KEY, {
                cluster: PUSHER_CLUSTER,
                encrypted: true,
                authEndpoint: '/broadcasting/auth',
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    }
                }
            });

            // Canal privado do usuário
            const userChannel = pusher.subscribe(`private-user.${userId}`);

            userChannel.bind('notificacao.criada', function (data) {
                // Nova notificação recebida via WebSocket
                addNotificationToList(data);
                updateCount();

                // Mostrar notificação toast se disponível
                if (window.showToast) {
                    window.showToast(data.title, data.message, data.type || 'info');
                }
            });

            userChannel.bind('notificacao.lida', function (data) {
                // Notificação lida via WebSocket
                const item = document.querySelector(`.notification-item[data-id="${data.id}"]`);
                if (item) {
                    item.style.opacity = '0.5';
                }
                updateCount();
            });

            websocketConnection = pusher;
            return true;
        } catch (error) {
            console.error('Erro ao conectar WebSockets:', error);
            return false;
        }
    }

    // Iniciar polling
    function startPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
        }

        // Carregar imediatamente
        updateCount();
        if (dropdown && !dropdown.classList.contains('hidden')) {
            loadNotifications();
        }

        // Configurar intervalo
        pollingInterval = setInterval(() => {
            updateCount();
            if (dropdown && !dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        }, POLLING_INTERVAL);
    }

    // Parar polling
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    // Event listeners
    if (dropdown && trigger) {
        function closeOtherDropdowns() {
            const allDropdowns = document.querySelectorAll('[data-dropdown]');
            allDropdowns.forEach(function (otherDropdown) {
                const notificationsContainer = dropdown.closest('[data-dropdown]');
                if (otherDropdown === notificationsContainer) {
                    return;
                }
                const otherMenu = otherDropdown.querySelector('[data-dropdown-menu]');
                if (otherMenu && otherMenu.id !== 'notifications-dropdown') {
                    otherMenu.classList.add('hidden');
                }
            });
        }

        const clickHandler = function (e) {
            e.preventDefault();
            e.stopPropagation();

            closeOtherDropdowns();

            const isHidden = dropdown.classList.contains('hidden');
            if (isHidden) {
                dropdown.classList.remove('hidden');
                setTimeout(function () {
                    loadNotifications();
                }, 100);
            } else {
                dropdown.classList.add('hidden');
            }
        };

        function attachNotificationListener() {
            const currentTrigger = document.getElementById('notifications-trigger');
            if (currentTrigger) {
                if (!currentTrigger.hasAttribute('data-notifications-listener-active')) {
                    currentTrigger.setAttribute('data-notifications-listener-active', 'true');
                    currentTrigger.addEventListener('click', clickHandler, true);
                }
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', attachNotificationListener);
        } else {
            attachNotificationListener();
        }

        setTimeout(attachNotificationListener, 100);
        setTimeout(attachNotificationListener, 300);
        setTimeout(attachNotificationListener, 500);

        const observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const isHidden = dropdown.classList.contains('hidden');
                    if (!isHidden) {
                        loadNotifications();
                    }
                }
            });
        });

        observer.observe(dropdown, {
            attributes: true,
            attributeFilter: ['class']
        });
    }

    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            markAllAsRead();
        });
    }

    // Expor funções globalmente
    window.markAsRead = markAsRead;
    window.markAllAsRead = markAllAsRead;

    // Inicializar sistema
    function initialize() {
        // Verificar se usuário está autenticado antes de iniciar polling
        // Se USER_ID não estiver definido ou for nulo, provavelmente é um guest
        const userId = window.USER_ID || null;

        // Se não houver trigger (botão de sino), não faz sentido pollar
        if (!trigger) return;

        // Tentar conectar WebSockets primeiro (se estiver logado)
        const websocketConnected = userId ? initializeWebSockets() : false;

        // Se WebSockets não estiver disponível e estiver logado, usar polling
        if (!websocketConnected && userId) {
            startPolling();
        } else if (userId) {
            // Mesmo com WebSockets, fazer uma verificação inicial se logado
            updateCount();
            if (dropdown && !dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        }
    }

    // Iniciar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }

    // Pausar polling quando a aba não está visível (apenas se não usar WebSockets)
    document.addEventListener('visibilitychange', function () {
        if (!websocketConnection) {
            if (document.hidden) {
                stopPolling();
            } else {
                startPolling();
            }
        }
    });
})();
