@extends('layouts.public-site')

@section('title', 'Minhas notificações')

@section('content')
@php
    $moduleLabels = config('notificacoes.module_sources', []);
    $filters = $filters ?? [];
@endphp
<div class="min-h-[60vh] max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950/40 dark:text-red-200" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3 mb-2">
                <span class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 shadow-lg">
                    <x-icon module="notificacoes" class="h-6 w-6 text-white" />
                </span>
                <span>Minhas notificações</span>
            </h1>
            <nav aria-label="breadcrumb" class="flex flex-wrap items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                <a href="{{ url('/') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Início</a>
                <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
                @if (Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Área da conta</a>
                    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
                @endif
                <span class="text-slate-900 dark:text-white font-medium">Notificações</span>
            </nav>
        </div>
        @if (($unreadCount ?? 0) > 0)
            <form action="{{ route('notificacoes.read-all') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                    <x-icon name="check-double" class="h-5 w-5" />
                    Marcar todas como lidas
                </button>
            </form>
        @endif
    </div>

    <div class="mt-8 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/80">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                <x-icon name="filter" class="h-4 w-4 text-indigo-500" />
                Filtros
            </div>
        </div>
        <form method="GET" action="{{ route('notificacoes.index') }}" class="p-6">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4 md:items-end">
                <div class="md:col-span-1">
                    <label for="filter-type" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Tipo</label>
                    <select name="type" id="filter-type" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        <option value="">Todos</option>
                        <option value="info" {{ ($filters['type'] ?? '') === 'info' ? 'selected' : '' }}>Informação</option>
                        <option value="success" {{ ($filters['type'] ?? '') === 'success' ? 'selected' : '' }}>Sucesso</option>
                        <option value="warning" {{ ($filters['type'] ?? '') === 'warning' ? 'selected' : '' }}>Aviso</option>
                        <option value="error" {{ ($filters['type'] ?? '') === 'error' ? 'selected' : '' }}>Erro</option>
                        <option value="alert" {{ ($filters['type'] ?? '') === 'alert' ? 'selected' : '' }}>Alerta</option>
                        <option value="system" {{ ($filters['type'] ?? '') === 'system' ? 'selected' : '' }}>Sistema</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label for="filter-panel" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Painel</label>
                    <select name="panel" id="filter-panel" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        <option value="">Todos</option>
                        <option value="admin" {{ ($filters['panel'] ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="diretoria" {{ ($filters['panel'] ?? '') === 'diretoria' ? 'selected' : '' }}>Diretoria</option>
                        <option value="jovens" {{ ($filters['panel'] ?? '') === 'jovens' ? 'selected' : '' }}>Jovens</option>
                        <option value="lideres" {{ ($filters['panel'] ?? '') === 'lideres' ? 'selected' : '' }}>Líderes</option>
                        <option value="diretoria" {{ ($filters['panel'] ?? '') === 'diretoria' ? 'selected' : '' }}>Diretoria</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label for="filter-read" class="mb-1 block text-xs font-medium text-slate-600 dark:text-slate-400">Estado</label>
                    <select name="is_read" id="filter-read" class="block w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                        <option value="">Todas</option>
                        <option value="0" {{ isset($filters['is_read']) && (string) $filters['is_read'] === '0' ? 'selected' : '' }}>Não lidas</option>
                        <option value="1" {{ isset($filters['is_read']) && (string) $filters['is_read'] === '1' ? 'selected' : '' }}>Lidas</option>
                    </select>
                </div>
                <div class="flex flex-wrap gap-2 md:col-span-1 md:justify-end">
                    <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 md:flex-initial">
                        <x-icon name="magnifying-glass" class="h-5 w-5" />
                        Aplicar
                    </button>
                    <a href="{{ route('notificacoes.index') }}" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 md:flex-initial" title="Limpar filtros">
                        <x-icon name="rotate-right" class="h-5 w-5" />
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-8 rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900 overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-700">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Lista</h2>
        </div>
        <div class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse($notifications as $notification)
                @php
                    $typeConfig = [
                        'info' => ['color' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'success' => ['color' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'warning' => ['color' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400', 'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
                        'error' => ['color' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400', 'icon' => 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'alert' => ['color' => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400', 'icon' => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0'],
                        'system' => ['color' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400', 'icon' => 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z'],
                    ];
                    $config = $typeConfig[$notification->type] ?? $typeConfig['info'];
                    $ms = $notification->module_source;
                    $moduleLabel = $ms ? ($moduleLabels[$ms] ?? $ms) : null;
                @endphp
                <div class="p-6 transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ !$notification->is_read ? 'bg-indigo-50/40 dark:bg-indigo-950/20 border-l-4 border-indigo-500' : '' }}">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full shadow-sm {{ $config['color'] }}">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
                                </svg>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="mb-1 flex flex-wrap items-center gap-2">
                                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $notification->title }}
                                        </h3>
                                        @if (!$notification->is_read)
                                            <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">
                                                Nova
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                                        {{ $notification->message }}
                                    </p>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if ($moduleLabel)
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h69.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                                </svg>
                                                {{ $moduleLabel }}
                                            </span>
                                        @endif
                                        @if ($notification->panel)
                                            <span class="rounded bg-slate-100 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                {{ $notification->panel }}
                                            </span>
                                        @endif
                                    </div>
                                    @if ($notification->action_url)
                                        <div class="mt-3">
                                            <a href="{{ $notification->action_url }}" class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Ver detalhes
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex shrink-0 items-center gap-2">
                                    @if (!$notification->is_read)
                                        <form action="{{ route('notificacoes.read', $notification->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="rounded-lg p-2 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40" title="Marcar como lida">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notificacoes.destroy', $notification->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja eliminar esta notificação?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/40" title="Eliminar">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                        <svg class="h-10 w-10 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">Nenhuma notificação</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Neste momento não tens notificações com os filtros escolhidos.</p>
                </div>
            @endforelse
        </div>

        @if ($notifications->hasPages())
            <div class="border-t border-slate-200 px-6 py-4 dark:border-slate-700">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
