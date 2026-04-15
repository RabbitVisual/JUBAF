@extends($layout)

@section('title', 'Cobrança #'.$payment->id)

@section('content')
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('gateway::paineldiretoria.partials.subnav', ['active' => 'payments'])

    <a href="{{ route('diretoria.gateway.payments.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">
        <x-icon name="arrow-left" class="h-4 w-4" /> Voltar
    </a>

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-600 dark:bg-slate-800">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Cobrança #{{ $payment->id }}</h1>
        <dl class="mt-6 grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div>
                <dt class="text-xs font-bold uppercase text-gray-500">UUID</dt>
                <dd class="mt-1 font-mono text-xs">{{ $payment->uuid }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase text-gray-500">Estado</dt>
                <dd class="mt-1 font-semibold">{{ $payment->statusLabel() }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase text-gray-500">Valor</dt>
                <dd class="mt-1 tabular-nums text-lg font-bold">R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase text-gray-500">Driver</dt>
                <dd class="mt-1">{{ $payment->driver }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase text-gray-500">Referência PSP</dt>
                <dd class="mt-1 font-mono text-xs break-all">{{ $payment->provider_reference ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase text-gray-500">Lançamento Financeiro</dt>
                <dd class="mt-1">
                    @if($payment->fin_transaction_id && Route::has('diretoria.financeiro.transactions.edit'))
                        <a href="{{ route('diretoria.financeiro.transactions.edit', $payment->fin_transaction_id) }}" class="font-semibold text-emerald-700 hover:underline dark:text-emerald-400">
                            #{{ $payment->fin_transaction_id }}
                        </a>
                    @else
                        —
                    @endif
                </dd>
            </div>
        </dl>
        @if($payment->raw_last_payload)
            <details class="mt-6 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-slate-700 dark:bg-slate-900/50">
                <summary class="cursor-pointer text-sm font-bold text-gray-700 dark:text-gray-200">Payload (auditoria)</summary>
                <pre class="mt-3 max-h-64 overflow-auto text-xs text-gray-600 dark:text-gray-300">{{ json_encode($payment->raw_last_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </details>
        @endif
    </div>
</div>
@endsection
