@extends('layouts.public-site')

@section('title', 'Pagamento')

@section('content')
<div class="mx-auto max-w-lg px-4 py-16">
    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg dark:border-slate-700 dark:bg-slate-800">
        <h1 class="text-xl font-bold text-gray-900 dark:text-white">Pagamento</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Referência: <span class="font-mono text-xs">{{ $payment->uuid }}</span></p>
        <p class="mt-4 text-2xl font-bold tabular-nums text-emerald-700 dark:text-emerald-400">R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}</p>
        <p class="mt-2 text-sm text-gray-500">Estado: <strong>{{ $payment->statusLabel() }}</strong></p>
        @if($payment->qr_code_base64 && $payment->status === \Modules\Gateway\App\Models\GatewayPayment::STATUS_PENDING)
            <button type="button"
                data-modal-target="pix-checkout-modal"
                data-modal-toggle="pix-checkout-modal"
                class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-violet-600 px-4 py-3 text-sm font-bold text-white hover:bg-violet-700">
                Abrir checkout PIX
            </button>
        @endif
        @if($payment->checkout_url && $payment->status === \Modules\Gateway\App\Models\GatewayPayment::STATUS_PENDING)
            <a href="{{ $payment->checkout_url }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white hover:bg-emerald-700">
                Continuar para pagamento
            </a>
        @endif
    </div>
</div>

@if($payment->qr_code_base64 && $payment->status === \Modules\Gateway\App\Models\GatewayPayment::STATUS_PENDING)
<div id="pix-checkout-modal" tabindex="-1" aria-hidden="true" class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full items-center justify-center overflow-y-auto overflow-x-hidden p-4 md:inset-0">
    <div class="relative max-h-full w-full max-w-md">
        <div class="relative rounded-2xl bg-white shadow dark:bg-slate-900">
            <div class="flex items-start justify-between rounded-t border-b p-5 dark:border-slate-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Checkout transparente (PIX)</h3>
                <button type="button" class="ms-auto inline-flex h-8 w-8 items-center justify-center rounded-lg text-sm text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:hover:bg-slate-800 dark:hover:text-white" data-modal-hide="pix-checkout-modal">x</button>
            </div>
            <div class="space-y-4 p-6 text-center">
                <img class="mx-auto h-56 w-56 rounded-xl border border-slate-200 p-2" src="data:image/png;base64,{{ $payment->qr_code_base64 }}" alt="QR Code PIX">
                <p class="text-sm text-gray-500 dark:text-gray-300">Expira em <span id="pix-countdown" class="font-semibold text-violet-700">30:00</span></p>
                @if($payment->ticket_url)
                    <a href="{{ $payment->ticket_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex w-full items-center justify-center rounded-xl bg-violet-600 px-4 py-3 text-sm font-bold text-white hover:bg-violet-700">Abrir detalhe do pagamento</a>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
(() => {
    let remaining = 30 * 60;
    const target = document.getElementById('pix-countdown');
    if (!target) return;
    setInterval(() => {
        if (remaining <= 0) return;
        remaining--;
        const m = String(Math.floor(remaining / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        target.textContent = `${m}:${s}`;
    }, 1000);
})();
</script>
@endif
@endsection
