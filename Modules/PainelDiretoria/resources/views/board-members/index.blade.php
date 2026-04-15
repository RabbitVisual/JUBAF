@extends('paineldiretoria::components.layouts.app')

@section('title', 'Diretoria — membros')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 pb-16 font-sans md:space-y-8 animate-fade-in">
        @include('paineldiretoria::partials.board-members-subnav', ['active' => 'lista'])

        <div
            class="relative overflow-hidden rounded-3xl border border-indigo-200/60 bg-gradient-to-br from-white via-indigo-50/50 to-violet-50/30 shadow-md dark:border-indigo-900/30 dark:from-slate-900 dark:via-indigo-950/20 dark:to-slate-900">
            <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-indigo-400/15 blur-3xl dark:bg-indigo-500/10"
                aria-hidden="true"></div>
            <div class="relative flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <div class="min-w-0">
                    <nav aria-label="breadcrumb" class="mb-3 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                        <a href="{{ route('diretoria.dashboard') }}"
                            class="font-medium text-indigo-700 hover:underline dark:text-indigo-400">Painel da diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                        <span class="font-semibold text-gray-900 dark:text-white">Membros no site</span>
                    </nav>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-700 dark:text-indigo-400">Site público
                    </p>
                    <h1 class="mt-2 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                        <span
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30">
                            <x-icon name="users" class="h-7 w-7" style="duotone" />
                        </span>
                        Equipa da diretoria
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        Ordem, cargo público e foto na página <strong>/equipe/diretoria</strong>. Só entradas <strong>ativas</strong> são listadas publicamente.
                    </p>
                </div>
                @can('create', \App\Models\BoardMember::class)
                    <a href="{{ route($routePrefix . '.create') }}"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-indigo-600/25 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                        <x-icon name="plus" class="h-4 w-4" style="duotone" />
                        Novo membro
                    </a>
                @endcan
            </div>
        </div>

        @isset($stats)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div
                    class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-slate-500">Total</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Registos na base</p>
                </div>
                <div
                    class="rounded-2xl border border-emerald-200/80 bg-emerald-50/50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-950/25">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800 dark:text-emerald-300">Visíveis no
                        site</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-emerald-800 dark:text-emerald-200">{{ $stats['active'] }}
                    </p>
                    <p class="mt-1 text-xs text-emerald-800/80 dark:text-emerald-400/90">Ativos na listagem pública</p>
                </div>
                <div
                    class="rounded-2xl border border-slate-200/90 bg-slate-50/80 p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800/60">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-400">Ocultos</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-800 dark:text-slate-200">{{ $stats['inactive'] }}
                    </p>
                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-500">Inativos (não aparecem)</p>
                </div>
            </div>
        @endisset

        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">
                {{ session('success') }}</div>
        @endif

        <div
            class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead
                        class="bg-gray-50/80 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:bg-slate-900/50 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-4 sm:px-6">Ordem</th>
                            <th class="px-2 py-4 sm:px-3">Foto</th>
                            <th class="px-6 py-4">Nome</th>
                            <th class="px-6 py-4">Cargo</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80">
                        @forelse($members as $m)
                            <tr class="transition hover:bg-indigo-50/40 dark:hover:bg-slate-700/30">
                                <td class="whitespace-nowrap px-4 py-4 tabular-nums text-gray-600 dark:text-gray-400 sm:px-6">
                                    {{ $m->sort_order }}</td>
                                <td class="px-2 py-3 sm:px-3">
                                    @if ($m->photo_path)
                                        <img src="{{ $m->photoUrl() }}" alt=""
                                            class="h-11 w-11 rounded-xl object-cover shadow-sm ring-2 ring-white dark:ring-slate-700" />
                                    @else
                                        <span
                                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-100 text-indigo-400 dark:bg-indigo-950/50 dark:text-indigo-500">
                                            <x-icon name="user" class="h-5 w-5" style="duotone" />
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $m->full_name }}</span>
                                    @if ($m->group_label)
                                        <p class="mt-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-400">
                                            {{ $m->group_label }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $m->public_title }}</td>
                                <td class="px-6 py-4">
                                    @if ($m->is_active)
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Ativo</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-700 dark:text-slate-300">Oculto</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @can('update', $m)
                                            <a href="{{ route($routePrefix . '.edit', $m) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40">
                                                <x-icon name="pen-to-square" class="h-3.5 w-3.5" style="duotone" />
                                                Editar
                                            </a>
                                        @endcan
                                        @can('delete', $m)
                                            <form action="{{ route($routePrefix . '.destroy', $m) }}" method="post" class="inline"
                                                onsubmit="return confirm('Remover este membro da lista?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1 text-sm font-semibold text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30">
                                                    <x-icon name="trash" class="h-3.5 w-3.5" style="duotone" />
                                                    Excluir
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <x-icon name="users" class="mx-auto mb-4 h-12 w-12 text-indigo-200 dark:text-indigo-900/40" style="duotone" />
                                    <p class="text-base font-semibold text-gray-800 dark:text-gray-200">Nenhum membro
                                        cadastrado</p>
                                    <p class="mx-auto mt-2 max-w-md text-sm text-gray-500 dark:text-slate-400">
                                        Adicione fotos e cargos para a equipa aparecer na página pública da diretoria.
                                    </p>
                                    @can('create', \App\Models\BoardMember::class)
                                        <a href="{{ route($routePrefix . '.create') }}"
                                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-indigo-700">
                                            <x-icon name="user-plus" class="h-4 w-4" style="duotone" />
                                            Novo membro
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($members->hasPages())
                <div class="border-t border-gray-100 px-6 py-4 dark:border-slate-700">{{ $members->links() }}</div>
            @endif
        </div>
    </div>
@endsection
