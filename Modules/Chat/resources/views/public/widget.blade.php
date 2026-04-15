@php
$chatEnabled = module_enabled('Chat');
$publicEnabled = \Modules\Chat\App\Models\ChatConfig::isPublicEnabled();
@endphp
@if($chatEnabled && $publicEnabled)
<!-- Chat widget público JUBAF -->
<div id="semagri-chat-widget" class="fixed font-sans" style="bottom: 2rem; right: 2rem; z-index: 9999;">
    <!-- Botão Flutuante -->
    <button id="chat-toggle-btn"
            class="group relative w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white rounded-full shadow-2xl hover:shadow-green-500/30 transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-green-500/20 transform hover:scale-110 active:scale-95 border-b-4 border-green-800 flex items-center justify-center"
            aria-label="Abrir chat JUBAF">

        <!-- Ícone de Chat (Padrão) -->
        <span id="chat-icon" class="absolute inset-0 flex items-center justify-center transition-all duration-300 group-hover:rotate-12">
            <x-icon name="comment-dots" style="duotone" class="w-8 h-8" />
        </span>

        <!-- Ícone de Fechar (Ativo) -->
        <span id="chat-close-icon" class="absolute inset-0 flex items-center justify-center transition-all duration-300 opacity-0 scale-50 rotate-90">
            <x-icon name="xmark" class="w-8 h-8" />
        </span>

        <!-- Badge de Notificação -->
        <span id="chat-notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-black rounded-full w-6 h-6 flex items-center justify-center hidden animate-bounce border-2 border-white dark:border-slate-900 shadow-lg pointer-events-none">1</span>
    </button>

    <!-- Janela do Chat -->
    <div id="chat-window" class="hidden absolute bottom-24 right-0 w-[400px] max-w-[calc(100vw-2rem)] h-[600px] max-h-[calc(100vh-8rem)] bg-white dark:bg-slate-800 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-slate-700 flex flex-col overflow-hidden animate-fade-in-up origin-bottom-right" style="z-index: 10000;">

        <!-- Header -->
        <div class="relative px-8 py-6 bg-gradient-to-br from-green-600 to-green-800 text-white flex-shrink-0 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-inner border border-white/10">
                    <x-icon name="headset" style="duotone" class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="font-black text-lg uppercase tracking-tight leading-none mb-1">Apoio JUBAF</h3>
                    <p id="chat-status-text" class="text-[10px] font-black uppercase tracking-widest text-green-100/90 flex items-center gap-2">
                        <span class="inline-block w-2 h-2 bg-green-300 rounded-full animate-pulse shadow-[0_0_8px_rgba(134,239,172,0.8)]"></span>
                        Disponível
                    </p>
                </div>
            </div>
            <button id="chat-minimize-btn" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/10 rounded-lg transition-all active:scale-95 z-20">
                <x-icon name="chevron-down" class="w-5 h-5" />
            </button>
        </div>

        <!-- Mensagem Offline -->
        <div id="chat-offline-message" class="hidden flex-1 flex flex-col items-center justify-center p-8 text-center bg-amber-50 dark:bg-amber-900/10">
            <div class="w-20 h-20 mb-6 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center shadow-inner text-amber-500 ring-4 ring-white dark:ring-slate-800">
                <x-icon name="clock" style="duotone" class="w-10 h-10" />
            </div>
            <h4 class="text-xl font-black text-amber-900 dark:text-amber-200 uppercase tracking-tight mb-2">Chat indisponível</h4>
            <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest mb-6" id="chat-next-availability">Tente mais tarde ou use o formulário de contato do site.</p>
            <p class="text-sm text-amber-700/80 dark:text-amber-300/60 max-w-[200px] leading-relaxed">O atendimento segue o horário configurado e a disponibilidade da diretoria no painel.</p>
        </div>

        <!-- Formulário Inicial -->
        <div id="chat-initial-form" class="hidden flex-1 flex flex-col p-8 bg-slate-50 dark:bg-slate-900/30 overflow-y-auto custom-scrollbar">
            <div class="text-center mb-8">
                <h4 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-1">Olá! 👋</h4>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Identifique-se para começar</p>
            </div>
            <form id="chat-start-form" class="space-y-4">
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nome Completo <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="user" class="w-4 h-4 text-slate-300 group-focus-within:text-green-500 transition-colors" />
                        </div>
                        <input type="text" id="visitor-name" name="name" required
                            placeholder="Seu nome"
                            class="w-full pl-10 pr-4 py-3.5 text-sm font-bold bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-all shadow-sm">
                    </div>
                </div>
                <div class="group">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">CPF <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <x-icon name="id-card" class="w-4 h-4 text-slate-300 group-focus-within:text-green-500 transition-colors" />
                        </div>
                        <input type="text" id="visitor-cpf" name="cpf" required
                            placeholder="000.000.000-00" maxlength="14"
                            class="w-full pl-10 pr-4 py-3.5 text-sm font-bold bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-all shadow-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">E-mail</label>
                        <input type="email" id="visitor-email" name="email"
                            placeholder="opcional@email.com"
                            class="w-full px-4 py-3.5 text-sm font-bold bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-all shadow-sm">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Telefone</label>
                        <input type="tel" id="visitor-phone" name="phone"
                            placeholder="(00) 00000-0000"
                            class="w-full px-4 py-3.5 text-sm font-bold bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:text-white transition-all shadow-sm">
                    </div>
                </div>
                <button type="submit" id="chat-start-btn"
                        class="w-full mt-2 px-6 py-4 bg-slate-900 hover:bg-black dark:bg-green-600 dark:hover:bg-green-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg active:scale-95 flex items-center justify-center gap-2">
                    Iniciar Atendimento
                    <x-icon name="arrow-right" class="w-4 h-4" />
                </button>
            </form>
        </div>

        <!-- Área de Mensagens -->
        <div id="chat-messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50 dark:bg-slate-900/50 custom-scrollbar"
             style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 20px 20px; opacity: 1;">
            <div id="chat-welcome-message" class="flex flex-col items-center justify-center h-full text-center py-10 opacity-60">
                <div class="w-16 h-16 bg-slate-200 dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-4 text-slate-400">
                    <x-icon name="message-dots" style="duotone" class="w-8 h-8" />
                </div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Inicie a conversa</p>
            </div>
        </div>

        <!-- Indicador de Digitação -->
        <div id="chat-typing-indicator" class="hidden px-6 py-2 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700/50">
            <div class="flex items-center gap-2">
                <div class="flex gap-1">
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-1.5 h-1.5 bg-slate-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                </div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Digitando...</span>
            </div>
        </div>

        <!-- Input de Mensagem -->
        <div id="chat-input-container" class="hidden p-4 bg-white dark:bg-slate-800 border-t border-slate-100 dark:border-slate-700/50">
            <form id="chat-message-form" class="flex items-end gap-2">
                <div class="flex-1 relative">
                    <textarea id="chat-message-input"
                              rows="1"
                              placeholder="Digite sua mensagem..."
                              class="w-full pl-4 pr-10 py-3 text-sm font-medium bg-slate-50 dark:bg-slate-900 border-none rounded-xl focus:ring-2 focus:ring-green-500/20 dark:text-white transition-all resize-none custom-scrollbar max-h-32 focus:bg-white dark:focus:bg-slate-900"></textarea>
                    <div class="absolute right-3 bottom-3 text-slate-300 pointer-events-none">
                        <x-icon name="paper-plane-top" class="w-4 h-4 opacity-50" style="duotone" />
                    </div>
                </div>
                <button type="submit" id="chat-send-btn"
                        class="w-11 h-11 bg-green-600 hover:bg-green-700 text-white rounded-xl flex items-center justify-center shadow-lg shadow-green-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:grayscale flex-shrink-0">
                    <x-icon name="paper-plane-top" class="w-5 h-5" style="duotone" />
                </button>
            </form>
        </div>

        <!-- Sessão Encerrada -->
        <div id="chat-session-closed" class="hidden flex-1 flex flex-col items-center justify-center p-8 text-center bg-slate-50 dark:bg-slate-900/50">
            <div class="w-20 h-20 mb-6 bg-slate-200 dark:bg-slate-800 rounded-full flex items-center justify-center text-slate-400">
                <x-icon name="check-double" class="w-10 h-10" />
            </div>
            <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mb-2">Atendimento Encerrado</h4>
            <p class="text-xs text-slate-500 font-medium mb-8 max-w-[200px]">Obrigado por entrar em contato conosco.</p>
            <button id="chat-new-session-btn"
                    class="px-6 py-3 bg-white border border-slate-200 text-slate-700 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all shadow-sm">
                Iniciar Nova Conversa
            </button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }

    #semagri-chat-widget .message-bubble {
        max-width: 85%;
        word-wrap: break-word;
        animation: chatSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    #semagri-chat-widget .message-bubble.sent {
        background: #10b981;
        margin-left: auto;
        border-radius: 18px 18px 4px 18px;
        color: white;
        box-shadow: 0 4px 12px -2px rgba(16, 185, 129, 0.2);
    }

    #semagri-chat-widget .message-bubble.received {
        background: white;
        margin-right: auto;
        border-radius: 18px 18px 18px 4px;
        color: #1e293b;
        box-shadow: 0 2px 8px -2px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .dark #semagri-chat-widget .message-bubble.received {
        background: #1e293b;
        color: #f8fafc;
        border-color: #334155;
    }

    @keyframes chatSlideIn {
        from { opacity: 0; transform: translateY(10px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes chatFadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in-up {
        animation: chatFadeInUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
(function() {
    'use strict';
    if (window.semagriChatInitialized) return;
    window.semagriChatInitialized = true;

    // Configuração
    const POLLING_INTERVAL = 3000;
    const ROUTES = {
        status: '{{ route("chat.status") }}',
        start: '{{ route("chat.start") }}',
        session: (id) => `/chat/session/${id}`,
        message: (id) => `/chat/session/${id}/message`,
    };

    const state = {
        isOpen: false,
        sessionId: null,
        isAvailable: false,
        lastMessageId: 0,
        pollInterval: null
    };

    const elements = {
        toggleBtn: document.getElementById('chat-toggle-btn'),
        iconOpen: document.getElementById('chat-icon'),
        iconClose: document.getElementById('chat-close-icon'),
        chatWindow: document.getElementById('chat-window'),
        minimizeBtn: document.getElementById('chat-minimize-btn'),
        offlineMessage: document.getElementById('chat-offline-message'),
        initialForm: document.getElementById('chat-initial-form'),
        startForm: document.getElementById('chat-start-form'),
        messagesContainer: document.getElementById('chat-messages-container'),
        welcomeMessage: document.getElementById('chat-welcome-message'),
        inputContainer: document.getElementById('chat-input-container'),
        messageForm: document.getElementById('chat-message-form'),
        messageInput: document.getElementById('chat-message-input'),
        sessionClosed: document.getElementById('chat-session-closed'),
        newSessionBtn: document.getElementById('chat-new-session-btn'),
        typingIndicator: document.getElementById('chat-typing-indicator'),
        statusText: document.getElementById('chat-status-text')
    };

    function init() {
        setupEventListeners();
        setupCpfMask();
        restoreSession();
    }

    function setupEventListeners() {
        elements.toggleBtn?.addEventListener('click', toggleChat);
        elements.minimizeBtn?.addEventListener('click', toggleChat);
        elements.startForm?.addEventListener('submit', handleStartChat);
        elements.messageForm?.addEventListener('submit', handleSendMessage);
        elements.messageInput?.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';
        });
        elements.messageInput?.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                elements.messageForm.dispatchEvent(new Event('submit'));
            }
        });
        elements.newSessionBtn?.addEventListener('click', handleNewSession);
    }

    function setupCpfMask() {
        const cpfInput = document.getElementById('visitor-cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);

                if (value.length > 9) value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                else if (value.length > 6) value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, '$1.$2.$3');
                else if (value.length > 3) value = value.replace(/(\d{3})(\d{1,3})/, '$1.$2');

                e.target.value = value;
            });
        }
    }

    function toggleChat() {
        state.isOpen = !state.isOpen;

        if (state.isOpen) {
            elements.chatWindow.classList.remove('hidden');
            // Animate Icons
            elements.iconOpen.classList.add('opacity-0', 'scale-50', '-rotate-90');
            elements.iconClose.classList.remove('opacity-0', 'scale-50', 'rotate-90');

            checkAvailability().then(() => {
                if (state.sessionId) {
                    showChatInterface();
                    loadMessages();
                    startPolling();
                } else if (state.isAvailable) {
                    showInitialForm();
                } else {
                    showOfflineMessage();
                }
            });
        } else {
            elements.chatWindow.classList.add('hidden');
            // Animate Icons
            elements.iconOpen.classList.remove('opacity-0', 'scale-50', '-rotate-90');
            elements.iconClose.classList.add('opacity-0', 'scale-50', 'rotate-90');
            stopPolling();
        }
    }

    async function checkAvailability() {
        try {
            const response = await fetch(ROUTES.status);
            const data = await response.json();
            state.isAvailable = data.available;

            if (elements.statusText) {
                const agents = typeof data.agents_online === 'number' ? data.agents_online : 0;
                const inHours = data.within_office_hours === true;
                let extra = '';
                if (state.isAvailable && agents > 0 && !inHours) {
                    extra = '<span class="block text-[9px] font-bold text-green-100/90 normal-case tracking-normal mt-1">Diretoria online no painel</span>';
                } else if (state.isAvailable && agents > 0) {
                    extra = '<span class="block text-[9px] font-bold text-green-100/90 normal-case tracking-normal mt-1">' + agents + ' atendente(s) na rede</span>';
                }
                elements.statusText.innerHTML = state.isAvailable
                    ? '<span class="inline-block w-2 h-2 bg-green-300 rounded-full animate-pulse shadow-[0_0_8px_rgba(134,239,172,0.8)]"></span>Disponível' + extra
                    : '<span class="inline-block w-2 h-2 bg-amber-300 rounded-full opacity-70"></span>Indisponível';
            }
        } catch (e) {
            state.isAvailable = true;
        }
    }

    async function restoreSession() {
        const savedId = localStorage.getItem('semagri_chat_session');
        if (savedId) {
            try {
                const response = await fetch(ROUTES.session(savedId), { headers: { 'Accept': 'application/json' } });
                const data = await response.json();
                if (data.success && data.session.status !== 'closed') {
                    state.sessionId = savedId;
                } else {
                    localStorage.removeItem('semagri_chat_session');
                }
            } catch (e) {
                localStorage.removeItem('semagri_chat_session');
            }
        }
    }

    async function handleStartChat(e) {
        e.preventDefault();
        const btn = document.getElementById('chat-start-btn');
        btn.disabled = true;

        const formData = {
            name: document.getElementById('visitor-name').value,
            cpf: document.getElementById('visitor-cpf').value.replace(/\D/g, ''),
            email: document.getElementById('visitor-email').value,
            phone: document.getElementById('visitor-phone').value
        };

        try {
            const response = await fetch(ROUTES.start, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify(formData)
            });
            const data = await response.json();

            if (data.success || data.existing_session_id) {
                state.sessionId = data.session_id || data.existing_session_id;
                localStorage.setItem('semagri_chat_session', state.sessionId);
                showChatInterface();
                loadMessages();
                startPolling();
            } else {
                alert(data.message || 'Erro ao iniciar chat');
            }
        } catch (e) {
            console.error(e);
        } finally {
            btn.disabled = false;
        }
    }

    async function handleSendMessage(e) {
        e.preventDefault();
        const input = elements.messageInput;
        const message = input.value.trim();
        if (!message) return;

        input.value = '';
        input.style.height = 'auto';

        try {
            await fetch(ROUTES.message(state.sessionId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({ message })
            });
            loadMessages();
        } catch (e) {
            input.value = message;
        }
    }

    async function loadMessages() {
        if (!state.sessionId) return;
        try {
            const response = await fetch(ROUTES.session(state.sessionId), { headers: { 'Accept': 'application/json' } });
            const data = await response.json();

            if (data.success) {
                if (data.session.status === 'closed') {
                    handleSessionClosed();
                    return;
                }

                if (data.messages) {
                    // Clear if initial load or simple diff check
                    if (state.lastMessageId === 0) elements.messagesContainer.innerHTML = '';

                    data.messages.forEach(msg => {
                        if (msg.id > state.lastMessageId) {
                            appendMessage(msg);
                            state.lastMessageId = msg.id;
                        }
                    });

                    scrollToBottom();
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    function appendMessage(msg) {
        const isSent = msg.sender_type === 'visitor';
        const isSystem = msg.sender_type === 'system';
        const div = document.createElement('div');
        div.className = `flex ${isSent ? 'justify-end' : (isSystem ? 'justify-center' : 'justify-start')}`;

        if (isSystem) {
            div.innerHTML = `<div class="bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-2">${msg.message}</div>`;
        } else {
            div.innerHTML = `
                <div class="message-bubble ${isSent ? 'sent' : 'received'} px-4 py-2.5 mb-2 text-sm">
                    ${msg.message}
                    <div class="text-[9px] text-right mt-1 opacity-70">${new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</div>
                </div>
            `;
        }
        elements.messagesContainer.appendChild(div);
    }

    function showChatInterface() {
        elements.offlineMessage.classList.add('hidden');
        elements.initialForm.classList.add('hidden');
        elements.sessionClosed.classList.add('hidden');
        elements.messagesContainer.classList.remove('hidden');
        elements.inputContainer.classList.remove('hidden');
        elements.welcomeMessage.classList.add('hidden');
    }

    function showInitialForm() {
        elements.offlineMessage.classList.add('hidden');
        elements.initialForm.classList.remove('hidden');
        elements.sessionClosed.classList.add('hidden');
        elements.messagesContainer.classList.add('hidden');
        elements.inputContainer.classList.add('hidden');
    }

    function showOfflineMessage() {
        elements.offlineMessage.classList.remove('hidden');
        elements.initialForm.classList.add('hidden');
        elements.sessionClosed.classList.add('hidden');
        elements.messagesContainer.classList.add('hidden');
        elements.inputContainer.classList.add('hidden');
    }

    function handleSessionClosed() {
        elements.inputContainer.classList.add('hidden');
        elements.sessionClosed.classList.remove('hidden');
        stopPolling();
    }

    function handleNewSession() {
        localStorage.removeItem('semagri_chat_session');
        state.sessionId = null;
        state.lastMessageId = 0;
        if (state.isAvailable) showInitialForm();
        else showOfflineMessage();
    }

    function startPolling() {
        if (!state.pollInterval) state.pollInterval = setInterval(loadMessages, POLLING_INTERVAL);
    }

    function stopPolling() {
        if (state.pollInterval) {
            clearInterval(state.pollInterval);
            state.pollInterval = null;
        }
    }

    function scrollToBottom() {
        elements.messagesContainer.scrollTop = elements.messagesContainer.scrollHeight;
    }

    init();
})();
</script>
@endif
