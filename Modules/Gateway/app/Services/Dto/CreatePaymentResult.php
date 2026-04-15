<?php

namespace Modules\Gateway\App\Services\Dto;

final class CreatePaymentResult
{
    public function __construct(
        public readonly ?string $providerReference,
        public readonly ?string $checkoutUrl,
        public readonly ?string $clientSecret,
        public readonly ?array $rawResponse = null,
    ) {}
}
