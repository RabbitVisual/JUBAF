<?php

namespace Modules\Calendario\App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;
use Modules\Notificacoes\App\Models\Notificacao;

class SendCalendarEventRemindersCommand extends Command
{
    protected $signature = 'calendario:send-reminders';

    protected $description = 'Envia lembretes (notificações internas) para inscrições confirmadas no dia anterior ao evento.';

    public function handle(): int
    {
        if (! module_enabled('Notificacoes') || ! class_exists(Notificacao::class)) {
            $this->warn('Módulo Notificacoes inactivo — nada a fazer.');

            return self::SUCCESS;
        }

        $tomorrow = Carbon::tomorrow();
        $events = CalendarEvent::query()
            ->where('status', CalendarEvent::STATUS_PUBLISHED)
            ->whereDate('start_date', $tomorrow->toDateString())
            ->get();

        $count = 0;
        foreach ($events as $event) {
            $regs = CalendarRegistration::query()
                ->where('event_id', $event->id)
                ->where('status', CalendarRegistration::STATUS_CONFIRMED)
                ->with('user')
                ->get();

            foreach ($regs as $reg) {
                if (! $reg->user_id) {
                    continue;
                }
                Notificacao::query()->create([
                    'user_id' => $reg->user_id,
                    'title' => 'Lembrete: '.$event->title,
                    'message' => 'O evento é amanhã ('.$event->start_date->format('d/m H:i').'). '.($event->location ? 'Local: '.$event->location.'.' : ''),
                    'is_read' => false,
                    'type' => 'calendario.event_reminder',
                    'module_source' => 'Calendario',
                    'data' => ['evento_id' => $event->id],
                    'panel' => 'jovens',
                ]);
                $count++;
            }
        }

        $this->info("Lembretes criados: {$count}.");

        return self::SUCCESS;
    }
}
