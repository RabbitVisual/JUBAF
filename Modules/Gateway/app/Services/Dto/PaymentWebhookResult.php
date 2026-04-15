<?php

namespace Modules\Gateway\App\Services\Dto;

final class PaymentWebhookResult
{
    public function __construct(
        public readonly ?string $providerReference,
        public readonly ?string $newStatus,
        public readonly ?string $failureReason = null,
        public readonly ?array $raw = null,
    ) {}
}
