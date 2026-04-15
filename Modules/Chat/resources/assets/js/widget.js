/**
 * ==========================================================================
 * WIDGET PÚBLICO DE CHAT - VERTEX SEMAGRI
 * ==========================================================================
 * Widget de chat para visitantes do site
 */

import {
    escapeHtml,
    formatTime,
    validateCpf,
    formatCpf,
    scrollToBottom,
    fetchJson,
    getCsrfToken,
    setStorage,
    getStorage,
    removeStorage,
    playNotificationSound
} from './utils.js';

class ChatWidget {
    constructor(options = {}) {
        this.config = {
            apiUrl: options.apiUrl || '/chat/public',
            pollingInterval: options.pollingInterval || 3000,
            enableSound: options.enableSound !== false,
            welcomeMessage: options.welcomeMessage || 'Olá! Como posso ajudá-lo hoje?',
            offlineMessage: options.offlineMessage || 'Nosso atendimento não está disponível no momento.',
            ...options
        };

        this.state = {
            isOpen: false,
            isOnline: false,
            sessionId: null,
            visitorName: '',
            visitorCpf: '',
            lastMessageId: 0,
            pollingInterval: null,
            isLoading: false,
        };

        this.elements = {};
        this.storageKey = 'chat_widget_session';
    }

    /**
     * Inicializa o widget
     */
    async init() {
        this.createWidget();
        this.cacheElements();
        this.setupEventListeners();
        await this.checkOnlineStatus();
        this.restoreSession();

        console.log('[ChatWidget] Inicializado');
    }

    /**
     * Cria o HTML do widget
     */
    createWidget() {
        const widget = document.createElement('div');
        widget.id = 'chat-widget';
        widget.className = 'chat-widget';
        widget.innerHTML = this.getWidgetHtml();
        document.body.appendChild(widget);
    }

    /**
     * Cache dos elementos DOM
     */
    cacheElements() {
        this.elements = {
            widget: document.getElementById('chat-widget'),
            button: document.getElementById('chat-widget-button'),
            window: document.getElementById('chat-widget-window'),
            closeBtn: document.getElementById('chat-widget-close'),
            form: document.getElementById('chat-widget-form'),
            nameInput: document.getElementById('chat-visitor-name'),
            cpfInput: document.getElementById('chat-visitor-cpf'),
            startBtn: document.getElementById('chat-start-btn'),
            messagesContainer: document.getElementById('chat-widget-messages'),
            messageForm: document.getElementById('chat-message-form'),
            messageInput: document.getElementById('chat-message-input'),
            sendBtn: document.getElementById('chat-send-btn'),
            statusDot: document.getElementById('chat-status-dot'),
            statusText: document.getElementById('chat-status-text'),
            badge: document.getElementById('chat-badge'),
        };
    }

    /**
     * Configura event listeners
     */
    setupEventListeners() {
        // Toggle do widget
        this.elements.button?.addEventListener('click', () => this.toggle());
        this.elements.closeBtn?.addEventListener('click', () => this.close());

        // Formulário de identificação
        this.elements.form?.addEventListener('submit', (e) => this.handleStartChat(e));

        // Máscara de CPF
        this.elements.cpfInput?.addEventListener('input', (e) => {
            e.target.value = this.applyCpfMask(e.target.value);
        });

        // Envio de mensagem
        this.elements.messageForm?.addEventListener('submit', (e) => this.handleSendMessage(e));

        // Enter para enviar
        this.elements.messageInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.elements.messageForm?.dispatchEvent(new Event('submit'));
            }
        });

        // Fechar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.state.isOpen) {
                this.close();
            }
        });

        // Visibilidade da página
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopPolling();
            } else if (this.state.sessionId) {
                this.startPolling();
            }
        });
    }

    /**
     * Abre o widget
     */
    open() {
        this.state.isOpen = true;
        this.elements.window?.classList.remove('hidden');
        this.elements.button?.classList.add('open');
        this.hideBadge();

        if (this.state.sessionId) {
            this.startPolling();
            scrollToBottom(this.elements.messagesContainer);
        }
    }

    /**
     * Fecha o widget
     */
    close() {
        this.state.isOpen = false;
        this.elements.window?.classList.add('hidden');
        this.elements.button?.classList.remove('open');
    }

    /**
     * Toggle do widget
     */
    toggle() {
        if (this.state.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    /**
     * Verifica status online
     */
    async checkOnlineStatus() {
        try {
            const data = await fetchJson(`${this.config.apiUrl}/status`);
            this.state.isOnline = data.online === true;
            this.updateStatusIndicator();
        } catch (error) {
            console.error('[ChatWidget] Erro ao verificar status:', error);
            this.state.isOnline = false;
            this.updateStatusIndicator();
        }
    }

    /**
     * Atualiza indicador de status
     */
    updateStatusIndicator() {
        if (this.elements.statusDot) {
            this.elements.statusDot.className = `status-dot ${this.state.isOnline ? 'online' : 'offline'}`;
        }
        if (this.elements.statusText) {
            this.elements.statusText.textContent = this.state.isOnline ? 'Online' : 'Offline';
        }
    }

    /**
     * Restaura sessão anterior
     */
    restoreSession() {
        const saved = getStorage(this.storageKey);
        if (saved && saved.sessionId) {
            this.state.sessionId = saved.sessionId;
            this.state.visitorName = saved.visitorName || '';
            this.state.visitorCpf = saved.visitorCpf || '';
            this.showChatView();
            this.loadMessages();
        }
    }

    /**
     * Salva sessão
     */
    saveSession() {
        setStorage(this.storageKey, {
            sessionId: this.state.sessionId,
            visitorName: this.state.visitorName,
            visitorCpf: this.state.visitorCpf,
        });
    }

    /**
     * Inicia uma nova conversa
     */
    async handleStartChat(e) {
        e.preventDefault();

        const name = this.elements.nameInput?.value.trim();
        const cpf = this.elements.cpfInput?.value.replace(/\D/g, '');

        if (!name) {
            this.showError('Por favor, informe seu nome.');
            return;
        }

        if (!cpf || !validateCpf(cpf)) {
            this.showError('Por favor, informe um CPF válido.');
            return;
        }

        this.setLoading(true);

        try {
            const data = await fetchJson(`${this.config.apiUrl}/start`, {
                method: 'POST',
                body: JSON.stringify({ name, cpf }),
            });

            if (data.success && data.session_id) {
                this.state.sessionId = data.session_id;
                this.state.visitorName = name;
                this.state.visitorCpf = cpf;
                this.saveSession();
                this.showChatView();
                this.startPolling();

                // Adicionar mensagem de boas-vindas
                if (this.config.welcomeMessage) {
                    this.appendMessage({
                        id: 0,
                        message: this.config.welcomeMessage,
                        sender_type: 'user',
                        created_at: new Date().toISOString(),
                    });
                }
            } else {
                this.showError(data.message || 'Erro ao iniciar conversa.');
            }
        } catch (error) {
            console.error('[ChatWidget] Erro ao iniciar chat:', error);
            this.showError('Erro ao conectar. Tente novamente.');
        } finally {
            this.setLoading(false);
        }
    }

    /**
     * Envia uma mensagem
     */
    async handleSendMessage(e) {
        e.preventDefault();

        const input = this.elements.messageInput;
        if (!input || !this.state.sessionId) return;

        const message = input.value.trim();
        if (!message) return;

        input.disabled = true;
        if (this.elements.sendBtn) {
            this.elements.sendBtn.disabled = true;
        }

        const originalValue = input.value;
        input.value = '';

        // Adicionar mensagem localmente
        const tempId = Date.now();
        this.appendMessage({
            id: tempId,
            message,
            sender_type: 'visitor',
            created_at: new Date().toISOString(),
            pending: true,
        });

        try {
            const data = await fetchJson(`${this.config.apiUrl}/message`, {
                method: 'POST',
                body: JSON.stringify({
                    session_id: this.state.sessionId,
                    message,
                }),
            });

            if (data.success) {
                // Atualizar ID da mensagem
                const tempElement = this.elements.messagesContainer?.querySelector(
                    `[data-message-id="${tempId}"]`
                );
                if (tempElement && data.message?.id) {
                    tempElement.setAttribute('data-message-id', data.message.id);
                    tempElement.classList.remove('pending');
                }
            } else {
                input.value = originalValue;
                this.removeTempMessage(tempId);
                this.showError(data.message || 'Erro ao enviar mensagem.');
            }
        } catch (error) {
            console.error('[ChatWidget] Erro ao enviar mensagem:', error);
            input.value = originalValue;
            this.removeTempMessage(tempId);
            this.showError('Erro ao enviar mensagem.');
        } finally {
            input.disabled = false;
            if (this.elements.sendBtn) {
                this.elements.sendBtn.disabled = false;
            }
            input.focus();
        }
    }

    /**
     * Carrega mensagens
     */
    async loadMessages() {
        if (!this.state.sessionId) return;

        try {
            const data = await fetchJson(
                `${this.config.apiUrl}/messages?session_id=${this.state.sessionId}&last_id=${this.state.lastMessageId}`
            );

            if (data.success && data.messages?.length > 0) {
                let hasNewFromAgent = false;

                data.messages.forEach(msg => {
                    if (msg.id > this.state.lastMessageId) {
                        this.appendMessage(msg);
                        this.state.lastMessageId = msg.id;

                        if (msg.sender_type === 'user') {
                            hasNewFromAgent = true;
                        }
                    }
                });

                if (hasNewFromAgent) {
                    if (this.config.enableSound && !this.state.isOpen) {
                        playNotificationSound();
                    }
                    if (!this.state.isOpen) {
                        this.showBadge();
                    }
                    scrollToBottom(this.elements.messagesContainer);
                }
            }
        } catch (error) {
            console.error('[ChatWidget] Erro ao carregar mensagens:', error);
        }
    }

    /**
     * Adiciona mensagem ao DOM
     */
    appendMessage(msg) {
        const container = this.elements.messagesContainer;
        if (!container) return;

        if (container.querySelector(`[data-message-id="${msg.id}"]`)) return;

        const isVisitor = msg.sender_type === 'visitor';
        const time = formatTime(msg.created_at);

        const wrapper = document.createElement('div');
        wrapper.className = `message-wrapper flex ${isVisitor ? 'justify-end' : 'justify-start'} ${msg.pending ? 'pending' : ''}`;
        wrapper.setAttribute('data-message-id', msg.id);

        wrapper.innerHTML = `
            <div class="message-bubble ${isVisitor ? 'sent' : 'received'} max-w-[80%] px-4 py-2 rounded-2xl shadow-sm">
                <p class="text-sm whitespace-pre-wrap break-words">${escapeHtml(msg.message)}</p>
                <span class="text-xs opacity-60 mt-1 block text-right">${time}</span>
            </div>
        `;

        container.appendChild(wrapper);
        scrollToBottom(container);
    }

    /**
     * Remove mensagem temporária
     */
    removeTempMessage(id) {
        const element = this.elements.messagesContainer?.querySelector(
            `[data-message-id="${id}"]`
        );
        element?.remove();
    }

    /**
     * Mostra view de chat
     */
    showChatView() {
        if (this.elements.form) {
            this.elements.form.classList.add('hidden');
        }
        if (this.elements.messagesContainer) {
            this.elements.messagesContainer.classList.remove('hidden');
        }
        if (this.elements.messageForm) {
            this.elements.messageForm.classList.remove('hidden');
        }
    }

    /**
     * Mostra view de formulário
     */
    showFormView() {
        if (this.elements.form) {
            this.elements.form.classList.remove('hidden');
        }
        if (this.elements.messagesContainer) {
            this.elements.messagesContainer.classList.add('hidden');
        }
        if (this.elements.messageForm) {
            this.elements.messageForm.classList.add('hidden');
        }
    }

    /**
     * Mostra badge de notificação
     */
    showBadge() {
        if (this.elements.badge) {
            this.elements.badge.classList.remove('hidden');
        }
    }

    /**
     * Esconde badge de notificação
     */
    hideBadge() {
        if (this.elements.badge) {
            this.elements.badge.classList.add('hidden');
        }
    }

    /**
     * Define estado de loading
     */
    setLoading(loading) {
        this.state.isLoading = loading;
        if (this.elements.startBtn) {
            this.elements.startBtn.disabled = loading;
            this.elements.startBtn.innerHTML = loading
                ? '<span class="spinner"></span> Conectando...'
                : 'Iniciar Conversa';
        }
    }

    /**
     * Mostra erro
     */
    showError(message) {
        // Implementação simples - pode ser melhorada com toast
        alert(message);
    }

    /**
     * Aplica máscara de CPF
     */
    applyCpfMask(value) {
        return value
            .replace(/\D/g, '')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})/, '$1-$2')
            .replace(/(-\d{2})\d+?$/, '$1');
    }

    /**
     * Inicia polling
     */
    startPolling() {
        this.stopPolling();
        this.state.pollingInterval = setInterval(
            () => this.loadMessages(),
            this.config.pollingInterval
        );
    }

    /**
     * Para polling
     */
    stopPolling() {
        if (this.state.pollingInterval) {
            clearInterval(this.state.pollingInterval);
            this.state.pollingInterval = null;
        }
    }

    /**
     * Encerra sessão
     */
    endSession() {
        this.stopPolling();
        removeStorage(this.storageKey);
        this.state.sessionId = null;
        this.state.visitorName = '';
        this.state.visitorCpf = '';
        this.state.lastMessageId = 0;
        this.showFormView();
        if (this.elements.messagesContainer) {
            this.elements.messagesContainer.innerHTML = '';
        }
    }

    /**
     * Retorna HTML do widget
     */
    getWidgetHtml() {
        return `
            <!-- Botão flutuante -->
            <button id="chat-widget-button" class="chat-widget-button" aria-label="Abrir chat">
                <svg class="chat-widget-button__icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                </svg>
                <svg class="chat-widget-button__close-icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span id="chat-badge" class="chat-widget-button__badge hidden">1</span>
                <span id="chat-status-dot" class="chat-widget-button__status offline"></span>
            </button>

            <!-- Janela do chat -->
            <div id="chat-widget-window" class="chat-widget-window hidden">
                <!-- Header -->
                <div class="chat-widget-header">
                    <div class="chat-widget-header__info">
                        <div class="logo">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                            </svg>
                        </div>
                        <div class="text">
                            <h4>SEMAGRI - Suporte</h4>
                            <p><span id="chat-status-text">Offline</span></p>
                        </div>
                    </div>
                    <button id="chat-widget-close" class="chat-widget-header__close">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Formulário de identificação -->
                <form id="chat-widget-form" class="chat-widget-form">
                    <h3 class="chat-widget-form__title">Bem-vindo!</h3>
                    <p class="chat-widget-form__subtitle">Preencha seus dados para iniciar o atendimento</p>

                    <div class="chat-widget-form__group">
                        <label for="chat-visitor-name">Nome completo</label>
                        <input type="text" id="chat-visitor-name" placeholder="Seu nome" required>
                    </div>

                    <div class="chat-widget-form__group">
                        <label for="chat-visitor-cpf">CPF</label>
                        <input type="text" id="chat-visitor-cpf" placeholder="000.000.000-00" maxlength="14" required>
                    </div>

                    <button type="submit" id="chat-start-btn" class="chat-widget-form__submit">
                        Iniciar Conversa
                    </button>

                    <p class="chat-widget-form__privacy">
                        Seus dados estão protegidos conforme a LGPD.
                    </p>
                </form>

                <!-- Área de mensagens -->
                <div id="chat-widget-messages" class="chat-widget-messages hidden"></div>

                <!-- Input de mensagem -->
                <div id="chat-message-form-container" class="chat-widget-input hidden">
                    <form id="chat-message-form" class="chat-widget-input__form">
                        <textarea id="chat-message-input" placeholder="Digite sua mensagem..." rows="1"></textarea>
                        <button type="submit" id="chat-send-btn">
                            <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        `;
    }
}

// Exportar
export default ChatWidget;

// Disponibilizar globalmente
if (typeof window !== 'undefined') {
    window.ChatWidget = ChatWidget;
}

