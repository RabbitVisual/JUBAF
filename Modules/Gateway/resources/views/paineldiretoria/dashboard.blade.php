@extends($layout)

@section('title', 'Gateway de pagamentos')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('gateway::paineldiretoria.partials.subnav', ['active' => 'dashboard'])

    <div class="relative overflow-hidden rounded-3xl border border-emerald-200/60 bg-gradient-to-br from-white via-emerald-50/40 to-teal-50/30 p-6 shadow-lg dark:border-emerald-900/30 dark:from-slate-900 dark:via-emerald-950/20 dark:to-slate-900 sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700 dark:text-emerald-400">JUBAF · Gateway</p>
                <h1 class="mt-2 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/30">
                        <x-icon name="credit-card" class="h-7 w-7" style="duotone" />
                    </span>
                    Pagamentos online (PSP)
                </h1>
                <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
                    Cobranças via Cora (padrão), Mercado Pago, Stripe ou Pagar.me. O livro razão continua no módulo Financeiro.
                </p>
            </div>
            @can('gateway.payments.view')
                <a href="{{ route('diretoria.gateway.payments.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:bg-emerald-700">
                    Ver todas as cobranças
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase text-amber-700 dark:text-amber-400">Pendentes</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $pending }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase text-emerald-700 dark:text-emerald-400">Pagos (mês)</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $paidMonth }}</p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <p class="text-xs font-bold uppercase text-rose-700 dark:text-rose-400">Falhados</p>
            <p class="mt-2 text-3xl font-bold tabular-nums text-gray-900 dark:text-white">{{ $failed }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Últimas cobranças</h2>
            <ul class="mt-4 divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($recent as $p)
                    <li class="flex flex-wrap items-center justify-between gap-2 py-3 text-sm">
                        <span class="font-mono text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($p->uuid, 14) }}</span>
                        <span class="font-semibold tabular-nums">R$ {{ number_format((float) $p->amount, 2, ',', '.') }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs dark:bg-slate-700">{{ $p->statusLabel() }}</span>
                        @can('gateway.payments.view')
                            <a href="{{ route('diretoria.gateway.payments.show', $p) }}" class="text-xs font-bold text-emerald-700 hover:underline dark:text-emerald-400">Detalhe</a>
                        @endcan
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-500">Sem cobranças ainda.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-slate-600 dark:bg-slate-800">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Webhooks recentes</h2>
            <ul class="mt-4 divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($webhooks as $w)
                    <li class="py-3 text-sm">
                        <span class="font-mono text-xs">{{ $w->driver }}</span>
                        <span class="ml-2 rounded bg-slate-100 px-2 py-0.5 text-xs dark:bg-slate-700">{{ $w->processing_status }}</span>
                    </li>
                @empty
                    <li class="py-6 text-center text-sm text-gray-500">Sem eventos.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
