@extends($layout)

@section('title', 'Devocionais')

@section('content')
    <div class="mx-auto max-w-7xl space-y-6 pb-16 font-sans md:space-y-8 animate-fade-in">
        @include('paineldiretoria::partials.devotionals-subnav', ['active' => 'lista'])

        <div
            class="relative overflow-hidden rounded-3xl border border-amber-200/60 bg-gradient-to-br from-amber-50/80 via-white to-orange-50/40 p-6 shadow-md dark:border-amber-900/30 dark:from-amber-950/20 dark:via-slate-900 dark:to-slate-900 sm:p-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="min-w-0">
                    <nav aria-label="breadcrumb" class="mb-3 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                        <a href="{{ route('diretoria.dashboard') }}"
                            class="font-medium text-amber-700 hover:underline dark:text-amber-400">Painel da diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                        <span class="font-semibold text-gray-900 dark:text-white">Devocionais</span>
                    </nav>
                    <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                        <x-icon name="book-open" class="h-9 w-9 shrink-0 text-amber-600 dark:text-amber-400" style="duotone" />
                        Publicações espirituais
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                        Artigos com data, leitura bíblica e estado rascunho/publicado. Visíveis na rota pública
                        <strong>/devocionais</strong> quando publicados.
                    </p>
                </div>
                @can('create', \App\Models\Devotional::class)
                    <a href="{{ route($routePrefix . '.create') }}"
                        class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-amber-600/25 transition hover:bg-amber-700">
                        <x-icon name="plus" class="h-4 w-4" style="duotone" />
                        Novo devocional
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
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Todos os registos</p>
                </div>
                <div
                    class="rounded-2xl border border-emerald-200/80 bg-emerald-50/50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-950/25">
                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-800 dark:text-emerald-300">Publicados</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-emerald-800 dark:text-emerald-200">{{ $stats['published'] }}</p>
                    <p class="mt-1 text-xs text-emerald-800/80 dark:text-emerald-400/90">Visíveis no site</p>
                </div>
                <div
                    class="rounded-2xl border border-slate-200/90 bg-slate-50/80 p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800/60">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-400">Rascunhos</p>
                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-800 dark:text-slate-200">{{ $stats['draft'] }}</p>
                    <p class="mt-1 text-xs text-slate-600 dark:text-slate-500">Ainda em edição</p>
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
                            <th class="px-6 py-4">Título</th>
                            <th class="px-6 py-4">Data</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700/80">
                        @forelse($rows as $row)
                            <tr class="transition hover:bg-amber-50/40 dark:hover:bg-slate-700/30">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $row->title }}</span>
                                    @if ($row->scripture_reference)
                                        <p class="mt-0.5 text-xs text-amber-800/90 dark:text-amber-400/90">
                                            {{ $row->scripture_reference }}</p>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-600 dark:text-gray-300">
                                    {{ $row->devotional_date?->format('d/m/Y') ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if ($row->status === \App\Models\Devotional::STATUS_PUBLISHED)
                                        <span
                                            class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">Publicado</span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600 dark:bg-slate-700/80 dark:text-slate-300">Rascunho</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <div class="flex flex-wrap items-center justify-end gap-2">
                                        @can('update', $row)
                                            <a href="{{ route($routePrefix . '.edit', $row) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1 text-sm font-semibold text-amber-700 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-950/40">
                                                <x-icon name="pen-to-square" class="h-3.5 w-3.5" style="duotone" />
                                                Editar
                                            </a>
                                        @endcan
                                        @can('delete', $row)
                                            <form action="{{ route($routePrefix . '.destroy', $row) }}" method="post" class="inline"
                                                onsubmit="return confirm('Remover este devocional?');">
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
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <x-icon name="book-open" class="mx-auto mb-4 h-12 w-12 text-amber-200 dark:text-amber-900/50" style="duotone" />
                                    <p class="text-base font-semibold text-gray-800 dark:text-gray-200">Nenhum devocional ainda</p>
                                    <p class="mx-auto mt-2 max-w-sm text-sm text-gray-500 dark:text-slate-400">
                                        Crie o primeiro com título, passagem bíblica e reflexão — o assistente de leitura
                                        usa os dados do módulo Bíblia.
                                    </p>
                                    @can('create', \App\Models\Devotional::class)
                                        <a href="{{ route($routePrefix . '.create') }}"
                                            class="mt-6 inline-flex items-center gap-2 rounded-xl bg-amber-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-amber-700">
                                            <x-icon name="plus" class="h-4 w-4" style="duotone" />
                                            Criar devocional
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($rows->hasPages())
                <div class="border-t border-gray-100 px-6 py-4 dark:border-slate-700">{{ $rows->links() }}</div>
            @endif
        </div>
    </div>
@endsection
