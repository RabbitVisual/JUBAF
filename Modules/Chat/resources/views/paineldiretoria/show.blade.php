@extends('paineldiretoria::components.layouts.app')

@section('title', 'Chat — ' . ($session->visitor_name ?? 'Visitante'))

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $labelClass = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $statusConfig = [
        'waiting' => ['cls' => 'bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-100', 'label' => 'Aguardando'],
        'active' => ['cls' => 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100', 'label' => 'Em atendimento'],
        'closed' => ['cls' => 'bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-200', 'label' => 'Encerrado'],
    ];
    $s = $statusConfig[$session->status] ?? ['cls' => 'bg-gray-100 text-gray-700', 'label' => $session->status];
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('chat::paineldiretoria.partials.subnav', ['active' => 'sessions'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-start">
            <a href="{{ route('diretoria.chat.index') }}" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 text-gray-600 transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700 dark:border-slate-600 dark:text-gray-300 dark:hover:border-emerald-700 dark:hover:bg-emerald-950/40" title="Voltar">
                <x-icon name="arrow-left" class="h-5 w-5" />
            </a>
            <div class="min-w-0">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Sessão #{{ $session->id }}</p>
                <h1 class="mt-1 flex flex-wrap items-center gap-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                    {{ $session->visitor_name ?? 'Visitante' }}
                    <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $s['cls'] }}">{{ $s['label'] }}</span>
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Iniciada em {{ $session->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @if($session->status !== 'closed')
                <form action="{{ route('diretoria.chat.close', $session->id) }}" method="POST" onsubmit="return confirm('Encerrar esta sessão?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-rose-700">
                        <x-icon name="xmark" class="h-4 w-4" />
                        Encerrar
                    </button>
                </form>
            @else
                <form action="{{ route('diretoria.chat.reopen', $session->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                        <x-icon name="rotate-right" class="h-4 w-4" />
                        Reabrir
                    </button>
                </form>
            @endif
            <a href="{{ route('diretoria.chat.realtime', ['session' => $session->id]) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                <x-icon name="bolt" class="h-4 w-4" style="duotone" />
                Tempo real
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8">
            <div class="flex h-[min(70vh,640px)] flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <div class="flex items-center gap-2">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                            <x-icon name="comments" class="h-4 w-4" style="duotone" />
                        </span>
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Histórico</h2>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $session->messages->count() }} mensagens</span>
                </div>

                <div id="chat-messages-container" class="custom-scrollbar flex-1 space-y-4 overflow-y-auto bg-gray-50/80 p-5 dark:bg-slate-900/40">
                    @forelse($session->messages as $message)
                        @if($message->sender_type === 'system')
                            <div class="flex justify-center">
                                <div class="rounded-full border border-gray-200 bg-white px-4 py-1.5 text-center text-[10px] font-bold uppercase tracking-wide text-gray-500 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-gray-400">
                                    {{ $message->message }}
                                    <span class="ml-2 opacity-60">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex {{ $message->sender_type === 'user' ? 'justify-end' : 'justify-start' }}">
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
                                    <div class="rounded-2xl border px-4 py-3 text-sm leading-relaxed shadow-sm {{ $message->sender_type === 'user' ? 'rounded-tr-sm border-emerald-700 bg-gray-900 text-white dark:border-emerald-600 dark:bg-emerald-700' : 'rounded-tl-sm border-gray-200 bg-white text-gray-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white' }}">
                                        <p class="whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="flex h-full flex-col items-center justify-center py-16 text-center">
                            <x-icon name="message-slash" class="mb-4 h-12 w-12 text-gray-300 dark:text-gray-600" />
                            <p class="font-semibold text-gray-900 dark:text-white">Sem mensagens</p>
                            <p class="mt-1 max-w-xs text-sm text-gray-500 dark:text-gray-400">Ainda não há interação nesta sessão.</p>
                        </div>
                    @endforelse
                </div>

                @if($session->status !== 'closed')
                    <div class="border-t border-gray-100 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
                        <form id="message-form" class="flex gap-2">
                            @csrf
                            <input type="text" id="message-input" autocomplete="off" placeholder="Escreva a resposta…" class="min-w-0 flex-1 {{ $inputClass }}">
                            <button type="submit" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700">
                                <x-icon name="paper-plane" class="h-4 w-4" />
                                Enviar
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6 lg:col-span-4">
            <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white">Visitante</h3>
                <div class="mt-4 flex flex-col items-center text-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-emerald-600 text-2xl font-bold text-white shadow-md shadow-emerald-600/25">
                        {{ strtoupper(substr($session->visitor_name ?? 'V', 0, 1)) }}
                    </div>
                    <p class="mt-3 text-lg font-bold text-gray-900 dark:text-white">{{ $session->visitor_name ?? 'Visitante' }}</p>
                    <p class="mt-1 inline-flex items-center gap-2 rounded-full border border-gray-200 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:border-slate-600 dark:text-gray-400">
                        <span class="h-2 w-2 rounded-full {{ $session->status === 'active' ? 'animate-pulse bg-emerald-500' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                        {{ $session->status_texto }}
                    </p>
                </div>
                <dl class="mt-6 space-y-4 border-t border-gray-100 pt-5 dark:border-slate-700">
                    @if($session->visitor_cpf)
                        <div>
                            <dt class="{{ $labelClass }}">CPF</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ \Modules\Chat\App\Helpers\CpfHelper::format($session->visitor_cpf) }}</dd>
                        </div>
                    @endif
                    @if($session->visitor_email)
                        <div>
                            <dt class="{{ $labelClass }}">E-mail</dt>
                            <dd class="break-all text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $session->visitor_email }}</dd>
                        </div>
                    @endif
                    @if($session->visitor_phone)
                        <div>
                            <dt class="{{ $labelClass }}">Telefone</dt>
                            <dd class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $session->visitor_phone }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="{{ $labelClass }}">Duração</dt>
                        <dd class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $session->created_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <h3 class="flex items-center gap-2 text-sm font-bold text-gray-900 dark:text-white">
                        <x-icon name="user-plus" class="h-4 w-4 text-emerald-600" style="duotone" />
                        Atendente
                    </h3>
                </div>
                <div class="p-5">
                    @if($session->assignedTo)
                        <div class="mb-4 flex items-center gap-3 rounded-xl border border-gray-100 bg-gray-50 p-3 dark:border-slate-600 dark:bg-slate-900/50">
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-200 text-sm font-bold text-gray-700 dark:bg-slate-700 dark:text-gray-200">{{ strtoupper(substr($session->assignedTo->name, 0, 1)) }}</span>
                            <div class="min-w-0">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Atribuído</p>
                                <p class="truncate font-semibold text-gray-900 dark:text-white">{{ $session->assignedTo->name }}</p>
                            </div>
                        </div>
                    @else
                        <p class="mb-4 rounded-xl border border-dashed border-gray-200 py-3 text-center text-xs font-semibold text-gray-500 dark:border-slate-600 dark:text-gray-400">Sem atendente atribuído</p>
                    @endif

                    @if($session->status !== 'closed')
                        <form action="{{ route('diretoria.chat.assign', $session->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="{{ $labelClass }}">Selecionar</label>
                                <select name="assigned_to" required class="{{ $inputClass }}">
                                    <option value="">—</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" @selected($session->assigned_to == $agent->id)>{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-gray-900 py-2.5 text-sm font-bold text-white transition hover:bg-gray-800 dark:bg-slate-700 dark:hover:bg-slate-600">
                                {{ $session->assignedTo ? 'Transferir' : 'Assumir sessão' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($session->status !== 'closed')
@push('scripts')
<script>
(function() {
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const sessionId = {{ $session->id }};
    let lastMessageId = {{ $session->messages->last()?->id ?? 0 }};
    let pollInterval = null;

    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const messagesContainer = document.getElementById('chat-messages-container');

    if (messageForm) {
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();
            if (!message) return;

            messageInput.disabled = true;
            const originalValue = messageInput.value;
            messageInput.value = '';

            try {
                const response = await fetch(`{{ url('diretoria/chat') }}/${sessionId}/api/message`, {
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
                    alert(data.message || 'Erro ao enviar mensagem');
                }
            } catch (error) {
                console.error('Erro:', error);
                messageInput.value = originalValue;
                alert('Erro ao enviar mensagem');
            } finally {
                messageInput.disabled = false;
                messageInput.focus();
            }
        });
    }

    async function checkNewMessages() {
        try {
            const response = await fetch(`{{ url('diretoria/chat') }}/${sessionId}/api/messages?last_id=${lastMessageId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
            });

            const data = await response.json();

            if (data.success && data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (msg.id > lastMessageId) {
                        appendMessage(msg);
                        lastMessageId = msg.id;
                    }
                });
                scrollToBottom();
            }
        } catch (error) {
            console.error('Erro ao verificar mensagens:', error);
        }
    }

    function appendMessage(msg) {
        if (!messagesContainer) return;
        if (messagesContainer.querySelector(`[data-message-id="${msg.id}"]`)) return;

        const isSent = msg.sender_type === 'user';
        const isSystem = msg.sender_type === 'system';

        const wrapper = document.createElement('div');
        wrapper.className = `flex ${isSent ? 'justify-end' : (isSystem ? 'justify-center' : 'justify-start')}`;
        wrapper.setAttribute('data-message-id', msg.id);

        const time = new Date(msg.created_at).toLocaleString('pt-BR', { hour: '2-digit', minute: '2-digit' });
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
                        <span class="text-[10px] font-bold uppercase tracking-wide text-gray-500">${escapeHtml(senderName)}</span>
                        <span class="text-[10px] text-gray-400">${time}</span>
                        ${isSent ? `
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-gray-900 text-[10px] font-bold text-white dark:bg-emerald-600">${escapeHtml(senderName.substring(0,1).toUpperCase())}</span>
                        ` : ''}
                    </div>
                    <div class="rounded-2xl border px-4 py-3 text-sm leading-relaxed shadow-sm ${isSent ? 'rounded-tr-sm border-emerald-700 bg-gray-900 text-white dark:border-emerald-600 dark:bg-emerald-700' : 'rounded-tl-sm border-gray-200 bg-white text-gray-900 dark:border-slate-600 dark:bg-slate-800 dark:text-white'}">
                        <p class="whitespace-pre-wrap break-words">${escapeHtml(msg.message)}</p>
                    </div>
                </div>
            `;
        }

        messagesContainer.appendChild(wrapper);
    }

    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    scrollToBottom();
    pollInterval = setInterval(checkNewMessages, 3000);

    window.addEventListener('beforeunload', () => {
        if (pollInterval) clearInterval(pollInterval);
    });
})();
</script>
@endpush
@endif
@endsection
