/**
 * ==========================================================================
 * SISTEMA DE CHAT EM TEMPO REAL - VERTEX SEMAGRI
 * ==========================================================================
 * Sistema de chat 100% independente usando polling
 * Sem dependências externas (WebSockets opcionais)
 */

import {
    escapeHtml,
    formatTime,
    formatRelativeTime,
    scrollToBottom,
    fetchJson,
    getCsrfToken,
    debounce,
    playNotificationSound
} from './utils.js';
import toast from './toast.js';

class ChatSystem {
    constructor(options = {}) {
        // Configurações
        this.config = {
            pollingInterval: options.pollingInterval || 3000,
            sessionsPollingInterval: options.sessionsPollingInterval || 5000,
            baseUrl: options.baseUrl || '/diretoria/chat',
            currentUserId: options.currentUserId || null,
            enableSound: options.enableSound !== false,
            enableNotifications: options.enableNotifications !== false,
            ...options
        };

        // Estado
        this.state = {
            currentSessionId: null,
            lastMessageId: 0,
            isTyping: false,
            typingTimeout: null,
            pollingInterval: null,
            sessionsPollingInterval: null,
            isInitialized: false,
            sessions: [],
            messages: [],
        };

        // Elementos DOM
        this.elements = {};

        // Bind methods
        this.handleSendMessage = this.handleSendMessage.bind(this);
        this.handleSessionClick = this.handleSessionClick.bind(this);
        this.handleSearch = debounce(this.handleSearch.bind(this), 300);
    }

    /**
     * Inicializa o sistema de chat
     * @param {number|null} sessionId - ID da sessão ativa (opcional)
     */
    init(sessionId = null) {
        if (this.state.isInitialized) return;

        this.cacheElements();
        this.setupEventListeners();

        if (sessionId) {
            this.state.currentSessionId = sessionId;
            this.updateLastMessageId();
            this.startMessagePolling();
            scrollToBottom(this.elements.messagesContainer);
        }

        this.startSessionsPolling();
        this.state.isInitialized = true;

        console.log('[ChatSystem] Inicializado com sucesso');
    }

    /**
     * Cache dos elementos DOM
     */
    cacheElements() {
        this.elements = {
            messagesContainer: document.getElementById('chat-messages'),
            messageForm: document.getElementById('message-form'),
            messageInput: document.getElementById('message-input'),
            sendButton: document.getElementById('send-button'),
            sessionsList: document.getElementById('sessions-list'),
            searchInput: document.getElementById('search-sessions'),
            typingIndicator: document.getElementById('typing-indicator'),
            statsWaiting: document.getElementById('stats-waiting'),
            statsActive: document.getElementById('stats-active'),
            chatSidebar: document.getElementById('chat-sidebar'),
            closeSessionBtn: document.getElementById('btn-close-session'),
            reopenSessionBtn: document.getElementById('btn-reopen-session'),
        };
    }

    /**
     * Configura event listeners
     */
    setupEventListeners() {
        // Envio de mensagem
        if (this.elements.messageForm) {
            this.elements.messageForm.addEventListener('submit', this.handleSendMessage);
        }

        // Input de mensagem
        if (this.elements.messageInput) {
            this.elements.messageInput.addEventListener('input', () => {
                this.autoResizeTextarea();
                this.handleTyping();
            });

            this.elements.messageInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    this.elements.messageForm?.dispatchEvent(new Event('submit'));
                }
            });
        }

        // Clique em sessão
        if (this.elements.sessionsList) {
            this.elements.sessionsList.addEventListener('click', this.handleSessionClick);
        }

        // Busca de sessões
        if (this.elements.searchInput) {
            this.elements.searchInput.addEventListener('input', this.handleSearch);
        }

        // Botões de ação
        if (this.elements.closeSessionBtn) {
            this.elements.closeSessionBtn.addEventListener('click', () => this.closeSession());
        }

        if (this.elements.reopenSessionBtn) {
            this.elements.reopenSessionBtn.addEventListener('click', () => this.reopenSession());
        }

        // Visibilidade da página
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopMessagePolling();
            } else if (this.state.currentSessionId) {
                this.loadNewMessages();
                this.startMessagePolling();
            }
        });

        // Cleanup ao sair
        window.addEventListener('beforeunload', () => {
            this.destroy();
        });
    }

    /**
     * Envia uma mensagem
     */
    async handleSendMessage(e) {
        e.preventDefault();

        const input = this.elements.messageInput;
        if (!input || !this.state.currentSessionId) return;

        const message = input.value.trim();
        if (!message) return;

        // Desabilitar input
        input.disabled = true;
        if (this.elements.sendButton) {
            this.elements.sendButton.disabled = true;
        }

        const originalValue = input.value;
        input.value = '';
        this.autoResizeTextarea();

        try {
            const data = await fetchJson(
                `${this.config.baseUrl}/${this.state.currentSessionId}/api/message`,
                {
                    method: 'POST',
                    body: JSON.stringify({ message }),
                }
            );

            if (data.success && data.message) {
                this.appendMessage(data.message);
                scrollToBottom(this.elements.messagesContainer);
            } else {
                input.value = originalValue;
                toast.error(data.message || 'Erro ao enviar mensagem');
            }
        } catch (error) {
            console.error('[ChatSystem] Erro ao enviar mensagem:', error);
            input.value = originalValue;
            toast.error('Erro ao enviar mensagem. Tente novamente.');
        } finally {
            input.disabled = false;
            if (this.elements.sendButton) {
                this.elements.sendButton.disabled = false;
            }
            input.focus();
        }
    }

    /**
     * Carrega novas mensagens via polling
     */
    async loadNewMessages() {
        if (!this.state.currentSessionId) return;

        try {
            const data = await fetchJson(
                `${this.config.baseUrl}/${this.state.currentSessionId}/api/messages?last_id=${this.state.lastMessageId}`
            );

            if (data.success && data.messages?.length > 0) {
                let hasNewFromVisitor = false;

                data.messages.forEach(msg => {
                    if (msg.id > this.state.lastMessageId) {
                        this.appendMessage(msg);
                        this.state.lastMessageId = msg.id;

                        if (msg.sender_type === 'visitor') {
                            hasNewFromVisitor = true;
                        }
                    }
                });

                if (hasNewFromVisitor) {
                    if (this.config.enableSound) {
                        playNotificationSound();
                    }
                    scrollToBottom(this.elements.messagesContainer);
                }
            }
        } catch (error) {
            console.error('[ChatSystem] Erro ao carregar mensagens:', error);
        }
    }

    /**
     * Atualiza lista de sessões
     */
    async updateSessionsList() {
        try {
            const data = await fetchJson(`${this.config.baseUrl}/api/sessions`);

            if (data.success && data.sessions) {
                this.state.sessions = data.sessions;

                // Atualizar estatísticas
                const waiting = data.sessions.filter(s => s.status === 'waiting').length;
                const active = data.sessions.filter(s => s.status === 'active').length;

                if (this.elements.statsWaiting) {
                    this.elements.statsWaiting.textContent = waiting;
                }
                if (this.elements.statsActive) {
                    this.elements.statsActive.textContent = active;
                }

                // Atualizar badges de não lidas
                this.updateUnreadBadges(data.sessions);
            }
        } catch (error) {
            console.error('[ChatSystem] Erro ao atualizar sessões:', error);
        }
    }

    /**
     * Atualiza badges de mensagens não lidas
     */
    updateUnreadBadges(sessions) {
        if (!this.elements.sessionsList) return;

        sessions.forEach(session => {
            const item = this.elements.sessionsList.querySelector(
                `[data-session-id="${session.id}"]`
            );
            if (!item) return;

            const badge = item.querySelector('.unread-badge');
            if (session.unread_count > 0) {
                if (badge) {
                    badge.textContent = session.unread_count;
                    badge.classList.remove('hidden');
                }
            } else if (badge) {
                badge.classList.add('hidden');
            }
        });
    }

    /**
     * Adiciona mensagem ao DOM
     */
    appendMessage(msg) {
        const container = this.elements.messagesContainer;
        if (!container) return;

        // Verificar se já existe
        if (container.querySelector(`[data-message-id="${msg.id}"]`)) return;

        const isSent = msg.sender_type === 'user';
        const isSystem = msg.sender_type === 'system';

        const wrapper = document.createElement('div');
        wrapper.className = `message-wrapper flex ${isSent ? 'justify-end' : (isSystem ? 'justify-center' : 'justify-start')}`;
        wrapper.setAttribute('data-message-id', msg.id);

        const time = formatTime(msg.created_at);

        if (isSystem) {
            wrapper.innerHTML = `
                <div class="message-bubble system px-4 py-2">
                    <p class="text-gray-600 dark:text-gray-400">${escapeHtml(msg.message)}</p>
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <div class="message-bubble ${isSent ? 'sent' : 'received'} px-4 py-2 shadow-sm">
                    <p class="text-sm whitespace-pre-wrap break-words">${escapeHtml(msg.message)}</p>
                    <div class="flex items-center justify-end gap-1 mt-1">
                        <span class="text-xs opacity-60">${time}</span>
                        ${isSent ? `
                        <svg class="w-4 h-4 ${msg.is_read ? 'text-blue-500' : 'opacity-60'}" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        // Inserir antes do indicador de digitação
        if (this.elements.typingIndicator) {
            container.insertBefore(wrapper, this.elements.typingIndicator);
        } else {
            container.appendChild(wrapper);
        }
    }

    /**
     * Handler de clique em sessão
     */
    handleSessionClick(e) {
        const sessionItem = e.target.closest('.chat-session-item');
        if (!sessionItem) return;

        const sessionId = sessionItem.dataset.sessionId;
        if (sessionId) {
            window.location.href = `${this.config.baseUrl}/realtime?session=${sessionId}`;
        }
    }

    /**
     * Handler de busca
     */
    handleSearch() {
        const query = this.elements.searchInput?.value.toLowerCase() || '';
        const items = this.elements.sessionsList?.querySelectorAll('.chat-session-item') || [];

        items.forEach(item => {
            const name = item.querySelector('h4')?.textContent.toLowerCase() || '';
            const message = item.querySelector('p')?.textContent.toLowerCase() || '';

            if (name.includes(query) || message.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    /**
     * Handler de digitação
     */
    handleTyping() {
        if (!this.state.isTyping) {
            this.state.isTyping = true;
            // Aqui poderia enviar indicador de digitação para o servidor
        }

        clearTimeout(this.state.typingTimeout);
        this.state.typingTimeout = setTimeout(() => {
            this.state.isTyping = false;
        }, 2000);
    }

    /**
     * Auto-resize do textarea
     */
    autoResizeTextarea() {
        const textarea = this.elements.messageInput;
        if (!textarea) return;

        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }

    /**
     * Encerra a sessão atual
     */
    async closeSession() {
        if (!this.state.currentSessionId) return;

        if (!confirm('Deseja encerrar esta conversa?')) return;

        try {
            const data = await fetchJson(
                `${this.config.baseUrl}/${this.state.currentSessionId}/close`,
                { method: 'POST' }
            );

            if (data.success) {
                toast.success('Conversa encerrada com sucesso!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                toast.error(data.message || 'Erro ao encerrar conversa');
            }
        } catch (error) {
            console.error('[ChatSystem] Erro ao encerrar sessão:', error);
            toast.error('Erro ao encerrar conversa');
        }
    }

    /**
     * Reabre a sessão atual
     */
    async reopenSession() {
        if (!this.state.currentSessionId) return;

        try {
            const data = await fetchJson(
                `${this.config.baseUrl}/${this.state.currentSessionId}/reopen`,
                { method: 'POST' }
            );

            if (data.success) {
                toast.success('Conversa reaberta com sucesso!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                toast.error(data.message || 'Erro ao reabrir conversa');
            }
        } catch (error) {
            console.error('[ChatSystem] Erro ao reabrir sessão:', error);
            toast.error('Erro ao reabrir conversa');
        }
    }

    /**
     * Atualiza o último ID de mensagem
     */
    updateLastMessageId() {
        if (!this.elements.messagesContainer) return;

        const messages = this.elements.messagesContainer.querySelectorAll('[data-message-id]');
        if (messages.length > 0) {
            const lastMsg = messages[messages.length - 1];
            this.state.lastMessageId = parseInt(lastMsg.dataset.messageId) || 0;
        }
    }

    /**
     * Inicia polling de mensagens
     */
    startMessagePolling() {
        this.stopMessagePolling();
        this.state.pollingInterval = setInterval(
            () => this.loadNewMessages(),
            this.config.pollingInterval
        );
    }

    /**
     * Para polling de mensagens
     */
    stopMessagePolling() {
        if (this.state.pollingInterval) {
            clearInterval(this.state.pollingInterval);
            this.state.pollingInterval = null;
        }
    }

    /**
     * Inicia polling de sessões
     */
    startSessionsPolling() {
        this.state.sessionsPollingInterval = setInterval(
            () => this.updateSessionsList(),
            this.config.sessionsPollingInterval
        );
    }

    /**
     * Para polling de sessões
     */
    stopSessionsPolling() {
        if (this.state.sessionsPollingInterval) {
            clearInterval(this.state.sessionsPollingInterval);
            this.state.sessionsPollingInterval = null;
        }
    }

    /**
     * Destrói a instância
     */
    destroy() {
        this.stopMessagePolling();
        this.stopSessionsPolling();
        clearTimeout(this.state.typingTimeout);
        this.state.isInitialized = false;
    }
}

// Exportar para uso global
export default ChatSystem;

// Disponibilizar globalmente
if (typeof window !== 'undefined') {
    window.ChatSystem = ChatSystem;
}

