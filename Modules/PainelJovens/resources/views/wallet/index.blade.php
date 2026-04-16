@extends('paineljovens::layouts.jovens')

@section('title', 'Carteira')


@section('jovens_content')
    @php
        use Modules\Calendario\App\Models\CalendarRegistration;
        use Modules\Gateway\App\Models\GatewayPayment;

        $regLabels = [
            CalendarRegistration::STATUS_CONFIRMED => 'Confirmada',
            CalendarRegistration::STATUS_WAITLIST => 'Lista de espera',
            CalendarRegistration::STATUS_CANCELLED => 'Cancelada',
            CalendarRegistration::STATUS_PENDING_PAYMENT => 'Pagamento pendente',
        ];
    @endphp

    <x-ui.jovens::page-shell class="space-y-8 md:space-y-10">
        <header class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 text-white shadow-xl">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative flex flex-col gap-6 px-6 py-10 md:px-10 md:py-12 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-blue-200/90">Unijovem · Carteira</p>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight md:text-4xl">Os teus ingressos e comprovativos</h1>
                    <p class="mt-4 text-sm leading-relaxed text-blue-100/95 md:text-base">
                        Consulta o estado do pagamento, QR Code e ligações para bilhetes dos eventos em que estás inscrito(a).
                    </p>
                </div>
                @if (Route::has('jovens.eventos.index'))
                    <div class="w-full rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-md lg:max-w-xs">
                        <p class="text-sm font-medium text-blue-100">Queres inscrever-te noutro evento?</p>
                        <a href="{{ route('jovens.eventos.index') }}"
                            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl bg-white px-4 py-3 text-sm font-bold text-blue-900 shadow-lg transition-colors hover:bg-blue-50">
                            <x-icon name="calendar-days" class="h-4 w-4" style="duotone" />
                            Ver eventos
                        </a>
                    </div>
                @endif
            </div>
        </header>

        @if ($registrations->isEmpty())
            <x-ui.jovens::empty-state title="Ainda sem inscrições activas"
                description="Quando te inscreveres num evento da JUBAF, o comprovativo e o QR aparecem aqui."
                icon="ticket" />
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                @foreach ($registrations as $reg)
                    @php
                        $ev = $reg->event;
                        $gp = $reg->gatewayPayment;
                        $regLabel = $regLabels[$reg->status] ?? $reg->status;
                        $payVariant = match ($gp?->status) {
                            GatewayPayment::STATUS_PAID => 'success',
                            GatewayPayment::STATUS_PENDING => 'warning',
                            GatewayPayment::STATUS_FAILED => 'danger',
                            default => 'neutral',
                        };
                        $payLabel = $gp ? $gp->statusLabel() : match ($reg->payment_status) {
                            'pending' => 'Pagamento pendente',
                            'not_required' => 'Sem pagamento online',
                            default => $reg->payment_status ?? '—',
                        };
                        $qr = $gp?->qrCodeDataUri();
                        $checkout =
                            $reg->status === CalendarRegistration::STATUS_PENDING_PAYMENT && $gp && $gp->status === GatewayPayment::STATUS_PENDING
                                ? route('gateway.public.checkout', ['uuid' => $gp->uuid])
                                : null;
                    @endphp
                    <x-ui.jovens::ticket-card :event-title="$ev?->title ?? 'Evento'"
                        :event-starts-at="$ev?->starts_at?->timezone(config('app.timezone'))->translatedFormat('d M Y, H:i')"
                        :registration-status="$regLabel" :payment-status="$payLabel" :payment-variant="$payVariant"
                        :gateway-label="$gp ? 'Ref. pagamento: '.$gp->uuid : null" :qr-src="$qr"
                        :ticket-url="$gp?->ticket_url" :checkout-route="$checkout" />
                @endforeach
            </div>
        @endif

        @if (Route::has('jovens.eventos.index') && $registrations->isNotEmpty())
            <div class="text-center">
                <a href="{{ route('jovens.eventos.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:underline dark:text-blue-300">
                    <x-icon name="calendar-days" class="h-4 w-4" style="duotone" />
                    Ver todos os eventos
                </a>
            </div>
        @endif
    </x-ui.jovens::page-shell>
@endsection
