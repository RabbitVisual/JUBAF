<?php

namespace Modules\Secretaria\App\Listeners;

use Modules\Secretaria\App\Events\AtaPublished;
use Modules\Secretaria\App\Services\SecretariaIntegrationBus;
use Modules\Secretaria\App\Services\SecretariaNotificationDispatcher;

class DispatchMinutePublishedIntegrations
{
    public function handle(AtaPublished $event): void
    {
        $minute = $event->minute;

        if (config('secretaria.integrations.notificacoes_on_publish', true)) {
            SecretariaNotificationDispatcher::minutePublished($minute);
        }

        SecretariaIntegrationBus::afterMinutePublished($minute, $event->publisher);
    }
}
