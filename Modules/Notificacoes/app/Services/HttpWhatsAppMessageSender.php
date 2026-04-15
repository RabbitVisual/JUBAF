<?php

namespace Modules\Notificacoes\App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Modules\Notificacoes\App\Contracts\WhatsAppMessageSender;

class HttpWhatsAppMessageSender implements WhatsAppMessageSender
{
    public function send(User $user, string $template, array $data = []): void
    {
        $phone = preg_replace('/\D+/', '', (string) ($user->phone ?? ''));
        if ($phone === '') {
            return;
        }

        $baseUrl = (string) config('notificacoes.whatsapp.base_url', '');
        $token = (string) config('notificacoes.whatsapp.token', '');
        if ($baseUrl === '' || $token === '') {
            Log::info('WhatsApp sender not configured, fallback log.', [
                'user_id' => $user->id,
                'template' => $template,
                'payload' => $data,
            ]);

            return;
        }

        Http::timeout(10)
            ->withToken($token)
            ->post(rtrim($baseUrl, '/').'/messages', [
                'to' => $phone,
                'template' => $template,
                'data' => $data,
            ])
            ->throw();
    }
}
