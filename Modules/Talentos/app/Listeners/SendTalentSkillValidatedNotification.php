<?php

namespace Modules\Talentos\App\Listeners;

use Modules\Notificacoes\App\Services\NotificacaoService;
use Modules\Talentos\App\Events\TalentSkillValidated;

class SendTalentSkillValidatedNotification
{
    public function handle(TalentSkillValidated $event): void
    {
        if (! module_enabled('Notificacoes')) {
            return;
        }

        app(NotificacaoService::class)->sendToUser(
            userId: $event->youth->id,
            type: 'success',
            title: 'Competência validada',
            message: 'O teu talento em «'.$event->skill->name.'» foi validado pelo líder da tua igreja. Continua a servir com dedicação!',
            actionUrl: route('jovens.talentos.profile.edit'),
            data: [
                'talent_skill_id' => $event->skill->id,
                'validator_id' => $event->validatedBy->id,
            ],
            moduleSource: 'Talentos',
            entityType: 'talent_skill',
            entityId: $event->skill->id,
            sendEmail: false,
            panel: 'jovens'
        );
    }
}
