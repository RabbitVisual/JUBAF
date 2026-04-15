<?php

namespace Modules\Notificacoes\App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Secretaria\App\Models\Minute;

class AtaPublishedWhatsAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Minute $minute,
        private readonly string $pdfUrl
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['whatsapp'];
    }

    public function toWhatsapp(object $notifiable): array
    {
        $summary = trim((string) ($this->minute->executive_summary ?: strip_tags((string) $this->minute->content)));
        $summary = mb_substr($summary, 0, 280);

        $message = "Ata publicada: {$this->minute->title}\n\n{$summary}\n\nPDF: {$this->pdfUrl}";

        return [
            'phone' => $notifiable->phone ?? null,
            'message' => $message,
        ];
    }
}
