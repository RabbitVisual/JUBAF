<?php

namespace Modules\Gateway\App\Services\Contracts;

use Illuminate\Http\Request;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Services\Dto\CreatePaymentResult;
use Modules\Gateway\App\Services\Dto\PaymentWebhookResult;

interface PaymentProviderContract
{
    public function driverKey(): string;

    public function createPayment(GatewayPayment $payment, GatewayProviderAccount $account): CreatePaymentResult;

    /**
     * @return array{valid: bool, reason?: string}
     */
    public function verifyWebhookSignature(Request $request, GatewayProviderAccount $account): array;

    public function parseWebhook(Request $request, GatewayProviderAccount $account): ?PaymentWebhookResult;
}
