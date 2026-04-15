<?php

namespace Modules\Secretaria\App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Secretaria\App\Models\Convocation;
use Modules\Secretaria\App\Models\Meeting;
use Modules\Secretaria\App\Models\Minute;

/**
 * Hooks opcionais entre Secretaria e outros módulos (flags em config secretaria.integrations).
 */
final class SecretariaIntegrationBus
{
    public static function syncMeetingCalendar(Meeting $meeting, ?User $actor): void
    {
        if (! (bool) config('secretaria.integrations.calendario_sync_meetings', false)) {
            return;
        }

        if (! module_enabled('Calendario') || ! Schema::hasTable('eventos')) {
            return;
        }

        $visibility = (string) config('secretaria.integrations.calendario_meeting_visibility', \Modules\Calendario\App\Models\CalendarEvent::VIS_DIRETORIA);
        $title = trim(($meeting->title ?: '').' ('.$meeting->type.')') ?: 'Reunião JUBAF';
        $ends = $meeting->ends_at ?? $meeting->starts_at->copy()->addHours(2);

        $payload = [
            'title' => $title,
            'description' => $meeting->notes,
            'start_date' => $meeting->starts_at,
            'end_date' => $ends,
            'all_day' => false,
            'visibility' => $visibility,
            'type' => 'secretaria_reuniao',
            'location' => $meeting->location,
            'church_id' => null,
            'registration_open' => false,
            'created_by' => $actor?->id,
        ];

        if ($meeting->evento_id) {
            $event = \Modules\Calendario\App\Models\CalendarEvent::query()->find($meeting->evento_id);
            if ($event) {
                $event->update($payload);

                return;
            }
        }

        $event = \Modules\Calendario\App\Models\CalendarEvent::query()->create($payload);
        $meeting->forceFill(['evento_id' => $event->id])->saveQuietly();
    }

    public static function deleteMeetingCalendarEvent(Meeting $meeting): void
    {
        if (! (bool) config('secretaria.integrations.calendario_delete_event_on_meeting_delete', true)) {
            return;
        }

        if (! $meeting->evento_id || ! Schema::hasTable('eventos')) {
            return;
        }

        \Modules\Calendario\App\Models\CalendarEvent::query()->whereKey($meeting->evento_id)->delete();
    }

    public static function afterMinutePublished(Minute $minute, User $publisher): void
    {
        if (! (bool) config('secretaria.integrations.aviso_draft_on_minute_publish', false)) {
            return;
        }

        if (! module_enabled('Avisos') || ! Schema::hasTable('avisos')) {
            return;
        }

        if (! $publisher->can('avisos.create')) {
            return;
        }

        $snippet = Str::limit(strip_tags((string) $minute->content), 240);

        \Modules\Avisos\App\Models\Aviso::query()->create([
            'titulo' => '[Rascunho] Ata publicada: '.$minute->title,
            'descricao' => 'Gerado automaticamente pela Secretaria. Revise antes de ativar.',
            'conteudo' => '<p>Nova ata publicada.</p><p><strong>'.$minute->title.'</strong></p><p>'.$snippet.'</p>',
            'tipo' => 'info',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => false,
            'destacar' => false,
            'user_id' => $publisher->id,
        ]);
    }

    public static function afterConvocationPublished(Convocation $convocation, User $publisher): void
    {
        if (! (bool) config('secretaria.integrations.aviso_draft_on_convocation_publish', false)) {
            return;
        }

        if (! module_enabled('Avisos') || ! Schema::hasTable('avisos')) {
            return;
        }

        if (! $publisher->can('avisos.create')) {
            return;
        }

        $snippet = Str::limit(strip_tags((string) $convocation->body), 240);

        \Modules\Avisos\App\Models\Aviso::query()->create([
            'titulo' => '[Rascunho] Convocatória: '.$convocation->title,
            'descricao' => 'Gerado automaticamente pela Secretaria.',
            'conteudo' => '<p>Convocatória publicada para '.$convocation->assembly_at->format('d/m/Y H:i').'.</p><p>'.$snippet.'</p>',
            'tipo' => 'warning',
            'posicao' => 'topo',
            'estilo' => 'banner',
            'ativo' => false,
            'destacar' => false,
            'user_id' => $publisher->id,
        ]);
    }
}
