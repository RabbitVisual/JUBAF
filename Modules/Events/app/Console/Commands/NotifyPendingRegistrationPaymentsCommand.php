<?php

namespace Modules\Events\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Events\App\Models\EventRegistration;
use Modules\Notifications\App\Services\InAppNotificationService;

/**
 * Inscrições com pagamento pendente: aviso recorrente (no máximo 1 vez por 24h por inscrição).
 */
class NotifyPendingRegistrationPaymentsCommand extends Command
{
    protected $signature = 'events:notify-pending-registration-payments {--dry-run : Apenas listar, sem enviar}';

    protected $description = 'Notifica membros com inscrição pendente há mais de 24h (intervalo mínimo de 24h entre avisos).';

    public function handle(InAppNotificationService $notifier): int
    {
        $query = EventRegistration::query()
            ->with(['user', 'event'])
            ->where('status', EventRegistration::STATUS_PENDING)
            ->where('created_at', '<=', now()->subHours(24))
            ->where(function ($q): void {
                $q->whereNull('payment_reminder_sent_at')
                    ->orWhere('payment_reminder_sent_at', '<=', now()->subHours(24));
            });

        $notified = 0;

        foreach ($query->cursor() as $registration) {
            $user = $registration->user;
            if (! $user) {
                continue;
            }

            $eventTitle = $registration->event?->title ?? 'evento';

            if ($this->option('dry-run')) {
                $this->line("registration_id={$registration->id} user_id={$user->id} event=".($registration->event_id ?? '—'));

                continue;
            }

            $actionUrl = route('memberpanel.events.show-registration', $registration);

            $notifier->sendToUser(
                $user,
                'Pagamento pendente — '.$eventTitle,
                'Seu QR Code Pix expira em breve! Garanta sua vaga no evento.',
                [
                    'type' => 'warning',
                    'priority' => 'normal',
                    'action_url' => $actionUrl,
                    'action_text' => 'Ver inscrição',
                    'notification_type' => 'generic',
                    'broadcast' => true,
                ]
            );

            $registration->forceFill(['payment_reminder_sent_at' => now()])->save();
            $notified++;
        }

        if ($this->option('dry-run')) {
            $this->info('Dry-run concluído (nenhuma notificação enviada).');
        } else {
            $this->info("Notificações enviadas: {$notified}.");
        }

        return self::SUCCESS;
    }
}
