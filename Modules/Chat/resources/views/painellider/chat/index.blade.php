@extends('painellider::components.layouts.app')

@section('title', 'Chat')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-emerald-600 dark:text-emerald-400">Comunicação com a JUBAF</span>
@endsection

@section('content')
<div class="h-[calc(100vh-14rem)] flex flex-col space-y-8 animate-fade-in">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-200 dark:border-slate-800">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 bg-gradient-to-br from-emerald-600 to-teal-700 rounded-2xl flex items-center justify-center text-white shadow-2xl transform rotate-3 hover:rotate-0 transition-all">
                <x-icon name="comment-dots" style="duotone" class="w-7 h-7" />
            </div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white tracking-tight leading-tight">Canal do líder</h1>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 leading-relaxed">Articulação com a diretoria, outros líderes e apoio aos jovens — alinhado ao calendário, avisos e secretaria JUBAF.</p>
            </div>
        </div>

        <button type="button" onclick="abrirNovaConversa()" class="h-12 px-6 sm:px-8 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20 active:scale-95 flex items-center gap-2 shrink-0">
            <x-icon name="plus" class="w-4 h-4" />
            Nova conversa
        </button>
    </div>

    <div class="flex-1 flex flex-col lg:flex-row gap-8 min-h-0 overflow-hidden">
        <div class="w-full lg:w-80 flex flex-col rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm max-h-64 lg:max-h-none">
            <div class="p-6 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                <span class="text-sm font-semibold text-slate-600 dark:text-slate-300">Conversas</span>
                <span id="online-status" class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse" aria-hidden="true"></span>
            </div>

            <div id="lista-conversas" class="flex-1 overflow-y-auto divide-y divide-gray-50 dark:divide-slate-800/50 min-h-[8rem]">
                <div class="p-12 text-center">
                    <x-icon name="spinner" class="w-6 h-6 text-emerald-500 animate-spin mx-auto mb-4" />
                    <p class="text-sm text-slate-500 dark:text-slate-400">Carregando…</p>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm min-h-[20rem]">
            <div id="chat-header" class="p-6 border-b border-gray-100 dark:border-slate-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md flex items-center justify-between z-10 hidden">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-center justify-center text-emerald-600 shadow-inner">
                        <x-icon name="user" style="duotone" class="w-6 h-6" />
                    </div>
                    <div>
                        <p id="chat-header-name" class="text-base font-semibold text-gray-900 dark:text-white">--</p>
                        <p id="chat-header-status" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-0.5">Conversação ativa</p>
                    </div>
                </div>
            </div>

            <div id="chat-mensagens-container" class="flex-1 p-8 overflow-y-auto flex flex-col space-y-6 bg-slate-50/50 dark:bg-slate-950/30">
                <div class="flex-1 flex flex-col items-center justify-center text-center py-20">
                    <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-[2.5rem] flex items-center justify-center text-slate-300 dark:text-slate-700 mb-6 shadow-inner">
                        <x-icon name="comments" style="duotone" class="w-10 h-10" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-600 dark:text-slate-300">Escolha uma conversa</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-xs leading-relaxed">Fale com a diretoria, outro líder ou acompanhe retorno dos jovens.</p>
                </div>
            </div>

            <div id="chat-input-container" class="p-6 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-t border-gray-100 dark:border-slate-800 z-10 hidden">
                <form id="chat-form" class="flex items-center gap-4">
                    <div class="flex-1 relative">
                        <label for="chat-message-input" class="sr-only">Mensagem</label>
                        <input type="text" id="chat-message-input" placeholder="Digite sua mensagem…" class="w-full pl-6 pr-6 py-5 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl text-sm font-medium focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 dark:text-white placeholder:text-slate-400 transition-all">
                    </div>
                    <button type="submit" class="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 transition-all active:scale-95 group shrink-0" aria-label="Enviar">
                        <x-icon name="paper-plane" class="w-6 h-6 group-hover:rotate-12 transition-transform" />
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal-nova-conversa" class="hidden fixed inset-0 z-[100] bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-white/10 overflow-hidden">
        <div class="p-8 border-b border-gray-100 dark:border-slate-800 bg-emerald-500/5 flex items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-emerald-600/20 shrink-0">
                    <x-icon name="message-plus" style="duotone" class="w-7 h-7" />
                </div>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Nova conversa</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Diretoria, outro líder ou jovem da sua rede</p>
                </div>
            </div>
            <button type="button" onclick="fecharModalNovaConversa()" class="w-12 h-12 rounded-2xl bg-gray-50 dark:bg-slate-800 text-slate-400 hover:text-rose-600 transition-colors flex items-center justify-center shrink-0" aria-label="Fechar">
                <x-icon name="xmark" class="w-5 h-5" />
            </button>
        </div>

        <form id="form-nova-conversa" class="p-8 space-y-8">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="usuario-select">Destinatário</label>
                <select id="usuario-select" required class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 dark:text-white transition-all appearance-none cursor-pointer">
                    <option value="">A carregar contactos…</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="mensagem-inicial">Primeira mensagem</label>
                <textarea id="mensagem-inicial" required rows="4" placeholder="Ex.: alinhamento de evento, talentos ou acompanhamento de jovem…" class="w-full p-6 bg-gray-50 dark:bg-slate-950 border border-gray-100 dark:border-slate-800 rounded-2xl text-sm font-medium text-slate-600 dark:text-slate-300 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all"></textarea>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="button" onclick="fecharModalNovaConversa()" class="flex-1 h-14 text-base font-semibold text-slate-500 dark:text-slate-400 hover:text-rose-600 transition-colors">Cancelar</button>
                <button type="submit" id="btn-criar-conversa" class="flex-[2] h-14 bg-emerald-600 text-white rounded-2xl text-base font-semibold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-600/20 active:scale-95">Iniciar</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@php
    $messagesUrlTpl = route('lideres.chat.messages', ['sessionId' => '__SID__']);
    $sendUrlTpl = route('lideres.chat.send', ['sessionId' => '__SID__']);
@endphp
<script>
    let sessaoAtual = null;
    const currentUserId = {{ (int) auth()->id() }};
    const messagesUrlTpl = @json($messagesUrlTpl);
    const sendUrlTpl = @json($sendUrlTpl);

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function peerName(s) {
        if (s.user_id === currentUserId) {
            return s.assigned_to?.name || 'Equipa JUBAF';
        }
        return s.user?.name || 'Participante';
    }

    async function carregarConversas() {
        try {
            const res = await fetch('{{ route("lideres.chat.index") }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            if (data.success) {
                const container = document.getElementById('lista-conversas');
                const sessoes = data.sessoes.data || [];
                if (sessoes.length === 0) {
                    container.innerHTML = '<div class="p-12 text-center text-sm text-slate-500 dark:text-slate-400 font-medium">Nenhuma conversa ainda. Inicie um diálogo com a diretoria, outro líder ou um jovem.</div>';
                    return;
                }

                container.innerHTML = sessoes.map(s => {
                    const nome = peerName(s);
                    const msg = s.last_message ? s.last_message.message : 'Sem mensagens';
                    const active = s.session_id === sessaoAtual;
                    const unread = (s.unread_for_me ?? 0) > 0;

                    return `
                        <div onclick="abrirConversa('${s.session_id}')" class="p-6 transition-all cursor-pointer ${active ? 'bg-emerald-500/10 border-l-4 border-emerald-500' : 'hover:bg-gray-50 dark:hover:bg-slate-800/30'}">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white truncate flex-1 ${unread ? 'text-emerald-600 dark:text-emerald-400' : ''}">${escapeHtml(nome)}</span>
                                ${unread ? `<span class="min-w-[1.25rem] h-5 px-1 rounded-full bg-emerald-600 text-xs font-bold text-white flex items-center justify-center">${s.unread_for_me}</span>` : ''}
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">${escapeHtml(msg)}</p>
                        </div>
                    `;
                }).join('');
            }
        } catch (e) { console.error(e); }
    }

    async function abrirConversa(sid) {
        sessaoAtual = sid;
        document.getElementById('chat-header').classList.remove('hidden');
        document.getElementById('chat-input-container').classList.remove('hidden');

        try {
            const url = messagesUrlTpl.replace('__SID__', encodeURIComponent(sid));
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (data.success) {
                const peer = data.session?.peer_name || data.messages[0]?.sender?.name || 'Conversa';
                document.getElementById('chat-header-name').textContent = peer;

                const container = document.getElementById('chat-mensagens-container');
                if (!data.messages.length) {
                    container.innerHTML = '<p class="text-center text-slate-400 text-sm py-8">Sem mensagens nesta conversa.</p>';
                } else {
                    container.innerHTML = data.messages.map(m => {
                        const isMe = m.sender && m.sender.id === currentUserId;
                        const label = isMe ? 'Você' : (m.sender?.name || 'Participante');
                        return `
                        <div class="flex ${isMe ? 'justify-end' : 'justify-start'} animate-fade-in">
                            <div class="max-w-[80%]">
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1 ${isMe ? 'text-right' : ''}">${escapeHtml(label)}</p>
                                <div class="px-5 py-3 rounded-2xl ${isMe ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-gray-100 dark:border-slate-700 shadow-sm'}">
                                    <p class="text-base font-medium leading-relaxed">${escapeHtml(m.message)}</p>
                                    <p class="text-xs mt-2 opacity-70">${new Date(m.created_at).toLocaleTimeString('pt-BR', {hour:'2-digit', minute:'2-digit'})}</p>
                                </div>
                            </div>
                        </div>`;
                    }).join('');
                }
                container.scrollTop = container.scrollHeight;
                carregarConversas();
            }
        } catch (e) { console.error(e); }
    }

    document.getElementById('chat-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const input = document.getElementById('chat-message-input');
        const msg = input.value.trim();
        if (!msg || !sessaoAtual) return;

        try {
            const url = sendUrlTpl.replace('__SID__', encodeURIComponent(sessaoAtual));
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ message: msg })
            });
            if ((await res.json()).success) {
                input.value = '';
                abrirConversa(sessaoAtual);
            }
        } catch (e) { console.error(e); }
    });

    async function abrirNovaConversa() {
        const modal = document.getElementById('modal-nova-conversa');
        modal.classList.remove('hidden');
        const select = document.getElementById('usuario-select');
        select.innerHTML = '<option value="">Carregando…</option>';
        try {
            const res = await fetch('{{ route("lideres.chat.users") }}', { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (data.users) {
                select.innerHTML = '<option value="">Selecione um contato…</option>' +
                    data.users.map(u => `<option value="${u.id}">${escapeHtml(u.name)} (${escapeHtml(u.email)})</option>`).join('');
            }
        } catch (e) { console.error(e); }
    }

    function fecharModalNovaConversa() { document.getElementById('modal-nova-conversa').classList.add('hidden'); }

    document.getElementById('form-nova-conversa').addEventListener('submit', async (e) => {
        e.preventDefault();
        const uid = document.getElementById('usuario-select').value;
        const msg = document.getElementById('mensagem-inicial').value;
        const btn = document.getElementById('btn-criar-conversa');
        btn.disabled = true;
        try {
            const res = await fetch('{{ route("lideres.chat.store") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ assigned_to: uid, message: msg })
            });
            const data = await res.json();
            if (data.success) {
                fecharModalNovaConversa();
                await carregarConversas();
                abrirConversa(data.session.session_id);
            }
        } catch (e) { console.error(e); } finally { btn.disabled = false; }
    });

    document.addEventListener('DOMContentLoaded', () => {
        carregarConversas();
        setInterval(carregarConversas, 10000);
    });
</script>
@endpush
@endsection
