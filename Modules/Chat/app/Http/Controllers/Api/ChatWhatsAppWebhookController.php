<?php

namespace Modules\Chat\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Chat\App\Services\ChatWhatsAppIngressService;

class ChatWhatsAppWebhookController extends Controller
{
    public function __invoke(Request $request, ChatWhatsAppIngressService $ingress): JsonResponse
    {
        $secret = (string) config('notificacoes.evolution.webhook_secret', '');

        if ($secret !== '') {
            $hdr = (string) $request->header('X-Webhook-Token', '');
            $bodyToken = (string) $request->input('token', '');
            if (! hash_equals($secret, $hdr) && ! hash_equals($secret, $bodyToken)) {
                abort(403);
            }
        } elseif (! app()->environment(['local', 'testing'])) {
            abort(403, 'Webhook secret não configurado.');
        }

        $parsed = $ingress->parseInbound($request);
        if ($parsed === null) {
            return response()->json(['success' => false, 'message' => 'Payload inválido.'], 422);
        }

        $ingress->handleInbound($parsed['from'], $parsed['body']);

        return response()->json(['success' => true]);
    }
}
