@extends('layouts.public-site')

@section('title', 'Pagamento')

@section('content')
<div class="mx-auto max-w-lg px-4 py-16">
    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg dark:border-slate-700 dark:bg-slate-800">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Pagamento</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Referência: <span class="font-mono text-xs">{{ $payment->uuid }}</span></p>
        <p class="mt-4 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-400">R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}</p>
        <p class="mt-2 text-sm text-gray-500">Estado: <strong>{{ $payment->statusLabel() }}</strong></p>
        @if($payment->checkout_url && $payment->status === \Modules\Gateway\App\Models\GatewayPayment::STATUS_PENDING)
            <a href="{{ $payment->checkout_url }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                Continuar para pagamento
            </a>
        @endif
    </div>
</div>
@endsection
