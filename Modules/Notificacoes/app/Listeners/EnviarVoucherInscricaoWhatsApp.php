<?php

namespace Modules\Notificacoes\App\Listeners;

use Modules\Calendario\App\Events\InscricaoConfirmada;
use Modules\Notificacoes\App\Contracts\WhatsAppMessageSender;
use Modules\Notificacoes\App\Services\NotificacaoService;

class EnviarVoucherInscricaoWhatsApp
{
    public function __construct(
        private readonly WhatsAppMessageSender $whatsAppSender,
        private readonly NotificacaoService $notificacaoService,
    ) {}

    public function handle(InscricaoConfirmada $event): void
    {
        $inscricao = $event->inscricao;
        $user = $inscricao->user;
        $calendarEvent = $inscricao->event;
        if (! $user || ! $calendarEvent) {
            return;
        }

        $voucherUrl = route('gateway.public.checkout', ['uuid' => $event->payment->uuid]);
        $this->whatsAppSender->send($user, 'voucher_ingresso_digital', [
            'evento' => $calendarEvent->title,
            'data' => optional($calendarEvent->start_date)->format('d/m/Y H:i'),
            'local' => $calendarEvent->location,
            'voucher_url' => $voucherUrl,
            'checkin_token' => $inscricao->checkin_token,
        ]);

        $this->notificacaoService->sendToUser(
            userId: $user->id,
            type: 'success',
            title: 'Voucher do evento disponível',
            message: 'O teu ingresso digital foi enviado para o WhatsApp cadastrado.',
            actionUrl: $voucherUrl,
            data: ['inscricao_id' => $inscricao->id, 'payment_id' => $event->payment->id],
            moduleSource: 'Calendario',
            entityType: 'evento_inscricao',
            entityId: $inscricao->id,
            panel: 'jovens'
        );
    }
}
