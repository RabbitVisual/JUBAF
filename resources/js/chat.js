// Sistema de Chat em Tempo Real com WebSockets e Fallback para Polling
(function () {
    'use strict';

    let pollingInterval = null;
    let websocketConnection = null;
    let websocketReconnectAttempts = 0;
    let typingTimeout = null;
    let isTyping = false;
    const POLLING_INTERVAL = 3000; // 3 segundos para chat
    const MAX_RECONNECT_ATTEMPTS = 5;
    const RECONNECT_DELAY = 3000;
    const TYPING_TIMEOUT = 3000; // 3 segundos sem digitar = parar de digitar

    // Configuração de Broadcasting
    // Usar apenas Redis ou polling - sem dependências externas
    const BROADCAST_DRIVER = window.BROADCAST_DRIVER || 'log';
    const USE_WEBSOCKETS = BROADCAST_DRIVER === 'redis'; // Apenas Redis, sem Pusher

    // Estado do chat
    let currentSessionId = null;
    let currentSessionDbId = null;
    let lastMessageId = 0;
    let currentUserId = window.currentUserId || null;

    // Elementos DOM (serão inicializados quando necessário)
    let messagesContainer = null;
    let messageInput = null;
    let messageForm = null;
    let typingIndicator = null;

    /**
     * Inicializar sistema de chat
     */
    function initChat(sessionId, sessionDbId = null, userId = null) {
        currentSessionId = sessionId;
        currentSessionDbId = sessionDbId;
        currentUserId = userId || currentUserId;

        // Encontrar elementos DOM
        messagesContainer = document.getElementById('chat-messages-container');
        messageInput = document.getElementById('chat-message-input') || document.getElementById('message-input');
        messageForm = document.getElementById('chat-message-form') || document.getElementById('message-form');
        typingIndicator = document.getElementById('typing-indicator');

        if (!messagesContainer) {
            console.warn('Container de mensagens não encontrado');
            return;
        }

        // Configurar event listeners
        setupEventListeners();

        // Inicializar conexão
        // Usar apenas polling por padrão (sem dependências externas)
        // Se Redis estiver configurado com Laravel Echo, pode usar WebSockets
        if (USE_WEBSOCKETS && typeof Echo !== 'undefined') {
            initWebSocket();
        } else {
            startPolling();
        }

        // Carregar mensagens iniciais
        loadMessages();
    }

    /**
     * Configurar event listeners
     */
    function setupEventListeners() {
        // Enviar mensagem
        if (messageForm) {
            messageForm.addEventListener('submit', handleSendMessage);
        }

        // Indicador de digitação
        if (messageInput) {
            let typingTimer = null;

            messageInput.addEventListener('input', () => {
                if (!isTyping) {
                    isTyping = true;
                    sendTypingIndicator(true);
                }

                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    isTyping = false;
                    sendTypingIndicator(false);
                }, TYPING_TIMEOUT);
            });

            messageInput.addEventListener('blur', () => {
                if (isTyping) {
                    isTyping = false;
                    sendTypingIndicator(false);
                }
            });
        }

        // Pausar polling quando aba não está visível
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopPolling();
            } else {
                if (!USE_WEBSOCKETS || !websocketConnection) {
                    startPolling();
                }
            }
        });
    }

    /**
     * Inicializar WebSocket (Redis via Laravel Echo)
     * Nota: Requer Laravel Echo e Socket.IO ou Redis WebSocket server
     */
    function initWebSocket() {
        // Se não tiver Laravel Echo configurado, usar polling
        if (typeof Echo === 'undefined') {
            startPolling();
            return;
        }

        try {
            // Escutar eventos via Laravel Echo
            if (currentSessionDbId) {
                websocketConnection = Echo.private(`chat.session.${currentSessionDbId}`);

                websocketConnection
                    .listen('.message.sent', (data) => {
                        handleNewMessage(data);
                    })
                    .listen('.session.updated', (data) => {
                        handleSessionUpdate(data);
                    })
                    .listen('.user.typing', (data) => {
                        handleTypingIndicator(data);
                    });
            } else if (currentSessionId) {
                // Para visitantes, usar canal público
                websocketConnection = Echo.channel(`chat.session.${currentSessionId}`);

                websocketConnection
                    .listen('.message.sent', (data) => {
                        handleNewMessage(data);
                    })
                    .listen('.session.updated', (data) => {
                        handleSessionUpdate(data);
                    })
                    .listen('.user.typing', (data) => {
                        handleTypingIndicator(data);
                    });
            }

            // Canal para atendentes
            if (currentUserId) {
                Echo.channel('chat.agents')
                    .listen('.message.sent', (data) => {
                        // Atualizar lista de sessões se necessário
                        if (typeof window.updateChatSessionsList === 'function') {
                            window.updateChatSessionsList();
                        }
                    });
            }

            // Laravel Echo gerencia reconexão automaticamente
            stopPolling();

        } catch (error) {
            console.error('Erro ao inicializar WebSocket:', error);
            startPolling();
        }
    }

    /**
     * Iniciar polling
     */
    function startPolling() {
        stopPolling();

        pollingInterval = setInterval(() => {
            if (!document.hidden && currentSessionId) {
                loadMessages(true); // true = apenas novas mensagens
            }
        }, POLLING_INTERVAL);
    }

    /**
     * Parar polling
     */
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    /**
     * Carregar mensagens
     */
    async function loadMessages(onlyNew = false) {
        if (!currentSessionId && !currentSessionDbId) return;

        try {
            // Usar rotas definidas globalmente se disponíveis (web), senão usar API
            let url;
            if (window.CHAT_ROUTES && window.CHAT_ROUTES.getMessages) {
                // Usar rota web (co-admin/admin)
                url = `${window.CHAT_ROUTES.getMessages}?last_id=${onlyNew ? lastMessageId : 0}`;
            } else if (currentSessionDbId) {
                // Fallback para rota API (requer auth:sanctum)
                url = `/api/chat/session/${currentSessionDbId}/messages?last_id=${onlyNew ? lastMessageId : 0}`;
            } else {
                // Rota pública para visitantes
                url = `/chat/session/${currentSessionId}`;
            }

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                if (response.status === 401) {
                    console.error('Não autenticado. Verifique se está logado.');
                }
                return;
            }

            const data = await response.json();

            if (data.success && data.messages && data.messages.length > 0) {
                if (onlyNew) {
                    // Adicionar apenas novas mensagens
                    const newMessages = data.messages.filter(msg => msg.id > lastMessageId);
                    newMessages.forEach(msg => {
                        appendMessage(msg);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                } else {
                    // Recarregar todas as mensagens
                    if (messagesContainer) {
                        messagesContainer.innerHTML = '';
                    }
                    data.messages.forEach(msg => {
                        appendMessage(msg);
                        lastMessageId = Math.max(lastMessageId, msg.id);
                    });
                }
                scrollToBottom();
            }
        } catch (error) {
            console.error('Erro ao carregar mensagens:', error);
        }
    }

    /**
     * Enviar mensagem
     */
    async function handleSendMessage(e) {
        e.preventDefault();

        if (!messageInput || !currentSessionId && !currentSessionDbId) return;

        const message = messageInput.value.trim();
        if (!message) return;

        // Desabilitar input temporariamente
        messageInput.disabled = true;
        const originalValue = messageInput.value;
        messageInput.value = '';

        try {
            // Usar rotas definidas globalmente se disponíveis (web), senão usar API
            let url;
            if (window.CHAT_ROUTES && window.CHAT_ROUTES.sendMessage) {
                // Usar rota web (co-admin/admin)
                url = window.CHAT_ROUTES.sendMessage;
            } else if (currentSessionDbId) {
                // Fallback para rota API (requer auth:sanctum)
                url = `/api/chat/session/${currentSessionDbId}/message`;
            } else {
                // Rota pública para visitantes
                url = `/chat/session/${currentSessionId}/message`;
            }

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ message }),
            });

            const data = await response.json();

            if (data.success) {
                // Mensagem será adicionada via WebSocket ou polling
                // Recarregar mensagens após um pequeno delay
                setTimeout(() => {
                    loadMessages();
                }, 500);
            } else {
                alert(data.message || 'Erro ao enviar mensagem');
                messageInput.value = originalValue;
            }
        } catch (error) {
            console.error('Erro ao enviar mensagem:', error);
            alert('Erro ao enviar mensagem. Tente novamente.');
            messageInput.value = originalValue;
        } finally {
            messageInput.disabled = false;
            messageInput.focus();
        }
    }

    /**
     * Adicionar mensagem ao DOM
     */
    function appendMessage(msg) {
        if (!messagesContainer) return;

        const isUser = msg.sender_type === 'user' || msg.sender_type === 'system';
        const isSystem = msg.sender_type === 'system';
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-4 flex ${isUser ? 'justify-end' : 'justify-start'}`;
        messageDiv.setAttribute('data-message-id', msg.id);

        const senderName = msg.sender_type === 'user'
            ? (msg.sender?.name || 'Você')
            : (msg.sender_type === 'system' ? 'Sistema' : 'Visitante');

        const createdAt = new Date(msg.created_at).toLocaleString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        messageDiv.innerHTML = `
            <div class="max-w-[70%]">
                ${!isSystem ? `
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-xs font-medium ${isUser ? 'text-blue-600 dark:text-blue-400' : 'text-gray-600 dark:text-gray-400'}">
                        ${escapeHtml(senderName)}
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        ${createdAt}
                    </span>
                </div>
                ` : ''}
                <div class="px-4 py-2 rounded-lg ${isSystem
                ? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs text-center'
                : isUser
                    ? 'bg-blue-100 dark:bg-blue-900/30 text-gray-900 dark:text-white'
                    : 'bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-900 dark:text-white'
            }">
                    <p class="text-sm whitespace-pre-wrap">${escapeHtml(msg.message)}</p>
                </div>
            </div>
        `;

        messagesContainer.appendChild(messageDiv);
        scrollToBottom();
    }

    /**
     * Lidar com nova mensagem recebida via WebSocket
     */
    function handleNewMessage(data) {
        if (data.id > lastMessageId) {
            appendMessage(data);
            lastMessageId = Math.max(lastMessageId, data.id);

            // Tocar som de notificação se não for do próprio usuário
            if (data.sender_type !== 'user' || (data.sender && data.sender.id !== currentUserId)) {
                playNotificationSound();
            }
        }
    }

    /**
     * Lidar com atualização de sessão
     */
    function handleSessionUpdate(data) {
        // Atualizar UI conforme necessário
        if (typeof window.updateChatSessionStatus === 'function') {
            window.updateChatSessionStatus(data);
        }
    }

    /**
     * Lidar com indicador de digitação
     */
    function handleTypingIndicator(data) {
        if (!typingIndicator) return;

        // Não mostrar se for o próprio usuário
        if (data.user_id === currentUserId) return;

        if (data.is_typing) {
            typingIndicator.textContent = `${data.user_name} está digitando...`;
            typingIndicator.classList.remove('hidden');
        } else {
            typingIndicator.classList.add('hidden');
        }
    }

    /**
     * Enviar indicador de digitação
     */
    function sendTypingIndicator(typing) {
        if (!currentSessionId && !currentSessionDbId) return;

        // Enviar via API
        const url = currentSessionDbId
            ? `/api/chat/session/${currentSessionDbId}/typing`
            : `/chat/session/${currentSessionId}/typing`;

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            credentials: 'same-origin',
            body: JSON.stringify({ is_typing: typing }),
        }).catch(err => console.error('Erro ao enviar indicador de digitação:', err));
    }

    /**
     * Scroll para o final
     */
    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    /**
     * Tocar som de notificação
     */
    function playNotificationSound() {
        if (document.hidden) return; // Não tocar se aba estiver inativa

        try {
            const audio = new Audio('/sounds/chat/notification.mp3');
            audio.volume = 0.5;
            audio.play().catch(err => { });
        } catch (e) {
            // Som não disponível
        }
    }

    /**
     * Escapar HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Limpar recursos
     */
    function cleanup() {
        stopPolling();
        if (websocketConnection) {
            websocketConnection.disconnect();
            websocketConnection = null;
        }
    }

    // Exportar funções globais
    window.ChatSystem = {
        init: initChat,
        cleanup: cleanup,
        loadMessages: loadMessages,
        sendMessage: handleSendMessage,
    };

    // Limpar ao sair da página
    window.addEventListener('beforeunload', cleanup);
})();
