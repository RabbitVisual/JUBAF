@props([
    'eventTitle',
    'eventStartsAt' => null,
    'registrationStatus' => null,
    'paymentStatus' => null,
    'paymentVariant' => 'neutral',
    'gatewayLabel' => null,
    'qrSrc' => null,
    'ticketUrl' => null,
    'checkoutRoute' => null,
])

<x-ui.card class="overflow-hidden border-gray-200 dark:border-gray-700">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0 flex-1">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">{{ $eventTitle }}</h3>
            @if ($eventStartsAt)
                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">{{ $eventStartsAt }}</p>
            @endif
            <div class="mt-3 flex flex-wrap gap-2">
                @if ($registrationStatus)
                    <x-ui.jovens::status-pill :label="$registrationStatus" variant="info" />
                @endif
                @if ($paymentStatus)
                    <x-ui.jovens::status-pill :label="$paymentStatus" :variant="$paymentVariant" />
                @endif
                @if ($gatewayLabel)
                    <span class="self-center text-xs text-gray-500 dark:text-gray-400">{{ $gatewayLabel }}</span>
                @endif
            </div>
        </div>
        @if ($qrSrc)
            <div class="shrink-0 rounded-lg border border-gray-200 bg-white p-2 dark:border-gray-600 dark:bg-gray-900">
                <img src="{{ $qrSrc }}" alt="" class="h-28 w-28 object-contain" width="112" height="112" loading="lazy" />
            </div>
        @endif
    </div>
    <div class="mt-4 flex flex-wrap gap-2">
        @if ($ticketUrl)
            <a href="{{ $ticketUrl }}" target="_blank" rel="noopener noreferrer"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
                <x-icon name="arrow-up-right-from-square" class="h-4 w-4" style="duotone" />
                Bilhete / comprovativo
            </a>
        @endif
        @if ($checkoutRoute)
            <a href="{{ $checkoutRoute }}"
                class="inline-flex items-center gap-2 rounded-xl border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-950 transition hover:bg-amber-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-100 dark:focus-visible:ring-offset-gray-900">
                <x-icon name="credit-card" class="h-4 w-4" style="duotone" />
                Completar pagamento
            </a>
        @endif
    </div>
</x-ui.card>
