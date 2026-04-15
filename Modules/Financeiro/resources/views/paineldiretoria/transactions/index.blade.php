@extends($layout)

@section('title', 'Lançamentos financeiros')

@section('content')
@php
    $hasFilters = filled($filters['from'] ?? null) || filled($filters['to'] ?? null) || filled($filters['direction'] ?? null) || filled($filters['church_id'] ?? null) || filled($filters['scope'] ?? null) || filled($filters['source'] ?? null) || filled($filters['category_id'] ?? null) || filled($filters['status'] ?? null);
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
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Livro da tesouraria</p>
            <h1 class="mt-1 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/25">
                    <x-icon name="list" class="h-5 w-5" style="duotone" />
                </span>
                Lançamentos
            </h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">Livro da tesouraria: receitas e despesas com data, categoria e âmbito <strong>regional (JUBAF)</strong> ou <strong>por igreja</strong>, alinhado ao estatuto e à transparência perante a assembleia.</p>
        </div>
        @can('create', \Modules\Financeiro\App\Models\FinTransaction::class)
            <x-ui.button variant="emerald" size="md" href="{{ route('diretoria.financeiro.transactions.create') }}" class="shrink-0 shadow-md shadow-emerald-600/25">
                <x-icon name="plus" class="h-4 w-4" style="solid" />
                Novo lançamento
            </x-ui.button>
        @endcan
    </div>

    <x-ui.card>
        <x-slot name="header">
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Filtros</h2>
                @if($hasFilters)
                    <a href="{{ route('diretoria.financeiro.transactions.index') }}" class="text-xs font-semibold text-indigo-700 hover:underline dark:text-indigo-400">Limpar filtros</a>
                @endif
            </div>
        </x-slot>

        <form method="get" class="flex flex-wrap items-end gap-4">
            <div class="min-w-[9rem]">
                <x-ui.input label="De" type="date" name="from" :value="$filters['from'] ?? ''" />
            </div>
            <div class="min-w-[9rem]">
                <x-ui.input label="Até" type="date" name="to" :value="$filters['to'] ?? ''" />
            </div>
            <div class="min-w-[10rem]">
                <x-ui.select label="Tipo" name="direction">
                    <option value="">Todos</option>
                    <option value="in" @selected(($filters['direction'] ?? '') === 'in')>Receita</option>
                    <option value="out" @selected(($filters['direction'] ?? '') === 'out')>Despesa</option>
                </x-ui.select>
            </div>
            <div class="min-w-[11rem]">
                <x-ui.select label="Âmbito" name="scope">
                    <option value="">Todos</option>
                    <option value="regional" @selected(($filters['scope'] ?? '') === 'regional')>Regional (JUBAF)</option>
                    <option value="igreja" @selected(($filters['scope'] ?? '') === 'igreja')>Por igreja</option>
                </x-ui.select>
            </div>
            @if($churches->isNotEmpty())
                <div class="min-w-[12rem] max-w-xs grow">
                    <x-ui.select label="Igreja" name="church_id">
                        <option value="">Todas</option>
                        @foreach($churches as $c)
                            <option value="{{ $c->id }}" @selected(($filters['church_id'] ?? '') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            @endif
            @if(isset($filterCategories) && $filterCategories->isNotEmpty())
                <div class="min-w-[14rem] max-w-xs grow">
                    <x-ui.select label="Categoria" name="category_id">
                        <option value="">Todas</option>
                        @foreach($filterCategories as $fc)
                            <option value="{{ $fc->id }}" @selected(($filters['category_id'] ?? '') == $fc->id)>{{ $fc->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>
            @endif
            <div class="min-w-[11rem]">
                <x-ui.select label="Origem" name="source">
                    <option value="">Todas</option>
                    <option value="manual" @selected(($filters['source'] ?? '') === 'manual')>Manual</option>
                    <option value="gateway" @selected(($filters['source'] ?? '') === 'gateway')>Gateway (online)</option>
                    <option value="adjustment" @selected(($filters['source'] ?? '') === 'adjustment')>Ajuste</option>
                </x-ui.select>
            </div>
            <div class="min-w-[10rem]">
                <x-ui.select label="Estado" name="status">
                    <option value="">Todos</option>
                    <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Pago</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pendente</option>
                    <option value="overdue" @selected(($filters['status'] ?? '') === 'overdue')>Atrasado</option>
                </x-ui.select>
            </div>
            <x-ui.button type="submit" variant="secondary" size="md" class="!bg-gray-900 !text-white hover:!bg-gray-800 dark:!bg-slate-700 dark:hover:!bg-slate-600">
                <x-icon name="filter" class="h-4 w-4 opacity-90" style="duotone" />
                Aplicar
            </x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.table>
        <x-slot name="head">
            <th class="px-5 py-3.5">Data</th>
            <th class="px-5 py-3.5">Estado</th>
            <th class="px-5 py-3.5">Categoria</th>
            <th class="px-5 py-3.5">Âmbito</th>
            <th class="px-5 py-3.5">Igreja</th>
            <th class="px-5 py-3.5">Tipo</th>
            <th class="px-5 py-3.5">Origem</th>
            <th class="px-5 py-3.5 text-right">Valor</th>
            <th class="px-5 py-3.5 text-right w-36"></th>
        </x-slot>

        @forelse($transactions as $t)
            <tr class="transition odd:bg-white even:bg-gray-50/60 hover:bg-indigo-50/50 dark:odd:bg-slate-800 dark:even:bg-slate-900/40 dark:hover:bg-slate-900/60">
                <td class="whitespace-nowrap px-5 py-3.5 text-gray-700 dark:text-gray-300">{{ $t->occurred_on->format('d/m/Y') }}</td>
                <td class="px-5 py-3.5">
                    @php
                        $st = $t->status;
                        $tone = match ($st) {
                            'paid' => 'success',
                            'pending' => 'warning',
                            'overdue' => 'danger',
                            default => 'neutral',
                        };
                    @endphp
                    <x-ui.badge :tone="$tone">{{ \Modules\Financeiro\App\Models\FinTransaction::statusLabel($st) }}</x-ui.badge>
                </td>
                <td class="px-5 py-3.5 font-medium text-gray-900 dark:text-white">{{ $t->category?->name ?? '—' }}</td>
                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">
                    <x-ui.badge tone="neutral">{{ $t->scopeLabel() }}</x-ui.badge>
                </td>
                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">{{ $t->church?->name ?? '—' }}</td>
                <td class="px-5 py-3.5">
                    <x-ui.badge :tone="$t->direction === 'in' ? 'success' : 'danger'">
                        {{ $t->direction === 'in' ? 'Receita' : 'Despesa' }}
                    </x-ui.badge>
                </td>
                <td class="px-5 py-3.5 text-gray-600 dark:text-gray-400">
                    @if($t->isFromGateway())
                        <x-ui.badge tone="info" class="inline-flex items-center gap-1">
                            <x-icon name="credit-card" class="h-3.5 w-3.5" style="duotone" />
                            Gateway
                        </x-ui.badge>
                    @else
                        <span class="text-xs">{{ \Modules\Financeiro\App\Models\FinTransaction::sourceLabel($t->source) }}</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-right font-semibold tabular-nums text-gray-900 dark:text-white">R$ {{ number_format((float) $t->amount, 2, ',', '.') }}</td>
                <td class="px-5 py-3.5 text-right">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        @can('update', $t)
                            <a href="{{ route('diretoria.financeiro.transactions.edit', $t) }}" class="text-xs font-bold text-indigo-700 hover:underline dark:text-indigo-400">Editar</a>
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
                <td colspan="9" class="px-5 py-16 text-center">
                    <div class="mx-auto flex max-w-md flex-col items-center">
                        <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-gray-500">
                            <x-icon name="list" class="h-7 w-7" style="duotone" />
                        </span>
                        <p class="mt-4 font-semibold text-gray-900 dark:text-white">Nenhum lançamento encontrado</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ajuste o período ou os filtros, ou registe um novo movimento.</p>
                        @can('create', \Modules\Financeiro\App\Models\FinTransaction::class)
                            <x-ui.button variant="emerald" size="md" href="{{ route('diretoria.financeiro.transactions.create') }}" class="mt-5">
                                <x-icon name="plus" class="h-4 w-4" style="solid" />
                                Criar lançamento
                            </x-ui.button>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    <div class="px-1">{{ $transactions->links() }}</div>
</div>
@endsection
