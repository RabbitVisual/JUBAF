import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

const cfg = window.ERP_CHAT;
if (!cfg) {
    // eslint-disable-next-line no-console
    console.warn('ERP_CHAT não configurado.');
}

function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function messagesUrl(uuid) {
    return cfg.messagesUrlTemplate.replace('__UUID__', uuid);
}

function sendUrl(uuid) {
    return cfg.sendUrlTemplate.replace('__UUID__', uuid);
}

let currentUuid = null;
let echoSubscribedId = null;

function renderMessages(messages) {
    const el = document.getElementById('erp-chat-thread');
    if (!el) {
        return;
    }
    const myId = Number(cfg.authUserId);
    el.innerHTML = '';
    for (const m of messages) {
        const mine = m.sender && Number(m.sender.id) === myId;
        const wrap = document.createElement('div');
        wrap.className = mine ? 'flex justify-end' : 'flex justify-start';
        const bubble = document.createElement('div');
        bubble.className = mine
            ? 'max-w-[85%] rounded-2xl rounded-br-sm bg-indigo-600 px-3 py-2 text-sm text-white shadow-sm dark:bg-indigo-500'
            : 'max-w-[85%] rounded-2xl rounded-bl-sm border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100';
        bubble.textContent = m.body;
        const meta = document.createElement('div');
        meta.className = mine ? 'mt-1 w-full text-right text-[10px] text-slate-400' : 'mt-1 w-full text-[10px] text-slate-400';
        meta.textContent = m.sender ? m.sender.name : '';
        const col = document.createElement('div');
        col.className = mine ? 'flex max-w-[85%] flex-col items-end' : 'flex max-w-[85%] flex-col items-start';
        col.appendChild(bubble);
        col.appendChild(meta);
        wrap.appendChild(col);
        el.appendChild(wrap);
    }
    el.scrollTop = el.scrollHeight;
}

function subscribeEcho(conversationNumericId) {
    if (!cfg.echoReverb?.key || cfg.broadcastDriver === 'log' || !conversationNumericId) {
        return;
    }
    window.Pusher = Pusher;
    if (!window.Echo) {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: cfg.echoReverb.key,
            wsHost: cfg.echoReverb.host,
            wsPort: cfg.echoReverb.port,
            wssPort: cfg.echoReverb.port,
            forceTLS: cfg.echoReverb.scheme === 'https',
            enabledTransports: ['ws', 'wss'],
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrf(),
                },
            },
        });
    }
    if (echoSubscribedId === conversationNumericId) {
        return;
    }
    if (echoSubscribedId !== null && window.Echo) {
        try {
            window.Echo.leave(`conversation.${echoSubscribedId}`);
        } catch {
            // ignore
        }
    }
    echoSubscribedId = conversationNumericId;
    window.Echo.private(`conversation.${conversationNumericId}`).listen('.message.created', async () => {
        if (currentUuid) {
            await loadMessages(currentUuid);
        }
    });
}

async function loadMessages(uuid) {
    const res = await axios.get(messagesUrl(uuid), { headers: { Accept: 'application/json' } });
    renderMessages(res.data.messages || []);
    subscribeEcho(res.data.conversation_id);
}

async function openConversation(uuid, title) {
    currentUuid = uuid;
    document.getElementById('erp-chat-peer-title').textContent = title || 'Conversa';
    document.getElementById('erp-chat-sub').textContent = '';
    document.getElementById('erp-chat-input').disabled = false;
    document.getElementById('erp-chat-submit').disabled = false;
    await loadMessages(uuid);
}

function renderConversationList(rows) {
    const el = document.getElementById('erp-chat-conversation-list');
    if (!el) {
        return;
    }
    el.innerHTML = '';
    for (const c of rows) {
        const a = document.createElement('button');
        a.type = 'button';
        a.className = 'w-full px-4 py-3 text-left text-sm hover:bg-white dark:hover:bg-slate-800';
        a.innerHTML = `<div class="font-semibold text-slate-800 dark:text-slate-100">${c.peer?.name ?? 'Conversa'}</div>
            <div class="text-xs text-slate-500">${c.last_message ?? ''}</div>`;
        a.addEventListener('click', () => openConversation(c.uuid, c.peer?.name));
        el.appendChild(a);
    }
}

async function refreshList() {
    const res = await axios.get(cfg.conversationsUrl, { headers: { Accept: 'application/json' } });
    renderConversationList(res.data.conversations || []);
}

async function loadUsers() {
    const res = await axios.get(cfg.usersUrl, { headers: { Accept: 'application/json' } });
    const sel = document.getElementById('erp-peer-select');
    if (!sel) {
        return;
    }
    sel.innerHTML = '<option value="">— Escolher contacto —</option>';
    for (const u of res.data.users || []) {
        const o = document.createElement('option');
        o.value = u.id;
        o.textContent = u.name;
        sel.appendChild(o);
    }
}

document.getElementById('erp-open-btn')?.addEventListener('click', async () => {
    const sel = document.getElementById('erp-peer-select');
    const id = sel?.value;
    if (!id) {
        return;
    }
    const res = await axios.post(
        cfg.openUrl,
        { user_id: id },
        { headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' } },
    );
    const uuid = res.data?.conversation?.uuid;
    if (uuid) {
        await refreshList();
        await openConversation(uuid, sel.options[sel.selectedIndex]?.text);
    }
});

document.getElementById('erp-chat-form')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!currentUuid) {
        return;
    }
    const input = document.getElementById('erp-chat-input');
    const body = input?.value?.trim();
    if (!body) {
        return;
    }
    await axios.post(
        sendUrl(currentUuid),
        { body },
        { headers: { 'X-CSRF-TOKEN': csrf(), Accept: 'application/json' } },
    );
    input.value = '';
    await loadMessages(currentUuid);
});

async function boot() {
    if (!cfg) {
        return;
    }
    await loadUsers();
    await refreshList();
}

boot();
