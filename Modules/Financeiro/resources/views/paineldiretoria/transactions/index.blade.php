@extends($layout)

@section('title', 'Lançamentos financeiros')

@section('content')
@php
    $inputClass = 'rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400 dark:focus:ring-emerald-400/20';
    $labelClass = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400';
    $hasFilters = filled($filters['from'] ?? null) || filled($filters['to'] ?? null) || filled($filters['direction'] ?? null) || filled($filters['church_id'] ?? null) || filled($filters['scope'] ?? null) || filled($filters['source'] ?? null) || filled($filters['category_id'] ?? null);
@endphp
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'transactions'])

    @if(session('warning'))
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100">
            {{ session('warning') }}
        </div>
    @endif

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Livro da tesouraria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                    <x-icon name="list" class="h-5 w-5" style="duotone" />
                </span>
                Lançamentos
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Livro da tesouraria: receitas e despesas com data, categoria e âmbito <strong>regional (JUBAF)</strong> ou <strong>por igreja</strong>, alinhado ao estatuto e à transparência perante a assembleia.</p>
        </div>
        @can('create', \Modules\Financeiro\App\Models\FinTransaction::class)
            <a href="{{ route('diretoria.financeiro.transactions.create') }}" class="inline-flex shrink-0 items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                <x-icon name="plus" class="h-4 w-4" style="solid" />
                Novo lançamento
            </a>
        @endcan
    </div>

    <div class="rounded-2xl border border-gray-200/90 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
            @if($hasFilters)
                <a href="{{ route('diretoria.financeiro.transactions.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Limpar filtros</a>
            @endif
        </div>
        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[9rem]">
                <label class="{{ $labelClass }}">De</label>
                <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="w-full {{ $inputClass }}">
            </div>
            <div class="min-w-[9rem]">
                <label class="{{ $labelClass }}">Até</label>
                <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="w-full {{ $inputClass }}">
            </div>
            <div class="min-w-[10rem]">
                <label class="{{ $labelClass }}">Tipo</label>
                <select name="direction" class="w-full {{ $inputClass }}">
                    <option value="">Todos</option>
                    <option value="in" @selected(($filters['direction'] ?? '') === 'in')>Receita</option>
                    <option value="out" @selected(($filters['direction'] ?? '') === 'out')>Despesa</option>
                </select>
            </div>
            <div class="min-w-[11rem]">
                <label class="{{ $labelClass }}">Âmbito</label>
                <select name="scope" class="w-full {{ $inputClass }}">
                    <option value="">Todos</option>
                    <option value="regional" @selected(($filters['scope'] ?? '') === 'regional')>Regional (JUBAF)</option>
                    <option value="igreja" @selected(($filters['scope'] ?? '') === 'igreja')>Por igreja</option>
                </select>
            </div>
            @if($churches->isNotEmpty())
                <div class="min-w-[12rem] max-w-xs grow">
                    <label class="{{ $labelClass }}">Igreja</label>
                    <select name="church_id" class="w-full {{ $inputClass }}">
                        <option value="">Todas</option>
                        @foreach($churches as $c)
                            <option value="{{ $c->id }}" @selected(($filters['church_id'] ?? '') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if(isset($filterCategories) && $filterCategories->isNotEmpty())
                <div class="min-w-[14rem] max-w-xs grow">
                    <label class="{{ $labelClass }}">Categoria</label>
                    <select name="category_id" class="w-full {{ $inputClass }}">
                        <option value="">Todas</option>
                        @foreach($filterCategories as $fc)
                            <option value="{{ $fc->id }}" @selected(($filters['category_id'] ?? '') == $fc->id)>{{ $fc->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="min-w-[11rem]">
                <label class="{{ $labelClass }}">Origem</label>
                <select name="source" class="w-full {{ $inputClass }}">
                    <option value="">Todas</option>
                    <option value="manual" @selected(($filters['source'] ?? '') === 'manual')>Manual</option>
                    <option value="gateway" @selected(($filters['source'] ?? '') === 'gateway')>Gateway (online)</option>
                    <option value="adjustment" @selected(($filters['source'] ?? '') === 'adjustment')>Ajuste</option>
                </select>
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
                        <th class="px-5 py-3.5">Data</th>
                        <th class="px-5 py-3.5">Categoria</th>
                        <th class="px-5 py-3.5">Âmbito</th>
                        <th class="px-5 py-3.5">Igreja</th>
                        <th class="px-5 py-3.5">Tipo</th>
                        <th class="px-5 py-3.5">Origem</th>
                        <th class="px-5 py-3.5 text-right">Valor</th>
                        <th class="px-5 py-3.5 text-right w-36"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse($transactions as $t)
                        <tr class="transition hover:bg-emerald-50/40 dark:hover:bg-slate-900/50">
                            <td class="whitespace-nowrap px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $t->occurred_on->format('d/m/Y') }}</td>
                            <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $t->category?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">
                                <span class="inline-flex rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-800 dark:bg-slate-900/80 dark:text-slate-200">{{ $t->scopeLabel() }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $t->church?->name ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex rounded-lg px-2.5 py-1 text-xs font-bold {{ $t->direction === 'in' ? 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100' : 'bg-rose-100 text-rose-900 dark:bg-rose-900/40 dark:text-rose-100' }}">
                                    {{ $t->direction === 'in' ? 'Receita' : 'Despesa' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">
                                @if($t->isFromGateway())
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-cyan-100 px-2 py-0.5 text-[11px] font-bold text-cyan-900 dark:bg-cyan-900/40 dark:text-cyan-100">
                                        <x-icon name="credit-card" class="h-3.5 w-3.5" style="duotone" />
                                        Gateway
                                    </span>
                                @else
                                    <span class="text-xs">{{ \Modules\Financeiro\App\Models\FinTransaction::sourceLabel($t->source) }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format((float) $t->amount, 2, ',', '.') }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @can('update', $t)
                                        <a href="{{ route('diretoria.financeiro.transactions.edit', $t) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Editar</a>
                                    @endcan
                                    @can('delete', $t)
                                        <form action="{{ route('diretoria.financeiro.transactions.destroy', $t) }}" method="post" class="inline" onsubmit="return confirm('Remover este lançamento?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:underline dark:text-rose-400">Excluir</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div class="mx-auto flex max-w-md flex-col items-center">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-gray-500">
                                        <x-icon name="list" class="h-7 w-7" style="duotone" />
                                    </span>
                                    <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhum lançamento encontrado</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste o período ou os filtros, ou registe um novo movimento.</p>
                                    @can('create', \Modules\Financeiro\App\Models\FinTransaction::class)
                                        <a href="{{ route('diretoria.financeiro.transactions.create') }}" class="mt-5 inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-bold text-white hover:bg-emerald-700">
                                            <x-icon name="plus" class="h-4 w-4" style="solid" />
                                            Criar lançamento
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="px-1">{{ $transactions->links() }}</div>
</div>
@endsection
