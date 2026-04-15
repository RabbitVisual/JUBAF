@extends('layouts.app')

@section('title', 'Detalhe da notificação')

@section('content')
    @php
        $ms = $notification->module_source ?? null;
        $moduleLabel = $ms ? config('notificacoes.module_sources')[$ms] ?? $ms : 'Geral';
        $type = $notification->type ?? 'info';
        $typeColors = [
            'info' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'success' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            'warning' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300',
            'danger' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'error' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'alert' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
            'system' => 'bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300',
        ];
        $typeClass = $typeColors[$type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
        $hasPayload = filled($notification->data) || filled($notification->action_url);
    @endphp

    <div class="mx-auto max-w-7xl space-y-6 pb-12 font-sans md:space-y-8 animate-fade-in">
        @include('notificacoes::paineldiretoria.partials.subnav', ['active' => 'detalhe'])

        <div
            class="relative overflow-hidden rounded-3xl border border-indigo-200/60 bg-gradient-to-br from-white via-indigo-50/40 to-violet-50/20 shadow-md dark:border-indigo-900/30 dark:from-slate-900 dark:via-indigo-950/15 dark:to-slate-900">
            <div class="relative p-6 sm:p-8">
                <nav aria-label="breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                    <a href="{{ route('diretoria.dashboard') }}"
                        class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Painel da diretoria</a>
                    <x-icon name="chevron-right" class="h-3 w-3 text-slate-400" style="duotone" />
                    <a href="{{ route('diretoria.notificacoes.index') }}"
                        class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Notificações</a>
                    <x-icon name="chevron-right" class="h-3 w-3 text-slate-400" style="duotone" />
                    <span class="font-semibold text-gray-900 dark:text-white">Detalhe</span>
                </nav>
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0">
                        <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                            <span
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/25">
                                <x-icon module="notificacoes" class="h-7 w-7" style="duotone" />
                            </span>
                            <span class="break-words">{{ $notification->title }}</span>
                        </h1>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $typeClass }}">
                                {{ $notification->type_texto }}
                            </span>
                            @if ($notification->is_read)
                                <span
                                    class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    <x-icon name="circle-check" class="h-3 w-3" style="duotone" />
                                    Lida
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-1 rounded-lg bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    <x-icon name="clock" class="h-3 w-3" style="duotone" />
                                    Por ler
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        @if (!$notification->is_read)
                            <form action="{{ route('diretoria.notificacoes.markAsRead', $notification->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-600/25 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                                    <x-icon name="check" class="h-4 w-4" style="duotone" />
                                    Marcar como lida
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('diretoria.notificacoes.index') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                            <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
                            Lista
                        </a>
                        <form action="{{ route('diretoria.notificacoes.destroy', $notification->id) }}" method="POST" class="inline"
                            onsubmit="return confirm('Tem certeza que deseja apagar esta notificação?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm font-bold text-red-700 transition hover:bg-red-100 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-950/60">
                                <x-icon name="trash" class="h-4 w-4" style="duotone" />
                                Apagar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200"
                role="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                <div
                    class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700/50 dark:bg-slate-800">
                    <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Mensagem</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Texto enviado aos utilizadores.</p>
                    </div>
                    <div class="p-6 md:p-8">
                        <div
                            class="rounded-2xl border border-gray-200 bg-gray-50/80 p-6 text-sm leading-relaxed text-gray-800 dark:border-slate-700 dark:bg-slate-900/50 dark:text-gray-200 whitespace-pre-wrap">
                            {{ $notification->message }}
                        </div>
                    </div>
                </div>

                @if ($hasPayload)
                    <div
                        class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700/50 dark:bg-slate-800">
                        <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Ligações e dados extra</h2>
                        </div>
                        <div class="space-y-6 p-6 md:p-8">
                            @if ($notification->action_url)
                                <div>
                                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Link de ação</p>
                                    <a href="{{ $notification->action_url }}" target="_blank" rel="noopener noreferrer"
                                        class="inline-flex max-w-full items-center gap-2 break-all rounded-xl bg-indigo-50 px-4 py-3 text-sm font-medium text-indigo-700 transition hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-300 dark:hover:bg-indigo-900/40">
                                        <x-icon name="link" class="h-4 w-4 shrink-0" />
                                        <span>{{ $notification->action_url }}</span>
                                        <x-icon name="arrow-up-right-from-square" class="h-3.5 w-3.5 shrink-0 opacity-80" />
                                    </a>
                                </div>
                            @endif

                            @if (filled($notification->data))
                                <details
                                    class="group rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-600 dark:bg-slate-900/60">
                                    <summary
                                        class="cursor-pointer list-none px-4 py-3 text-sm font-semibold text-slate-800 dark:text-slate-200 [&::-webkit-details-marker]:hidden">
                                        <span class="flex items-center justify-between gap-2">
                                            <span class="flex items-center gap-2">
                                                <x-icon name="code" class="h-4 w-4 text-slate-500" style="duotone" />
                                                Detalhes técnicos (avançado)
                                            </span>
                                            <x-icon name="chevron-down"
                                                class="h-4 w-4 shrink-0 text-slate-400 transition group-open:rotate-180" style="duotone" />
                                        </span>
                                    </summary>
                                    <div class="border-t border-slate-200 px-4 py-3 dark:border-slate-600">
                                        <p class="mb-2 text-xs text-slate-500 dark:text-gray-400">Payload em JSON — útil para equipas técnicas ou suporte.</p>
                                        <pre
                                            class="max-h-64 overflow-auto rounded-xl border border-slate-700 bg-slate-900 p-4 text-xs text-emerald-400">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </details>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <div
                    class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700/50 dark:bg-slate-800">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                        <h2 class="text-sm font-bold text-gray-900 dark:text-white">Resumo</h2>
                    </div>
                    <dl class="divide-y divide-gray-100 dark:divide-slate-700/80">
                        <div class="px-5 py-4">
                            <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Módulo</dt>
                            <dd class="mt-1 flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 dark:bg-slate-700">
                                    <x-icon name="layer-group" class="h-4 w-4 text-gray-500" style="duotone" />
                                </span>
                                {{ $moduleLabel }}
                            </dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Destinatário</dt>
                            <dd class="mt-2 flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-sm font-bold text-white shadow-sm">
                                    @if ($notification->user)
                                        {{ strtoupper(mb_substr($notification->user->name, 0, 2)) }}
                                    @else
                                        <x-icon name="users" class="h-5 w-5" />
                                    @endif
                                </span>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                        {{ $notification->user->name ?? ($notification->role ? ucfirst($notification->role) : 'Todos os utilizadores') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $notification->user ? 'Utilizador específico' : ($notification->role ? 'Por função (role)' : 'Envio geral') }}
                                    </p>
                                </div>
                            </dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Enviada em</dt>
                            <dd class="mt-1 flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                <x-icon name="calendar" class="h-4 w-4 text-gray-400" style="duotone" />
                                {{ $notification->created_at->format('d/m/Y \à\s H:i') }}
                                <span class="text-xs text-gray-500">({{ $notification->created_at->diffForHumans() }})</span>
                            </dd>
                        </div>
                        @if ($notification->is_read && $notification->read_at)
                            <div class="px-5 py-4">
                                <dt class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Lida em</dt>
                                <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $notification->read_at->format('d/m/Y \à\s H:i') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div
                    class="rounded-2xl border border-indigo-100 bg-gradient-to-br from-indigo-50 to-violet-50 p-5 dark:border-indigo-800/40 dark:from-indigo-950/40 dark:to-slate-900/80">
                    <div class="flex gap-3">
                        <div class="shrink-0 rounded-lg bg-white p-2 shadow-sm dark:bg-slate-800">
                            <x-icon name="signal-stream" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" style="duotone" />
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-indigo-900 dark:text-indigo-100">Tempo real</h3>
                            <p class="mt-1 text-xs leading-relaxed text-indigo-900/85 dark:text-indigo-200/85">
                                Esta notificação foi entregue pelo sistema de eventos aos canais subscritos (WebSocket).
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @if ($notification->user_id)
                                    <code
                                        class="rounded border border-indigo-200 bg-white px-2 py-1 text-[10px] text-indigo-700 dark:border-indigo-800 dark:bg-slate-900 dark:text-indigo-300">private-user.{{ $notification->user_id }}</code>
                                @elseif ($notification->role)
                                    <code
                                        class="rounded border border-indigo-200 bg-white px-2 py-1 text-[10px] text-indigo-700 dark:border-indigo-800 dark:bg-slate-900 dark:text-indigo-300">presence-role.{{ $notification->role }}</code>
                                @else
                                    <code
                                        class="rounded border border-indigo-200 bg-white px-2 py-1 text-[10px] text-indigo-700 dark:border-indigo-800 dark:bg-slate-900 dark:text-indigo-300">public-notifications</code>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
