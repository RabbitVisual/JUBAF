@extends($layout)

@section('title', 'Cotas por igreja')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Pendente',
        'paid' => 'Pago',
        'waived' => 'Isento',
        'cancelled' => 'Cancelado',
    ];
@endphp

<div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
    @include('financeiro::paineldiretoria.partials.subnav', ['active' => 'obligations'])

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cotas (ano associativo)</h1>
            <p class="mt-1 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                Uma linha por igreja e por ano. Pagamentos online podem marcar a cota como paga quando o pagamento é associado a este registo.
            </p>
        </div>
        <a href="{{ route($routePrefix.'.dashboard') }}" class="shrink-0 text-sm font-medium text-indigo-600 dark:text-indigo-400">Voltar ao resumo</a>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Ano em destaque</p>
            <p class="mt-1 text-2xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $obligationStats['year'] }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Estatísticas para este ano (conforme o seu acesso).</p>
        </div>
        <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4 dark:border-amber-900/40 dark:bg-amber-950/20">
            <p class="text-[11px] font-bold uppercase tracking-wide text-amber-900 dark:text-amber-300">Pendentes</p>
            <p class="mt-1 text-2xl font-bold tabular-nums text-amber-900 dark:text-amber-100">{{ $obligationStats['pending'] }}</p>
        </div>
        <div class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 dark:border-emerald-900/40 dark:bg-emerald-950/20">
            <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-900 dark:text-emerald-300">Pagas</p>
            <p class="mt-1 text-2xl font-bold tabular-nums text-emerald-900 dark:text-emerald-100">{{ $obligationStats['paid'] }}</p>
        </div>
    </div>

    @can('financeiro.obligations.manage')
        <div class="rounded-xl border border-indigo-200 bg-indigo-50/50 p-4 dark:border-indigo-900/50 dark:bg-indigo-950/30">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="min-w-0">
                    <h2 class="text-sm font-bold text-indigo-950 dark:text-indigo-100">Gerar cotas em falta</h2>
                    <p class="mt-1 text-sm text-indigo-900/90 dark:text-indigo-200/90">
                        Cria um registo de cota para cada igreja <strong>activa</strong> que ainda não tenha linha neste ano. Não duplica: se já existir, é ignorado.
                    </p>
                </div>
                <form method="post" action="{{ route('diretoria.financeiro.obligations.generate') }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    @if (filled($filters['year'] ?? null))
                        <input type="hidden" name="year" value="{{ $filters['year'] }}">
                    @endif
                    @if (filled($filters['status'] ?? null))
                        <input type="hidden" name="status" value="{{ $filters['status'] }}">
                    @endif
                    <div>
                        <label for="assoc_start_year" class="mb-1 block text-xs font-medium text-indigo-900 dark:text-indigo-200">Ano de início associativo</label>
                        <input id="assoc_start_year" type="number" name="assoc_start_year" value="{{ old('assoc_start_year', $defaultAssocYear) }}" min="2000" max="2099"
                            class="w-36 rounded-lg border border-indigo-200 bg-white px-3 py-2 text-sm dark:border-indigo-800 dark:bg-slate-900">
                    </div>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Gerar agora
                    </button>
                </form>
            </div>
        </div>
    @endcan

    <form method="get" class="flex flex-wrap items-end gap-3 rounded-xl border border-gray-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800">
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Ano associativo (início)</label>
            <input type="number" name="year" value="{{ $filters['year'] ?? '' }}" min="2000" max="2099" placeholder="ex. {{ $defaultAssocYear }}"
                class="w-36 rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-gray-500 dark:text-gray-400">Estado</label>
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                <option value="">Todos</option>
                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pendente</option>
                <option value="paid" @selected(($filters['status'] ?? '') === 'paid')>Pago</option>
                <option value="waived" @selected(($filters['status'] ?? '') === 'waived')>Isento</option>
                <option value="cancelled" @selected(($filters['status'] ?? '') === 'cancelled')>Cancelado</option>
            </select>
        </div>
        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Filtrar lista</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
            <thead class="bg-gray-50 dark:bg-slate-900/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Igreja</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Ano</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Valor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Estado</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Lançamento</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($obligations as $ob)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-900/40">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $ob->church?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm tabular-nums text-gray-700 dark:text-gray-300">{{ $ob->assoc_start_year }}</td>
                        <td class="px-4 py-3 text-right text-sm tabular-nums text-gray-900 dark:text-white">R$ {{ number_format((float) $ob->amount, 2, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $st = $ob->status;
                                $badge = $st === 'paid'
                                    ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200'
                                    : ($st === 'pending'
                                        ? 'bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-100'
                                        : ($st === 'waived'
                                            ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300'
                                            : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'));
                            @endphp
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $badge }}">{{ $statusLabels[$st] ?? $st }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                            @if($ob->fin_transaction_id)
                                #{{ $ob->fin_transaction_id }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                            @if (filled($filters['year'] ?? null) || filled($filters['status'] ?? null))
                                Nenhum registo com estes filtros. Ajuste o ano ou o estado.
                            @else
                                Ainda não há cotas registadas para o período visível. Se tiver permissão, use «Gerar cotas em falta» acima.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-center">
        {{ $obligations->links() }}
    </div>
</div>
@endsection
