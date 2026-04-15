<?php

namespace Modules\Notificacoes\App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notificacoes\App\Notifications\AtaPublishedWhatsAppNotification;
use Modules\Secretaria\App\Events\AtaPublished;
use Modules\Secretaria\App\Services\AtaWhatsAppAudienceResolver;

class SendAtaSummaryToWhatsApp implements ShouldQueue
{
    public string $queue = 'notifications';

    public function __construct(
        private readonly AtaWhatsAppAudienceResolver $audienceResolver
    ) {
    }

    public function handle(AtaPublished $event): void
    {
        if (! module_enabled('Notificacoes')) {
            return;
        }

        $minute = $event->minute->refresh();
        $pdfUrl = route('diretoria.secretaria.atas.pdf', $minute, true);

        $notification = new AtaPublishedWhatsAppNotification($minute, $pdfUrl);

        $this->audienceResolver->resolve()
            ->each(fn ($user) => $user->notify($notification));
    }
}
