@extends($layout)

@section('title', 'Congregações — lista')

@section('content')
@php
    $filterClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-cyan-400';
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'list'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-800 dark:text-cyan-400">Diretoria · Cadastro</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-600 text-white shadow-lg shadow-cyan-600/25">
                    <x-module-icon module="Igrejas" class="h-7 w-7" />
                </span>
                Congregações
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Lista completa com pesquisa, cidade e estado — exportação CSV para secretaria e assembleia.
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap gap-2">
            @can('export', \Modules\Igrejas\App\Models\Church::class)
                <a href="{{ route($routePrefix.'.export.csv', request()->query()) }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:border-cyan-200 hover:bg-cyan-50/40 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:border-cyan-700">
                    <x-icon name="download" class="h-4 w-4 text-cyan-600 dark:text-cyan-400" style="duotone" />
                    Exportar CSV
                </a>
            @endcan
            @can('create', \Modules\Igrejas\App\Models\Church::class)
                <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/25 transition hover:bg-cyan-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Nova congregação
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[12rem] flex-1 sm:max-w-xs">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Pesquisar</label>
                <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nome, cidade, e-mail…" class="{{ $filterClass }}">
            </div>
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Cidade</label>
                <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="Filtrar…" class="{{ $filterClass }}">
            </div>
            <div class="min-w-[10rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Estado</label>
                <select name="active" class="{{ $filterClass }}" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <option value="1" @selected(($filters['active'] ?? '') === '1')>Ativas</option>
                    <option value="0" @selected(($filters['active'] ?? '') === '0')>Inativas</option>
                </select>
            </div>
            @if(isset($jubafSectors) && $jubafSectors->isNotEmpty())
            <div class="min-w-[10rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Setor (ERP)</label>
                <select name="jubaf_sector_id" class="{{ $filterClass }}" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    @foreach($jubafSectors as $s)
                        <option value="{{ $s->id }}" @selected(($filters['jubaf_sector_id'] ?? '') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="min-w-[10rem] flex-1 sm:max-w-[12rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Setor (texto)</label>
                <input type="text" name="sector" value="{{ $filters['sector'] ?? '' }}" placeholder="Filtrar…" class="{{ $filterClass }}">
            </div>
            <div class="min-w-[10rem]">
                <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Cooperação</label>
                <select name="cooperation_status" class="{{ $filterClass }}" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    @foreach(\Modules\Igrejas\App\Models\Church::cooperationStatuses() as $st)
                        <option value="{{ $st }}" @selected(($filters['cooperation_status'] ?? '') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-cyan-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-cyan-600/20 transition hover:bg-cyan-700">
                    <x-icon name="filter" class="h-4 w-4" style="solid" />
                    Aplicar
                </button>
                <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:text-gray-300 dark:hover:bg-slate-700">Limpar</a>
            </div>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/80 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3.5">Nome</th>
                        <th class="px-4 py-3.5">Setor</th>
                        <th class="px-4 py-3.5">Cidade</th>
                        <th class="px-4 py-3.5">Líderes</th>
                        <th class="px-4 py-3.5">Jovens</th>
                        <th class="px-4 py-3.5">Estado</th>
                        <th class="px-4 py-3.5 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($churches as $c)
                        <tr class="transition hover:bg-cyan-50/40 dark:hover:bg-slate-900/50">
                            <td class="px-4 py-3.5 font-semibold text-gray-900 dark:text-white">{{ $c->name }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->sector ?? '—' }}</td>
                            <td class="px-4 py-3.5 text-gray-600 dark:text-gray-300">{{ $c->city ?? '—' }}</td>
                            <td class="px-4 py-3.5 tabular-nums text-gray-700 dark:text-gray-300">{{ $c->leaders_count }}</td>
                            <td class="px-4 py-3.5 tabular-nums text-gray-700 dark:text-gray-300">{{ $c->jovens_members_count }}</td>
                            <td class="px-4 py-3.5">
                                @if($c->is_active)
                                    <span class="inline-flex rounded-lg bg-emerald-100 px-2 py-0.5 text-xs font-bold text-emerald-900 ring-1 ring-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-100 dark:ring-emerald-800/50">Ativa</span>
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
                            <td colspan="7" class="px-6 py-16 text-center">
                                <x-icon name="church" class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" style="duotone" />
                                <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhuma congregação encontrada</p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste os filtros ou registe uma nova igreja.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>{{ $churches->links() }}</div>
</div>
@endsection
