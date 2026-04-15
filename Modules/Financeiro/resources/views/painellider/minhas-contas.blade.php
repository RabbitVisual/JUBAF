@extends('painellider::components.layouts.app')

@section('title', 'Minhas contas JUBAF')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <span class="text-emerald-700 dark:text-emerald-300">Tesouraria</span>
@endsection

@section('content')
<div class="max-w-6xl space-y-8">
    <div class="relative overflow-hidden rounded-3xl border border-emerald-200/80 bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 p-6 text-white shadow-xl md:p-8">
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div class="flex items-start gap-4">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15">
                    <x-module-icon module="Financeiro" class="h-8 w-8 text-white" />
                </span>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-100/90">Transparência</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight md:text-3xl">Minhas contas JUBAF</h1>
                    <p class="mt-2 max-w-xl text-sm text-emerald-50/95">Cotas associativas e faturas mensais da(s) sua(s) congregação(ões). Para pagamentos online, utilize o link enviado pela tesouraria ou o checkout do Gateway quando disponível.</p>
                </div>
            </div>
        </div>
    </div>

    @if(empty($churchIds))
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-50">
            <p class="font-semibold">Nenhuma igreja vinculada ao seu perfil.</p>
            <p class="mt-1 text-sm opacity-90">Peça à diretoria para associar a sua congregação ao seu utilizador.</p>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Cotas anuais (obrigações)</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Por ciclo associativo (mar.–fev.).</p>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs font-bold uppercase text-slate-500 dark:border-slate-600 dark:text-slate-400">
                            <th class="py-2 pr-4">Ano</th>
                            <th class="py-2 pr-4">Igreja</th>
                            <th class="py-2 pr-4">Valor</th>
                            <th class="py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($obligations as $o)
                            <tr>
                                <td class="py-2.5 pr-4 font-medium text-slate-900 dark:text-white">{{ $o->assoc_start_year }}</td>
                                <td class="py-2.5 pr-4 text-slate-700 dark:text-slate-300">{{ $o->church?->name ?? '—' }}</td>
                                <td class="py-2.5 pr-4 tabular-nums">R$ {{ number_format((float) $o->amount, 2, ',', '.') }}</td>
                                <td class="py-2.5">
                                    <span class="inline-flex rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-800 dark:bg-slate-900 dark:text-slate-200">{{ $o->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 text-center text-slate-500">Sem obrigações listadas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Faturas mensais (cotas associativas)</h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Geradas automaticamente para igrejas activas. Inclua <code class="rounded bg-slate-100 px-1 text-xs dark:bg-slate-900">fin_quota_invoice_id</code> no pagamento Gateway quando aplicável.</p>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs font-bold uppercase text-slate-500 dark:border-slate-600 dark:text-slate-400">
                            <th class="py-2 pr-4">Mês</th>
                            <th class="py-2 pr-4">Igreja</th>
                            <th class="py-2 pr-4">Valor</th>
                            <th class="py-2 pr-4">Vencimento</th>
                            <th class="py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @forelse($invoices as $inv)
                            <tr>
                                <td class="py-2.5 pr-4 font-mono text-xs text-slate-800 dark:text-slate-200">{{ $inv->billing_month }}</td>
                                <td class="py-2.5 pr-4 text-slate-700 dark:text-slate-300">{{ $inv->church?->name ?? '—' }}</td>
                                <td class="py-2.5 pr-4 tabular-nums">R$ {{ number_format((float) $inv->amount, 2, ',', '.') }}</td>
                                <td class="py-2.5 pr-4">{{ $inv->due_on?->format('d/m/Y') ?? '—' }}</td>
                                <td class="py-2.5">
                                    <span class="inline-flex rounded-lg bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-800 dark:bg-slate-900 dark:text-slate-200">{{ $inv->status }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 text-center text-slate-500">Sem faturas mensais.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(!empty($checkoutHint))
            <p class="text-center text-xs text-slate-500 dark:text-slate-400">Pagamentos online: utilize o URL de checkout enviado pela tesouraria (Gateway).</p>
        @endif
    @endif
</div>
@endsection
