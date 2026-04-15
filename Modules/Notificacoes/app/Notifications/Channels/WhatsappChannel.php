<?php

namespace Modules\Notificacoes\App\Notifications\Channels;

use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Modules\Notificacoes\App\Models\NotificacaoLog;

class WhatsappChannel
{
    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsapp')) {
            return;
        }

        $payload = (array) $notification->toWhatsapp($notifiable);
        $phone = $this->normalizePhone((string) ($payload['phone'] ?? ($notifiable->phone ?? '')));
        $message = (string) ($payload['message'] ?? '');

        if ($phone === '' || $message === '') {
            $this->log($notifiable, $message, 'failed', ['reason' => 'missing_phone_or_message']);

            return;
        }

        $endpoint = rtrim((string) config('notificacoes.evolution.url'), '/').'/message/sendText';
        $instance = (string) config('notificacoes.evolution.instance');
        $apiKey = (string) config('notificacoes.evolution.key');

        try {
            $response = Http::timeout(12)
                ->connectTimeout(8)
                ->retry(2, 500)
                ->withToken($apiKey)
                ->asJson()
                ->post($endpoint, [
                    'instance' => $instance,
                    'number' => $phone,
                    'text' => $message,
                ]);

            $status = $response->successful() ? 'sent' : 'failed';
            $this->log($notifiable, $message, $status, $response->json() ?: ['body' => $response->body()]);
        } catch (\Throwable $exception) {
            $this->log($notifiable, $message, 'failed', ['error' => $exception->getMessage()]);
        }
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return '';
        }

        if (! str_starts_with($digits, '244') && strlen($digits) <= 9) {
            $digits = '244'.$digits;
        }

        return $digits;
    }

    private function log(object $notifiable, string $message, string $status, array $payload): void
    {
        NotificacaoLog::query()->create([
            'user_id' => $notifiable instanceof User ? $notifiable->id : null,
            'channel' => 'whatsapp',
            'message' => $message,
            'status' => $status,
            'response_payload' => $payload,
        ]);
    }
}
