<?php

namespace Modules\Notificacoes\App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Igrejas\App\Events\IgrejaAtualizada;
use Modules\Igrejas\App\Models\Church;
use Modules\Notificacoes\App\Services\NotificacaoService;

/**
 * Alerta líderes locais quando a situação CRM da igreja exige atenção (inativa / inadimplente).
 */
class SendChurchCrmAlerts implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        protected NotificacaoService $notificacoes
    ) {}

    public function handle(IgrejaAtualizada $event): void
    {
        if (! function_exists('module_enabled') || ! module_enabled('Notificacoes')) {
            return;
        }

        $church = $event->church->fresh();
        if (! $church) {
            return;
        }

        if (! in_array($church->crm_status, [Church::CRM_INATIVA, Church::CRM_INADIMPLENTE], true)) {
            return;
        }

        if ($event->originalCrmStatus === $church->crm_status) {
            return;
        }

        $label = match ($church->crm_status) {
            Church::CRM_INATIVA => 'inativa',
            Church::CRM_INADIMPLENTE => 'inadimplente',
            default => $church->crm_status,
        };

        $title = 'Situação CRM da congregação';
        $message = 'A situação de «'.$church->displayName().'» foi atualizada para «'.$label.'». Verifique com a Diretoria JUBAF se necessário.';

        $ids = collect([$church->pastor_user_id, $church->unijovem_leader_user_id])
            ->filter()
            ->unique()
            ->values();

        $roleUserIds = $church->users()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['pastor', 'lider']))
            ->pluck('id');

        $recipientIds = $ids->merge($roleUserIds)->unique()->values();

        foreach ($recipientIds as $userId) {
            $this->notificacoes->sendToUser(
                userId: (int) $userId,
                type: 'alert',
                title: $title,
                message: $message,
                actionUrl: null,
                data: ['church_id' => $church->id, 'crm_status' => $church->crm_status],
                moduleSource: 'Igrejas',
                entityType: Church::class,
                entityId: $church->id,
                sendEmail: false,
                panel: 'lideres'
            );
        }
    }
}
