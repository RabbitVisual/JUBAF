<?php

namespace Modules\Gateway\App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Gateway\App\Jobs\ProcessGatewayWebhookJob;
use Modules\Gateway\App\Models\GatewayProviderAccount;
use Modules\Gateway\App\Models\GatewayWebhookEvent;
use Modules\Gateway\App\Services\ProviderRegistry;

class GatewayWebhookController extends Controller
{
    public function handle(Request $request, string $driver, ?GatewayProviderAccount $account = null): Response
    {
        $account ??= GatewayProviderAccount::query()
            ->where('driver', $driver)
            ->where('is_enabled', true)
            ->orderByDesc('is_default')
            ->first();

        if (! $account) {
            return response('Conta não encontrada', 404);
        }

        $registry = app(ProviderRegistry::class);
        $provider = $registry->get($driver);
        $verify = $provider->verifyWebhookSignature($request, $account);

        $event = GatewayWebhookEvent::query()->create([
            'gateway_provider_account_id' => $account->id,
            'driver' => $driver,
            'payload' => $request->getContent(),
            'headers' => $request->headers->all(),
            'signature_valid' => $verify['valid'] ?? false,
            'processing_status' => GatewayWebhookEvent::STATUS_PENDING,
        ]);

        if (! ($verify['valid'] ?? false)) {
            $event->update([
                'processing_status' => GatewayWebhookEvent::STATUS_FAILED,
                'error_message' => $verify['reason'] ?? 'Assinatura inválida',
            ]);

            return response('Invalid signature', 400);
        }

        ProcessGatewayWebhookJob::dispatch($event->id);

        return response('OK', 200);
    }
}
