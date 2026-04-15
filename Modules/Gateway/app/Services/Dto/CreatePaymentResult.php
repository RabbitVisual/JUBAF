<?php

namespace Modules\Gateway\App\Services\Dto;

final class CreatePaymentResult
{
    public function __construct(
        public readonly ?string $providerReference,
        public readonly ?string $checkoutUrl,
        public readonly ?string $clientSecret,
        public readonly ?string $paymentMethod = null,
        public readonly ?string $qrCodeBase64 = null,
        public readonly ?string $ticketUrl = null,
        public readonly ?string $expiresAt = null,
        public readonly ?array $rawResponse = null,
    ) {}
}
