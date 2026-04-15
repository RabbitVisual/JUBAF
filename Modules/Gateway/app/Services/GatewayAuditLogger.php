<?php

namespace Modules\Gateway\App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Gateway\App\Models\GatewayAuditLog;
use Modules\Gateway\App\Models\GatewayPayment;

class GatewayAuditLogger
{
    public function log(string $action, ?GatewayPayment $payment = null, ?Model $auditable = null, array $properties = []): void
    {
        GatewayAuditLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $auditable ? $auditable->getMorphClass() : null,
            'auditable_id' => $auditable?->getKey(),
            'gateway_payment_id' => $payment?->id,
            'properties' => $properties ?: null,
            'ip_address' => request()?->ip(),
        ]);
    }
}
