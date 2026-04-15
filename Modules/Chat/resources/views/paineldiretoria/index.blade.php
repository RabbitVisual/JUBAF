@extends('layouts.app')

@section('title', 'Chat — Sessões')

@section('content')
@php
    $inputClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $labelClass = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $hasFilters = filled(request('search')) || filled(request('status')) || filled(request('assigned_to')) || filled(request('date_from')) || filled(request('date_to'));
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('chat::paineldiretoria.partials.subnav', ['active' => 'sessions'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Atendimento ao visitante</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-module-icon module="Chat" class="h-6 w-6" />
                </span>
                Sessões de chat
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Conversas públicas iniciadas pelo widget do site. Filtre por estado, atendente ou pesquisa.</p>
        </div>
        <a href="{{ route('diretoria.chat.realtime') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
            <x-icon name="bolt" class="h-4 w-4" style="duotone" />
            Abrir tempo real
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-950/30 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach([
            ['label' => 'Aguardando', 'value' => $stats['waiting'] ?? 0, 'icon' => 'clock', 'tone' => 'amber'],
            ['label' => 'Ativos', 'value' => $stats['active'] ?? 0, 'icon' => 'message-dots', 'tone' => 'emerald'],
            ['label' => 'Mensagens hoje', 'value' => $stats['messages_today'] ?? 0, 'icon' => 'comments', 'tone' => 'sky'],
            ['label' => 'Encerrados', 'value' => $stats['closed'] ?? 0, 'icon' => 'check-double', 'tone' => 'slate'],
        ] as $card)
            <div class="rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-gray-300">
                        <x-icon name="{{ $card['icon'] }}" class="h-5 w-5" style="duotone" />
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                        <p class="text-xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $card['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
            @if($hasFilters)
                <a href="{{ route('diretoria.chat.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Limpar filtros</a>
            @endif
        </div>
        <form method="GET" action="{{ route('diretoria.chat.index') }}" class="flex flex-col gap-4 lg:flex-row lg:flex-wrap lg:items-end">
            <div class="min-w-[12rem] flex-1">
                <label class="{{ $labelClass }}">Pesquisar</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, CPF, e-mail ou ID" class="{{ $inputClass }}">
            </div>
            <div class="min-w-[10rem]">
                <label class="{{ $labelClass }}">Estado</label>
                <select name="status" class="{{ $inputClass }}">
                    <option value="">Todos</option>
                    <option value="waiting" @selected(request('status') === 'waiting')>Aguardando</option>
                    <option value="active" @selected(request('status') === 'active')>Ativos</option>
                    <option value="closed" @selected(request('status') === 'closed')>Encerrados</option>
                </select>
            </div>
            <div class="min-w-[12rem]">
                <label class="{{ $labelClass }}">Atendente</label>
                <select name="assigned_to" class="{{ $inputClass }}">
                    <option value="">Todos</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" @selected(request('assigned_to') == $agent->id)>{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-[9rem]">
                <label class="{{ $labelClass }}">De</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="{{ $inputClass }}">
            </div>
            <div class="min-w-[9rem]">
                <label class="{{ $labelClass }}">Até</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="{{ $inputClass }}">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-slate-700 dark:hover:bg-slate-600">
                <x-icon name="filter" class="h-4 w-4 opacity-90" style="duotone" />
                Aplicar
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        <th class="px-5 py-3.5">Visitante</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5">Atividade</th>
                        <th class="px-5 py-3.5">Responsável</th>
                        <th class="w-36 px-5 py-3.5 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($sessions as $session)
                        @php
                            $statusConfig = [
                                'waiting' => ['cls' => 'bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-100', 'label' => 'Aguardando'],
                                'active' => ['cls' => 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100', 'label' => 'Em atendimento'],
                                'closed' => ['cls' => 'bg-slate-100 text-slate-700 dark:bg-slate-700/60 dark:text-slate-200', 'label' => 'Encerrado'],
                            ];
                            $st = $statusConfig[$session->status] ?? ['cls' => 'bg-gray-100 text-gray-700', 'label' => $session->status];
                        @endphp
                        <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-slate-900/50">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="relative flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-sm font-bold text-white shadow-sm">
                                        {{ strtoupper(substr($session->visitor_name ?? 'V', 0, 1)) }}
                                        @if($session->unread_count_user > 0)
                                            <span class="absolute -right-1 -top-1 flex h-5 min-w-[1.25rem] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ $session->unread_count_user }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $session->visitor_name ?? 'Visitante' }}</p>
                                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $session->visitor_email ?? $session->session_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $st['cls'] }}">{{ $st['label'] }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-300">
                                <span class="font-medium">{{ $session->last_activity_at ? $session->last_activity_at->diffForHumans() : $session->created_at->diffForHumans() }}</span>
                                <span class="mt-0.5 block text-xs text-gray-500 dark:text-gray-400">Início {{ $session->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-700 dark:text-gray-300">
                                @if($session->assignedTo)
                                    {{ $session->assignedTo->name }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('diretoria.chat.show', $session->id) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Abrir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="mx-auto flex max-w-md flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-gray-500">
                                        <x-icon name="message-slash" class="h-7 w-7" style="duotone" />
                                    </span>
                                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhuma sessão encontrada</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste os filtros ou aguarde novos contactos pelo widget.</p>
                                    @if($hasFilters)
                                        <a href="{{ route('diretoria.chat.index') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-slate-600 dark:text-gray-200 dark:hover:bg-slate-700">
                                            Limpar filtros
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($sessions->hasPages())
        <div class="px-1">{{ $sessions->links() }}</div>
    @endif
</div>
@endsection
