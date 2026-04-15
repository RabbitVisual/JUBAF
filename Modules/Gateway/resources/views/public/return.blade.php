@extends('layouts.public-site')

@section('title', 'Resultado do pagamento')

@section('content')
<div class="mx-auto max-w-lg px-4 py-16">
    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg dark:border-slate-700 dark:bg-slate-800">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Estado do pagamento</h1>
        <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Referência: <span class="font-mono">{{ $payment->uuid }}</span></p>
        <p class="mt-4 text-lg font-semibold">{{ $payment->statusLabel() }}</p>
        @if($payment->status === \Modules\Gateway\App\Models\GatewayPayment::STATUS_PAID)
            <p class="mt-2 text-sm text-emerald-700">Obrigado. O teu pagamento foi registado.</p>
        @elseif(request()->boolean('cancelled'))
            <p class="mt-2 text-sm text-amber-700">Pagamento cancelado. Podes tentar novamente pelo calendário.</p>
        @else
            <p class="mt-2 text-sm text-gray-500">Se acabaste de pagar, o estado pode demorar alguns segundos a actualizar.</p>
        @endif
    </div>
</div>
@endsection
