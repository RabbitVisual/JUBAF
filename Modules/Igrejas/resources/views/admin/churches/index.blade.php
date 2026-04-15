@extends('admin::layouts.admin')

@section('title', 'Igrejas JUBAF — Super-admin')

@section('content')
@php
    $filterClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-cyan-400';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-6">
    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="flex items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white">
                <x-module-icon module="Igrejas" class="h-9 w-9 shrink-0" />
                Congregações (ASBAF / JUBAF)
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Gestão global do cadastro — espelha o módulo da diretoria com permissões Spatie.</p>
            <p class="mt-2 text-xs font-medium text-cyan-700 dark:text-cyan-400">Super-admin: alterações refletem-se em todos os painéis.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @can('create', \Modules\Igrejas\App\Models\Church::class)
                <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Nova igreja
                </a>
            @endcan
            @can('export', \Modules\Igrejas\App\Models\Church::class)
                <a href="{{ route($routePrefix.'.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:border-cyan-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-cyan-700">
                    <x-icon name="download" class="h-4 w-4 text-cyan-600 dark:text-cyan-400" style="duotone" />
                    Exportar CSV
                </a>
            @endcan
        </div>
    </div>

    @isset($stats)
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Congregações</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $stats['churches_total'] }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 dark:border-emerald-900/50 dark:bg-emerald-950/30">
                <p class="text-xs font-bold uppercase text-emerald-800 dark:text-emerald-300">Ativas</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-900 dark:text-emerald-100">{{ $stats['churches_active'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Contas c/ igreja</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $stats['users_with_church'] }}</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Jovens ligados</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $stats['jovens_linked'] }}</p>
            </div>
            <div class="col-span-2 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800 lg:col-span-1">
                <p class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Líderes ligados</p>
                <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $stats['lideres_linked'] }}</p>
            </div>
        </div>
    @endisset

    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] flex-1 sm:max-w-xs">
                <label class="mb-1.5 block text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Pesquisar</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nome, cidade, e-mail…" class="{{ $filterClass }}">
            </div>
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Cidade</label>
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="Filtrar…" class="{{ $filterClass }}">
            </div>
            <div class="min-w-[10rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase text-gray-500 dark:text-gray-400">Estado</label>
                <select name="active" class="{{ $filterClass }}">
                    <option value="">Todas</option>
                    <option value="1" @selected(($filters['active'] ?? '') === '1')>Ativas</option>
                    <option value="0" @selected(($filters['active'] ?? '') === '0')>Inativas</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/20 hover:bg-cyan-700">
                <x-icon name="filter" class="h-4 w-4" style="solid" />
                Filtrar
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/80 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3.5">Nome</th>
                        <th class="px-4 py-3.5">Cidade</th>
                        <th class="px-4 py-3.5">Líderes</th>
                        <th class="px-4 py-3.5">Jovens</th>
                        <th class="px-4 py-3.5">Estado</th>
                        <th class="px-4 py-3.5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($churches as $c)
                        <tr class="hover:bg-cyan-50/30 dark:hover:bg-slate-900/40">
                            <td class="px-4 py-3.5 font-semibold text-gray-900 dark:text-white">{{ $c->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->city ?? '—' }}</td>
                            <td class="px-4 py-3.5 tabular-nums">{{ $c->leaders_count }}</td>
                            <td class="px-4 py-3.5 tabular-nums">{{ $c->jovens_members_count }}</td>
                            <td class="px-4 py-3.5">
                                @if($c->is_active)
                                    <span class="inline-flex rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-200">Ativa</span>
                                @else
                                    <span class="inline-flex rounded-lg bg-slate-200 px-2 py-0.5 text-xs font-bold text-slate-800 dark:bg-slate-700 dark:text-slate-200">Inativa</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right space-x-2">
                                @can('view', $c)
                                    <a href="{{ route($routePrefix.'.show', $c) }}" class="text-sm font-bold text-cyan-700 hover:underline dark:text-cyan-400">Ver</a>
                                @endcan
                                @can('update', $c)
                                    <a href="{{ route($routePrefix.'.edit', $c) }}" class="text-sm font-semibold text-gray-600 hover:text-cyan-700 dark:text-gray-400 dark:hover:text-cyan-400">Editar</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Nenhuma igreja cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $churches->links() }}</div>
</div>
@endsection
