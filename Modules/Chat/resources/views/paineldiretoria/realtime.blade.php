@extends('layouts.app')

@section('title', 'Chat — Tempo real')

@push('styles')
<style>
    .chat-diretoria-container { height: calc(100vh - 14rem); min-height: 520px; }
    @media (min-width: 768px) { .chat-diretoria-container { min-height: 600px; } }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 20px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endpush

@section('content')
@php
    $searchInputClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 pl-10 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('chat::paineldiretoria.partials.subnav', ['active' => 'realtime'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Atendimento contínuo</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="bolt" class="h-5 w-5" style="duotone" />
                </span>
                Tempo real
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Selecione uma sessão à esquerda e responda no painel. A lista atualiza automaticamente.</p>
        </div>
        <a href="{{ route('diretoria.chat.index') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
            <x-icon name="list" class="h-4 w-4" style="duotone" />
            Ver todas as sessões
        </a>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                    <x-icon name="clock" class="h-5 w-5" style="duotone" />
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Aguardando</p>
                    <p id="stats-waiting" class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $sessions->where('status', 'waiting')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                    <x-icon name="message-dots" class="h-5 w-5" style="duotone" />
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Em atendimento</p>
                    <p id="stats-active" class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $sessions->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>
        <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-50 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300">
                    <x-icon name="users" class="h-5 w-5" style="duotone" />
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Visitantes (lista)</p>
                    <p class="text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $sessions->unique('visitor_name')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="chat-diretoria-container flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800 md:flex-row">
        <div class="flex w-full flex-col border-b border-gray-200 dark:border-slate-700 md:w-80 md:border-b-0 md:border-r lg:w-96">
            <div class="border-b border-gray-100 p-4 dark:border-slate-700">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <x-icon name="magnifying-glass" class="h-4 w-4" />
                    </span>
                    <input type="text" id="search-sessions" placeholder="Pesquisar conversas…" class="{{ $searchInputClass }}">
                </div>
            </div>

            <div id="sessions-list" class="custom-scrollbar flex-1 space-y-2 overflow-y-auto p-3">
                @forelse($sessions as $session)
                    <div class="chat-session-item cursor-pointer rounded-xl border p-3 transition hover:border-emerald-200 hover:bg-emerald-50/50 dark:hover:border-emerald-900/40 dark:hover:bg-emerald-950/20 {{ $activeSession && $activeSession->id == $session->id ? 'border-emerald-500 bg-emerald-50/80 ring-1 ring-emerald-500/30 dark:border-emerald-600 dark:bg-emerald-950/30' : 'border-transparent bg-gray-50/50 dark:bg-slate-900/40' }}"
                         data-session-id="{{ $session->id }}"
                         onclick="window.location.href='{{ route('diretoria.chat.realtime', ['session' => $session->id]) }}'">
                        <div class="flex items-center gap-3">
                            <div class="relative shrink-0">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-600 text-sm font-bold text-white shadow-sm">
                                    {{ strtoupper(substr($session->visitor_name ?? 'V', 0, 1)) }}
                                </div>
                                <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-white dark:border-slate-900 {{ $session->status === 'active' ? 'bg-emerald-500' : ($session->status === 'waiting' ? 'bg-amber-400' : 'bg-gray-400') }}"></span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $session->visitor_name ?? 'Visitante' }}</h4>
                                    <span class="shrink-0 text-[10px] font-semibold text-gray-400">{{ $session->last_activity_at?->diffForHumans(null, true) ?? $session->created_at->diffForHumans(null, true) }}</span>
                                </div>
                                <p class="mt-0.5 truncate text-xs text-gray-500 dark:text-gray-400">
                                    @if($session->lastMessage)
                                        @if($session->lastMessage->sender_type === 'user')<span class="font-semibold text-gray-600 dark:text-gray-300">Você: </span>@endif
                                        {{ \Illuminate\Support\Str::limit($session->lastMessage->message, 42) }}
                                    @else
                                        <span class="italic text-gray-400">Nova conversa</span>
                                    @endif
                                </p>
                                @if($session->unread_count_user > 0)
                                    <span class="unread-badge mt-1 inline-flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ $session->unread_count_user }}</span>
                                @else
                                    <span class="unread-badge mt-1 hidden h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">0</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                        <x-icon name="message-slash" class="mx-auto mb-3 h-10 w-10 text-gray-300 dark:text-gray-600" />
                        Nenhuma conversa na lista.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="flex min-h-0 flex-1 flex-col bg-white dark:bg-slate-800">
            @if($activeSession)
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-lg font-bold text-white shadow-md shadow-emerald-600/25">
                            {{ strtoupper(substr($activeSession->visitor_name ?? 'V', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h2 class="truncate text-lg font-bold text-gray-900 dark:text-white">{{ $activeSession->visitor_name ?? 'Visitante' }}</h2>
                            <div class="mt-0.5 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="h-2 w-2 rounded-full {{ $activeSession->status === 'active' ? 'animate-pulse bg-emerald-500' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                                    {{ $activeSession->status_texto }}
                                </span>
                                @if($activeSession->visitor_cpf)
                                    <span class="hidden sm:inline">·</span>
                                    <span class="hidden sm:inline">CPF {{ \Modules\Chat\App\Helpers\CpfHelper::format($activeSession->visitor_cpf) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('diretoria.chat.show', $activeSession->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 text-gray-500 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-600 dark:hover:bg-emerald-950/40" title="Ficha da sessão">
                            <x-icon name="circle-info" class="h-5 w-5" style="duotone" />
                        </a>
                        @if($activeSession->status !== 'closed')
                            <button type="button" id="btn-close-session" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 text-rose-500 transition hover:border-rose-200 hover:bg-rose-50 dark:border-slate-600 dark:hover:bg-rose-950/30" title="Encerrar">
                                <x-icon name="xmark" class="h-5 w-5" />
                            </button>
                        @endif
                    </div>
                </div>

                <div id="chat-messages" class="custom-scrollbar flex-1 space-y-4 overflow-y-auto bg-gray-50/80 p-5 dark:bg-slate-900/40" data-session-id="{{ $activeSession->id }}">
                    @foreach($activeSession->messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="flex justify-center" data-message-id="{{ $message->id }}">
                                <div class="rounded-full border border-gray-200 bg-white px-4 py-1.5 text-center text-[10px] font-bold uppercase tracking-wide text-gray-500 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-gray-400">
                                    {{ $message->message }}
                                    <span class="ml-2 opacity-60">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex {{ $message->sender_type === 'user' ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                                <div class="max-w-[85%] sm:max-w-[75%]">
                                    <div class="mb-1 flex items-center gap-2 {{ $message->sender_type === 'user' ? 'justify-end' : '' }}">
                                        @if($message->sender_type !== 'user')
                                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-600 text-[10px] font-bold text-white">{{ strtoupper(substr($message->sender_name, 0, 1)) }}</span>
                                        @endif
                                        <span class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $message->sender_name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $message->created_at->format('H:i') }}</span>
                                        @if($message->sender_type === 'user')
                                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-gray-900 text-[10px] font-bold text-white dark:bg-emerald-600">{{ strtoupper(substr($message->sender_name, 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="relative rounded-2xl border px-4 py-3 text-sm leading-relaxed shadow-sm {{ $message->sender_type === 'user' ? 'rounded-tr-sm border-emerald-700 bg-gray-900 text-white dark:border-emerald-600 dark:bg-emerald-700' : 'rounded-tl-sm border-gray-200 bg-white text-gray-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white' }}">
                                        <p class="whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                        @if($message->sender_type === 'user')
                                            <div class="absolute -bottom-1 -right-1 text-emerald-300 {{ $message->is_read ? 'text-emerald-200' : 'opacity-50' }}">
                                                <x-icon name="check-double" class="h-3 w-3" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <div id="typing-indicator" class="hidden animate-pulse">
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex gap-1 rounded-lg bg-white px-2 py-1 dark:bg-slate-800">
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:0s"></span>
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:0.15s"></span>
                                <span class="h-1.5 w-1.5 animate-bounce rounded-full bg-gray-400" style="animation-delay:0.3s"></span>
                            </span>
                            A digitar…
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-100 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                    @if($activeSession->status !== 'closed')
                        <form id="message-form" class="relative">
                            @csrf
                            <textarea id="message-input" autocomplete="off" rows="1" placeholder="Mensagem… (Enter envia, Shift+Enter nova linha)" class="w-full min-h-[3rem] resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-28 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400"></textarea>
                            <button type="submit" id="send-button" class="absolute bottom-2.5 right-2 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                <x-icon name="paper-plane" class="h-4 w-4" />
                                Enviar
                            </button>
                        </form>
                    @else
                        <div class="flex flex-col items-center gap-3 py-2 text-center">
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400">Conversa encerrada</p>
                            <button type="button" id="btn-reopen-session" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-700">
                                <x-icon name="rotate-right" class="h-4 w-4" />
                                Reabrir
                            </button>
                        </div>
                    @endif
                </div>
            @else
                <div class="flex flex-1 flex-col items-center justify-center px-6 py-20 text-center">
                    <span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-gray-300 dark:bg-slate-700 dark:text-gray-500">
                        <x-icon name="comments" class="h-8 w-8" style="duotone" />
                    </span>
                    <h3 class="mt-6 text-lg font-bold text-gray-900 dark:text-white">Selecione uma conversa</h3>
                    <p class="mt-2 max-w-sm text-sm text-gray-500 dark:text-gray-400">Escolha um visitante na lista à esquerda para ver o histórico e responder em tempo real.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<audio id="notification-sound" preload="auto">
    <source src="/sounds/chat/notification.mp3" type="audio/mpeg">
</audio>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const POLLING_INTERVAL = 3000;

        const sessionsList = document.getElementById('sessions-list');
        const chatMessages = document.getElementById('chat-messages');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const searchInput = document.getElementById('search-sessions');
        const typingIndicator = document.getElementById('typing-indicator');
        const notificationSound = document.getElementById('notification-sound');
        const btnCloseSession = document.getElementById('btn-close-session');
        const btnReopenSession = document.getElementById('btn-reopen-session');

        let currentSessionId = chatMessages?.dataset.sessionId || null;
        let lastMessageId = 0;
        let pollingInterval = null;
        let sessionsPollingInterval = null;

        function init() {
            if (currentSessionId) {
                updateLastMessageId();
                scrollToBottom();
                startPolling();

                if (messageInput) {
                    messageInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            handleSendMessage(e);
                        }
                    });

                    messageInput.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = this.scrollHeight + 'px';
                    });
                }
            }

            if (messageForm) messageForm.addEventListener('submit', handleSendMessage);
            if (searchInput) searchInput.addEventListener('input', debounce(filterSessions, 300));
            if (btnCloseSession) btnCloseSession.addEventListener('click', handleCloseSession);
            if (btnReopenSession) btnReopenSession.addEventListener('click', handleReopenSession);

            updateSessionsList();
            startSessionsPolling();
        }

        async function handleSendMessage(e) {
            e.preventDefault();
            if (!messageInput || !currentSessionId) return;

            const message = messageInput.value.trim();
            if (!message) return;

            messageInput.disabled = true;
            const sendButton = document.getElementById('send-button');
            if (sendButton) sendButton.disabled = true;

            const originalValue = messageInput.value;
            messageInput.value = '';
            messageInput.style.height = 'auto';

            try {
                const response = await fetch(`{{ url('diretoria/chat') }}/${currentSessionId}/api/message`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                    },
                    body: JSON.stringify({ message }),
                });

                const data = await response.json();
                if (data.success) {
                    appendMessage(data.message);
                    scrollToBottom();
                } else {
                    messageInput.value = originalValue;
                    showToast(data.message || 'Erro ao enviar mensagem', 'error');
                }
            } catch (error) {
                console.error('[Chat] Erro ao enviar:', error);
                messageInput.value = originalValue;
                showToast('Erro ao enviar mensagem. Tente novamente.', 'error');
            } finally {
                messageInput.disabled = false;
                if (sendButton) sendButton.disabled = false;
                messageInput.focus();
            }
        }

        async function loadNewMessages() {
            if (!currentSessionId) return;
            try {
                const response = await fetch(`{{ url('diretoria/chat') }}/${currentSessionId}/api/messages?last_id=${lastMessageId}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                });
                const data = await response.json();
                if (data.success && data.messages && data.messages.length > 0) {
                    let hasNewFromVisitor = false;
                    data.messages.forEach(msg => {
                        if (msg.id > lastMessageId) {
                            appendMessage(msg);
                            lastMessageId = msg.id;
                            if (msg.sender_type === 'visitor') hasNewFromVisitor = true;
                        }
                    });
                    if (hasNewFromVisitor) {
                        playNotificationSound();
                        scrollToBottom();
                    }
                }
            } catch (error) {
                console.error('[Chat] Polling error:', error);
            }
        }

        function appendMessage(msg) {
            if (!chatMessages) return;
            if (chatMessages.querySelector(`[data-message-id="${msg.id}"]`)) return;

            const isSent = msg.sender_type === 'user';
            const isSystem = msg.sender_type === 'system';
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${isSent ? 'justify-end' : (isSystem ? 'justify-center' : 'justify-start')}`;
            wrapper.setAttribute('data-message-id', msg.id);

            const time = new Date(msg.created_at).toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            const senderName = msg.sender_name || (isSent ? 'Você' : 'Visitante');

            if (isSystem) {
                wrapper.innerHTML = `
                    <div class="rounded-full border border-gray-200 bg-white px-4 py-1.5 text-center text-[10px] font-bold uppercase tracking-wide text-gray-500 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-gray-400">
                        ${escapeHtml(msg.message)}
                        <span class="ml-2 opacity-60">${time}</span>
                    </div>
                `;
            } else {
                wrapper.innerHTML = `
                    <div class="max-w-[85%] sm:max-w-[75%]">
                        <div class="mb-1 flex items-center gap-2 ${isSent ? 'justify-end' : ''}">
                            ${!isSent ? `
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-600 text-[10px] font-bold text-white">${escapeHtml(senderName.substring(0,1).toUpperCase())}</span>
                            ` : ''}
                            <span class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">${escapeHtml(senderName)}</span>
                            <span class="text-[10px] text-gray-400">${time}</span>
                            ${isSent ? `
                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-gray-900 text-[10px] font-bold text-white dark:bg-emerald-600">${escapeHtml(senderName.substring(0,1).toUpperCase())}</span>
                            ` : ''}
                        </div>
                        <div class="relative rounded-2xl border px-4 py-3 text-sm leading-relaxed shadow-sm ${isSent ? 'rounded-tr-sm border-emerald-700 bg-gray-900 text-white dark:border-emerald-600 dark:bg-emerald-700' : 'rounded-tl-sm border-gray-200 bg-white text-gray-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white'}">
                            <p class="whitespace-pre-wrap break-words">${escapeHtml(msg.message)}</p>
                            ${isSent ? `
                            <div class="absolute -bottom-1 -right-1 text-emerald-300 ${msg.is_read ? 'text-emerald-200' : 'opacity-50'}">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                `;
            }

            if (typingIndicator) chatMessages.insertBefore(wrapper, typingIndicator);
            else chatMessages.appendChild(wrapper);
            scrollToBottom();
        }

        async function updateSessionsList() {
            try {
                const response = await fetch(`{{ route('diretoria.chat.api.sessions') }}`, {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const data = await response.json();
                if (data.success && data.sessions) {
                    const waiting = data.sessions.filter(s => s.status === 'waiting').length;
                    const active = data.sessions.filter(s => s.status === 'active').length;
                    const elW = document.getElementById('stats-waiting');
                    const elA = document.getElementById('stats-active');
                    if (elW) elW.textContent = waiting;
                    if (elA) elA.textContent = active;

                    data.sessions.forEach(session => {
                        const item = sessionsList?.querySelector(`[data-session-id="${session.id}"]`);
                        if (item) {
                            const badge = item.querySelector('.unread-badge');
                            if (session.unread_count_user > 0) {
                                if (badge) {
                                    badge.textContent = session.unread_count_user;
                                    badge.classList.remove('hidden');
                                }
                            } else if (badge) {
                                badge.classList.add('hidden');
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('[Chat] Erro ao atualizar sessões:', error);
            }
        }

        function filterSessions() {
            const query = searchInput?.value.toLowerCase() || '';
            const items = sessionsList?.querySelectorAll('.chat-session-item') || [];
            items.forEach(item => {
                const name = item.querySelector('h4')?.textContent.toLowerCase() || '';
                const preview = item.querySelector('p')?.textContent.toLowerCase() || '';
                if (name.includes(query) || preview.includes(query)) item.style.display = '';
                else item.style.display = 'none';
            });
        }

        async function handleCloseSession() {
            if (!currentSessionId || !confirm('Deseja encerrar esta conversa?')) return;
            try {
                const response = await fetch(`{{ url('diretoria/chat') }}/${currentSessionId}/close`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const data = await response.json();
                if (data.success) {
                    showToast('Conversa encerrada com sucesso!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else showToast(data.message || 'Erro ao encerrar conversa', 'error');
            } catch (error) { showToast('Erro ao encerrar conversa', 'error'); }
        }

        async function handleReopenSession() {
            if (!currentSessionId) return;
            try {
                const response = await fetch(`{{ url('diretoria/chat') }}/${currentSessionId}/reopen`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
                });
                const data = await response.json();
                if (data.success) {
                    showToast('Conversa reaberta com sucesso!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else showToast(data.message || 'Erro ao reabrir conversa', 'error');
            } catch (error) { showToast('Erro ao reabrir conversa', 'error'); }
        }

        function startPolling() { pollingInterval = setInterval(loadNewMessages, POLLING_INTERVAL); }
        function stopPolling() { if (pollingInterval) clearInterval(pollingInterval); pollingInterval = null; }
        function startSessionsPolling() { sessionsPollingInterval = setInterval(updateSessionsList, 5000); }
        function scrollToBottom() { if (chatMessages) chatMessages.scrollTop = chatMessages.scrollHeight; }
        function updateLastMessageId() {
            if (chatMessages) {
                const messages = chatMessages.querySelectorAll('[data-message-id]');
                if (messages.length > 0) lastMessageId = parseInt(messages[messages.length - 1].dataset.messageId) || 0;
            }
        }

        function playNotificationSound() {
            if (notificationSound && !document.hidden) {
                notificationSound.currentTime = 0;
                notificationSound.play().catch(() => {});
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), wait);
            };
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 z-50 rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-emerald-600' : type === 'error' ? 'bg-rose-600' : 'bg-gray-900'
            }`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopPolling();
            } else if (currentSessionId) {
                loadNewMessages();
                startPolling();
            }
        });

        window.addEventListener('beforeunload', () => {
            stopPolling();
            if (sessionsPollingInterval) {
                clearInterval(sessionsPollingInterval);
            }
        });

        init();
    });
})();
</script>
@endpush
