@extends('layouts.app')

@section('title', 'Chat - Sessão #' . $session->id)

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans h-[calc(100vh-8rem)] flex flex-col">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-gray-200 dark:border-slate-700 flex-shrink-0">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl flex items-center justify-center shadow-lg">
                    <x-icon module="chat" class="w-6 h-6 md:w-7 md:h-7 text-white" style="duotone" />
                </div>
                <span>{{ $session->visitor_name ?? 'Visitante' }}</span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border ml-2 {{ $session->status === 'waiting' ? 'bg-amber-50 text-amber-600 border-amber-100' : ($session->status === 'active' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-50 text-slate-600 border-slate-100') }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $session->status === 'waiting' ? 'bg-amber-500 animate-pulse' : ($session->status === 'active' ? 'bg-green-500' : 'bg-slate-400') }}"></span>
                    {{ $session->status_texto }}
                </span>
            </h1>
            <nav aria-label="breadcrumb" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors italic">Admin</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <a href="{{ route('admin.chat.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors italic">Chat</a>
                <x-icon name="chevron-right" class="w-3 h-3 text-slate-400 font-sans" style="duotone" />
                <span class="text-gray-900 dark:text-white font-medium uppercase tracking-widest text-[10px]">Sessão #{{ $session->id }}</span>
            </nav>
        </div>
        <div class="flex gap-2">
            @if($session->status !== 'closed')
            <form action="{{ route('admin.chat.close', $session->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja encerrar este atendimento?');">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-800 transition-all shadow-lg shadow-red-500/20 uppercase tracking-widest text-[10px]">
                    <x-icon name="xmark" class="w-5 h-5" style="duotone" />
                    Encerrar
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-4 gap-6 overflow-hidden">
        <!-- Chat Area -->
        <div class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 flex flex-col overflow-hidden">
            <!-- Messages -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50/50 dark:bg-slate-900/50 scroll-smooth">
                @foreach($session->messages as $message)
                <div class="flex {{ $message->user_id ? 'justify-end' : 'justify-start' }} animate-fade-in-up">
                    <div class="max-w-[80%] {{ $message->user_id ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-700 text-slate-800 dark:text-gray-100 border border-gray-200 dark:border-slate-600 rounded-tl-none' }} rounded-2xl px-5 py-3 shadow-sm relative group">
                        @if(!$message->user_id)
                        <div class="absolute -top-5 left-0 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">{{ $session->visitor_name }}</div>
                        @else
                        <div class="absolute -top-5 right-0 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 text-right">{{ $message->user->name }}</div>
                        @endif

                        <p class="text-sm leading-relaxed whitespace-pre-wrap font-sans">{{ $message->message }}</p>

                        <div class="flex items-center gap-1 mt-1 {{ $message->user_id ? 'justify-end text-blue-100' : 'justify-start text-slate-400' }}">
                            <span class="text-[9px] font-bold uppercase tracking-widest">{{ $message->created_at->format('H:i') }}</span>
                            @if($message->user_id)
                            <x-icon name="{{ $message->is_read ? 'check-double' : 'check' }}" class="w-3 h-3 {{ $message->is_read ? 'text-blue-200' : 'text-blue-300/70' }}" />
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Input Area -->
            @if($session->status !== 'closed')
            <div class="p-4 bg-white dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700">
                <form action="{{ route('admin.chat.send', $session->id) }}" method="POST" class="flex items-end gap-3" id="chat-form">
                    @csrf
                    <div class="flex-1 relative">
                        <textarea name="message" rows="1" class="block w-full pl-4 pr-12 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-gray-900 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 transition-all resize-none font-sans max-h-32" placeholder="Digite sua mensagem..." required oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                        <div class="absolute right-3 bottom-2.5 text-slate-400">
                            <x-icon name="paper-plane-top" class="w-5 h-5 opacity-50" style="duotone" />
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center w-12 h-12 text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 flex-shrink-0">
                        <x-icon name="paper-plane-top" class="w-5 h-5" style="duotone" />
                    </button>
                </form>
            </div>
            @else
            <div class="p-6 bg-slate-50 dark:bg-slate-900/50 border-t border-gray-200 dark:border-slate-700 text-center">
                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Este atendimento foi encerrado</p>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-1 space-y-6 overflow-y-auto custom-scrollbar pr-1">
            <!-- Visitor Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-black text-lg shadow-lg">
                        {{ strtoupper(substr($session->visitor_name ?? 'V', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $session->visitor_name ?? 'Visitante' }}</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Visitante</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Email</label>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-300 break-all">{{ $session->visitor_email ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Telefone</label>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $session->visitor_phone ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">CPF</label>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $session->visitor_cpf ? \Modules\Chat\App\Helpers\CpfHelper::format($session->visitor_cpf) : '-' }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Iniciado em</label>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $session->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">IP</label>
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $session->ip_address ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Agent Assignment -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-tight mb-4 flex items-center gap-2">
                    <x-icon name="user-headset" class="w-4 h-4 text-blue-500" style="duotone" />
                    Atendimento
                </h3>

                @if($session->assignedTo)
                <div class="flex items-center gap-3 mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800">
                    <div class="w-8 h-8 rounded-lg bg-blue-200 dark:bg-blue-800 flex items-center justify-center text-xs font-bold text-blue-700 dark:text-blue-200 uppercase">
                        {{ substr($session->assignedTo->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-xs font-black text-blue-900 dark:text-blue-100 uppercase">{{ $session->assignedTo->name }}</div>
                        <div class="text-[9px] text-blue-500 font-bold uppercase tracking-widest">Responsável</div>
                    </div>
                </div>
                @else
                <div class="text-xs text-slate-500 mb-4 font-medium italic">Nenhum atendente atribuído.</div>
                @endif

                @if($session->status !== 'closed')
                <form action="{{ route('admin.chat.assign', $session->id) }}" method="POST">
                    @csrf
                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Transferir / Atribuir</label>
                    <select name="user_id" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-slate-900 dark:border-slate-700 dark:text-white font-sans mb-3">
                        <option value="">Selecione...</option>
                        <option value="{{ auth()->id() }}">Atribuir a mim</option>
                        @foreach($agents as $agent)
                            @if($agent->id !== auth()->id())
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors uppercase tracking-widest shadow-sm">
                        <x-icon name="arrow-right-arrow-left" class="w-3 h-3" style="duotone" />
                        Atribuir
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Auto-refresh logic (Polling) - Simplificado
        // Em produção ideal usar Websockets / Echo
        setInterval(() => {
            if({{ $session->status !== 'closed' ? 'true' : 'false' }}) {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newMessages = doc.getElementById('messages-container').innerHTML;
                        const currentScroll = messagesContainer.scrollTop + messagesContainer.clientHeight === messagesContainer.scrollHeight;

                        if (messagesContainer.innerHTML !== newMessages) {
                            messagesContainer.innerHTML = newMessages;
                            if (currentScroll) {
                                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                            }
                        }
                    });
            }
        }, 5000); // 5 segundos
    });
</script>
@endsection
