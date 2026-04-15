<?php

namespace Modules\Avisos\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Modules\Avisos\App\Models\Aviso;

class UrgentAvisoWhatsAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Aviso $aviso
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    /**
     * @return array{phone: string, message: string}
     */
    public function toWhatsapp(object $notifiable): array
    {
        $phone = '';
        if (method_exists($notifiable, 'getAttribute')) {
            $phone = (string) ($notifiable->getAttribute('phone') ?? '');
        }

        $title = Str::limit(strip_tags((string) $this->aviso->titulo), 120);
        $url = route('avisos.show', $this->aviso);
        $message = "[JUBAF] URGENTE: {$title}\n\nVer comunicado: {$url}";

        return [
            'phone' => $phone,
            'message' => $message,
        ];
    }
}
