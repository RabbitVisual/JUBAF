@extends('paineldiretoria::components.layouts.app')

@section('title', 'Chat interno — Diretoria')

@push('scripts')
<script>
    window.ERP_CHAT = {
        conversationsUrl: @json($conversationsJsonUrl),
        usersUrl: @json($usersJsonUrl),
        openUrl: @json($openUrl),
        messagesUrlTemplate: @json($messagesUrlTemplate),
        sendUrlTemplate: @json($sendUrlTemplate),
        echoReverb: @json($echoReverb),
        broadcastDriver: @json($broadcastDriver),
        authUserId: @json($authUserId),
    };
</script>
@vite(['resources/js/erp-chat.js'])
@endpush

@section('content')
<div class="space-y-4">
    <div id="erp-chat-root"
         class="flex min-h-[calc(100vh-12rem)] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900 lg:flex-row">
        <aside class="w-full shrink-0 border-b border-slate-200 bg-slate-50/80 dark:border-slate-700 dark:bg-slate-900/80 lg:w-80 lg:border-b-0 lg:border-r">
            <div class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                <h2 class="text-sm font-bold uppercase tracking-wide text-slate-600 dark:text-slate-300">Conversas</h2>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Comunicação institucional monitorizada.</p>
            </div>
            <div id="erp-chat-new" class="border-b border-slate-200 p-3 dark:border-slate-700">
                <label for="erp-peer-select" class="mb-1 block text-xs font-semibold text-slate-600 dark:text-slate-400">Nova conversa</label>
                <div class="flex gap-2">
                    <select id="erp-peer-select" class="block w-full rounded-lg border border-slate-300 bg-white text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white"></select>
                    <button type="button" id="erp-open-btn" class="shrink-0 rounded-lg bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-700">Abrir</button>
                </div>
            </div>
            <div id="erp-chat-conversation-list" class="max-h-72 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800 lg:max-h-[calc(100vh-16rem)]"></div>
        </aside>
        <section class="flex min-h-[320px] flex-1 flex-col">
            <header id="erp-chat-header" class="border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                <p class="text-sm font-semibold text-slate-900 dark:text-white" id="erp-chat-peer-title">Selecione uma conversa</p>
                <p class="text-xs text-slate-500 dark:text-slate-400" id="erp-chat-sub"></p>
            </header>
            <div id="erp-chat-thread" class="flex-1 space-y-3 overflow-y-auto bg-slate-50/50 p-4 dark:bg-slate-950/40"></div>
            <form id="erp-chat-form" class="border-t border-slate-200 p-3 dark:border-slate-700">
                @csrf
                <div class="flex gap-2">
                    <input type="text" id="erp-chat-input" name="body" maxlength="8000" placeholder="Escreva uma mensagem…" disabled
                           class="flex-1 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-800 dark:text-white" />
                    <button type="submit" id="erp-chat-submit" disabled class="rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-50">Enviar</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
