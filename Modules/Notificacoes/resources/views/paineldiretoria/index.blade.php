@extends('paineldiretoria::components.layouts.app')

@section('title', 'Notificações')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
        @include('notificacoes::paineldiretoria.partials.subnav', ['active' => 'lista'])

        {{-- Hero --}}
        <div
            class="relative overflow-hidden rounded-3xl border border-indigo-200/60 bg-gradient-to-br from-white via-indigo-50/50 to-violet-50/30 shadow-lg shadow-indigo-900/5 dark:border-indigo-900/30 dark:from-slate-900 dark:via-indigo-950/20 dark:to-slate-900">
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-400/15 blur-3xl dark:bg-indigo-500/10" aria-hidden="true"></div>
            <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div class="min-w-0">
                    <nav aria-label="breadcrumb" class="mb-3 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                        <a href="{{ route('diretoria.dashboard') }}"
                            class="font-medium text-indigo-600 hover:text-indigo-700 hover:underline dark:text-indigo-400 dark:hover:text-indigo-300">Painel da diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                        <span class="font-semibold text-gray-900 dark:text-white">Notificações</span>
                    </nav>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-700 dark:text-indigo-400">JUBAF · Comunicação</p>
                    <h1 class="mt-2 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30 ring-4 ring-indigo-600/10 dark:ring-indigo-400/10">
                            <x-icon module="notificacoes" class="h-7 w-7" style="duotone" />
                        </span>
                        Centro de notificações
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        Veja tudo o que foi enviado pelo sistema, filtre por tipo ou estado e abra o detalhe para ler a mensagem completa. Para avisar utilizadores, use <strong>Nova notificação</strong>.
                    </p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2">
                    <a href="{{ route('diretoria.notificacoes.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800">
                        <x-icon name="plus" class="h-4 w-4" style="duotone" />
                        Nova notificação
                    </a>
                    @if (($stats['unread'] ?? 0) > 0)
                        <form action="{{ route('diretoria.notificacoes.markAllAsRead') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white/80 px-4 py-2.5 text-sm font-semibold text-gray-800 backdrop-blur-sm transition hover:border-indigo-200 hover:bg-white dark:border-slate-600 dark:bg-slate-800/80 dark:text-white dark:hover:border-indigo-700">
                                <x-icon name="check-double" class="h-4 w-4 text-indigo-600 dark:text-indigo-400" style="duotone" />
                                Marcar todas como lidas
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- Estatísticas globais --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total no sistema</p>
                        <p class="mt-1 text-2xl font-black tabular-nums tracking-tighter text-gray-900 dark:text-white">
                            {{ $stats['total'] ?? 0 }}</p>
                    </div>
                    <div
                        class="rounded-xl border border-indigo-100 bg-indigo-50 p-3 dark:border-indigo-800/50 dark:bg-indigo-900/20">
                        <x-icon name="bell" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" style="duotone" />
                    </div>
                </div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Não lidas</p>
                        <p class="mt-1 text-2xl font-black tabular-nums tracking-tighter text-amber-700 dark:text-amber-300">
                            {{ $stats['unread'] ?? 0 }}</p>
                    </div>
                    <div
                        class="rounded-xl border border-amber-100 bg-amber-50 p-3 dark:border-amber-800/50 dark:bg-amber-900/20">
                        <x-icon name="clock" class="h-6 w-6 text-amber-600 dark:text-amber-400" style="duotone" />
                    </div>
                </div>
            </div>
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md dark:border-slate-700 dark:bg-slate-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Lidas</p>
                        <p class="mt-1 text-2xl font-black tabular-nums tracking-tighter text-emerald-700 dark:text-emerald-300">
                            {{ $stats['read'] ?? 0 }}</p>
                    </div>
                    <div
                        class="rounded-xl border border-emerald-100 bg-emerald-50 p-3 dark:border-emerald-800/50 dark:bg-emerald-900/20">
                        <x-icon name="circle-check" class="h-6 w-6 text-emerald-600 dark:text-emerald-400" style="duotone" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div
            class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700/50 dark:bg-slate-800">
            <div class="flex items-center gap-2 border-b border-gray-200 bg-gray-50/50 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/50">
                <x-icon name="filter" class="h-4 w-4 text-indigo-500" style="duotone" />
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtrar lista</h2>
                <span class="text-xs text-gray-500 dark:text-gray-400">— mostra só o que corresponder aos critérios</span>
            </div>
            <form method="GET" action="{{ route('diretoria.notificacoes.index') }}" class="p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    <div class="md:col-span-2 lg:col-span-1 xl:col-span-2">
                        <label for="search" class="mb-2 block text-xs font-semibold text-gray-600 dark:text-gray-300">Pesquisar</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="magnifying-glass" class="h-4 w-4 text-gray-400" />
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Título, mensagem ou nome do utilizador…"
                                class="block w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 transition focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label for="type" class="mb-2 block text-xs font-semibold text-gray-600 dark:text-gray-300">Tipo</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="tag" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select name="type" id="type"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">Todos</option>
                                <option value="info" @selected(request('type') == 'info')>Informação</option>
                                <option value="success" @selected(request('type') == 'success')>Sucesso</option>
                                <option value="warning" @selected(request('type') == 'warning')>Aviso</option>
                                <option value="error" @selected(request('type') == 'error')>Erro</option>
                                <option value="alert" @selected(request('type') == 'alert')>Alerta</option>
                                <option value="system" @selected(request('type') == 'system')>Sistema</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="is_read" class="mb-2 block text-xs font-semibold text-gray-600 dark:text-gray-300">Estado</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="eye" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select name="is_read" id="is_read"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">Todas</option>
                                <option value="0" @selected(request('is_read') === '0' || request('is_read') === 'false')>Não lidas</option>
                                <option value="1" @selected(request('is_read') === '1' || request('is_read') === 'true')>Lidas</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="module_source" class="mb-2 block text-xs font-semibold text-gray-600 dark:text-gray-300">Módulo de origem</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="layer-group" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select name="module_source" id="module_source"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">Qualquer / geral</option>
                                @foreach ($modules as $key => $label)
                                    <option value="{{ $key }}" @selected(request('module_source') == $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="user_id" class="mb-2 block text-xs font-semibold text-gray-600 dark:text-gray-300">Utilizador (destino)</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="user" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select name="user_id" id="user_id"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">Todos</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="role" class="mb-2 block text-xs font-semibold text-gray-600 dark:text-gray-300">Função (role)</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="user-shield" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select name="role" id="role"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                <option value="">Todas</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected(request('role') == $role->name)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/20 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                        <x-icon name="magnifying-glass" class="h-5 w-5" style="duotone" />
                        Aplicar filtros
                    </button>
                    <a href="{{ route('diretoria.notificacoes.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-300 dark:hover:bg-slate-700"
                        title="Limpar todos os filtros">
                        <x-icon name="rotate-right" class="h-5 w-5" style="duotone" />
                        Limpar
                    </a>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Resultados nesta página: <strong class="text-gray-800 dark:text-gray-200">{{ $notifications->total() }}</strong>
                    </p>
                </div>
            </form>
        </div>

        {{-- Tabela --}}
        <div
            class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700/50 dark:bg-slate-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead
                        class="bg-gray-50/50 text-[10px] font-black uppercase tracking-widest text-gray-400 dark:bg-slate-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 italic">Notificação</th>
                            <th scope="col" class="px-6 py-4 italic">Tipo</th>
                            <th scope="col" class="px-6 py-4 italic">Módulo</th>
                            <th scope="col" class="px-6 py-4 italic">Data</th>
                            <th scope="col" class="px-6 py-4 text-right italic">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        @forelse ($notifications as $notification)
                            <tr
                                class="group transition-colors hover:bg-gray-50 dark:hover:bg-slate-700/50 {{ !$notification->is_read && !$notification->read_at ? 'bg-indigo-50/30 dark:bg-indigo-900/10' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <a href="{{ route('diretoria.notificacoes.show', $notification->id) }}"
                                                class="font-bold text-gray-900 hover:text-indigo-600 hover:underline dark:text-white dark:hover:text-indigo-400 {{ !$notification->is_read && !$notification->read_at ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                                {{ $notification->data['title'] ?? ($notification->title ?? 'Sem título') }}
                                            </a>
                                            @if (!$notification->is_read && !$notification->read_at)
                                                <span
                                                    class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">Nova</span>
                                            @endif
                                        </div>
                                        <p class="max-w-xl truncate text-sm text-gray-500 dark:text-gray-400">
                                            {{ $notification->data['message'] ?? ($notification->message ?? '') }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $type = $notification->data['type'] ?? ($notification->type ?? 'info');
                                        $colors = [
                                            'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                                            'success' =>
                                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                                            'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                                            'error' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                                            'alert' =>
                                                'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300',
                                            'system' =>
                                                'bg-violet-100 text-violet-800 dark:bg-violet-900/30 dark:text-violet-300',
                                        ];
                                        $colorClass = $colors[$type] ?? $colors['info'];
                                        $iconMap = [
                                            'info' => 'circle-info',
                                            'success' => 'circle-check',
                                            'warning' => 'triangle-exclamation',
                                            'error' => 'circle-xmark',
                                            'alert' => 'bell',
                                            'system' => 'gear',
                                        ];
                                        $icon = $iconMap[$type] ?? 'circle-info';
                                    @endphp
                                    <div
                                        class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide {{ $colorClass }}">
                                        <x-icon name="{{ $icon }}" class="h-3.5 w-3.5" style="duotone" />
                                        {{ ucfirst($type) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-slate-700">
                                            <x-icon name="layer-group" class="h-4 w-4" style="duotone" />
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            @php
                                                $ms = $notification->module_source ?? ($notification->data['module'] ?? null);
                                                $label = $ms ? config('notificacoes.module_sources')[$ms] ?? $ms : 'Geral';
                                            @endphp
                                            {{ $label }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
                                        <x-icon name="calendar" class="h-4 w-4" style="duotone" />
                                        <span class="text-xs font-medium">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-1 sm:gap-2">
                                        <a href="{{ route('diretoria.notificacoes.show', $notification->id) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-indigo-600 shadow-sm transition hover:bg-indigo-50 dark:border-slate-600 dark:bg-slate-800 dark:text-indigo-400 dark:hover:bg-indigo-950/40"
                                            title="Ver detalhe completo">
                                            <x-icon name="arrow-up-right-from-square" class="h-4 w-4" style="duotone" />
                                            <span class="hidden sm:inline">Abrir</span>
                                        </a>
                                        @if (!$notification->is_read && !$notification->read_at)
                                            <form action="{{ route('diretoria.notificacoes.markAsRead', $notification->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="rounded-lg p-2 text-indigo-600 transition-colors hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-900/30"
                                                    title="Marcar como lida">
                                                    <x-icon name="check" class="h-5 w-5" style="duotone" />
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('diretoria.notificacoes.destroy', $notification->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja excluir esta notificação?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg p-2 text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30"
                                                title="Excluir">
                                                <x-icon name="trash" class="h-5 w-5" style="duotone" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div
                                        class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-50 dark:bg-slate-800/50">
                                        <x-icon name="bell-slash" class="h-10 w-10 text-gray-300 dark:text-gray-600" style="duotone" />
                                    </div>
                                    <h3 class="mb-1 text-lg font-bold text-gray-900 dark:text-white">Nenhum resultado</h3>
                                    <p class="mx-auto max-w-md text-sm text-gray-500 dark:text-gray-400">
                                        Não há notificações com estes filtros. Tente limpar a pesquisa ou envie uma nova mensagem com <strong>Nova notificação</strong>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($notifications->hasPages())
                <div class="border-t border-gray-200 bg-gray-50/50 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/50">
                    {{ $notifications->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
