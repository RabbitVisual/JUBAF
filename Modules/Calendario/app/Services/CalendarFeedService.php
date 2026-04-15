<?php

namespace Modules\Calendario\App\Services;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Schema;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Igrejas\App\Models\Church;

class CalendarFeedService
{
    public const CONTEXT_DIRETORIA = 'diretoria';

    public const CONTEXT_JOVENS = 'jovens';

    public const CONTEXT_LIDER = 'lider';

    public const CONTEXT_PUBLIC = 'public';

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fullCalendarEvents(
        CarbonInterface $rangeStart,
        CarbonInterface $rangeEnd,
        ?User $user,
        string $context = self::CONTEXT_DIRETORIA,
        ?int $churchId = null,
        bool $includeBirthdays = true,
        bool $includeChurchMilestones = true
    ): array {
        $out = [];

        $q = CalendarEvent::query()
            ->with(['church:id,name'])
            ->where(function ($w) use ($rangeStart, $rangeEnd) {
                $w->whereBetween('starts_at', [$rangeStart, $rangeEnd])
                    ->orWhere(function ($w2) use ($rangeStart, $rangeEnd) {
                        $w2->where('starts_at', '<', $rangeEnd)
                            ->where(function ($w3) use ($rangeStart) {
                                $w3->whereNull('ends_at')->orWhere('ends_at', '>', $rangeStart);
                            });
                    });
            });

        if ($churchId) {
            $q->where(function ($w) use ($churchId) {
                $w->whereNull('church_id')->orWhere('church_id', $churchId);
            });
        }

        if ($context === self::CONTEXT_PUBLIC) {
            $q->where('status', CalendarEvent::STATUS_PUBLISHED)
                ->where('visibility', CalendarEvent::VIS_PUBLIC);
        } elseif (in_array($context, [self::CONTEXT_JOVENS, self::CONTEXT_LIDER], true)) {
            $q->where('status', CalendarEvent::STATUS_PUBLISHED);
        }
        // diretoria: todos os status (rascunhos, aprovação, etc.)

        $events = $q->orderBy('starts_at')->get();

        foreach ($events as $event) {
            if ($user && ! $event->userCanView($user)) {
                continue;
            }
            if ($user && ! $event->churchScopeAllows($user)) {
                continue;
            }

            $color = $this->colorForType($event->type);
            $out[] = [
                'id' => 'event:'.$event->id,
                'title' => $event->title,
                'start' => $event->all_day
                    ? $event->starts_at->copy()->startOfDay()->toIso8601String()
                    : $event->starts_at->toIso8601String(),
                'end' => $event->all_day
                    ? ($event->ends_at ?? $event->starts_at)->copy()->endOfDay()->toIso8601String()
                    : ($event->ends_at ?? $event->starts_at->copy()->addHours(2))->toIso8601String(),
                'allDay' => (bool) $event->all_day,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'kind' => 'event',
                    'eventId' => $event->id,
                    'type' => $event->type,
                    'status' => $event->status,
                    'visibility' => $event->visibility,
                    'churchName' => $event->church?->name,
                ],
            ];
        }

        if ($includeBirthdays && $this->birthdaysAllowed($context, $user)) {
            $out = array_merge($out, $this->birthdayEvents($rangeStart, $rangeEnd, $context, $user));
        }

        if ($includeChurchMilestones && module_enabled('Igrejas')) {
            $out = array_merge($out, $this->churchAnniversaryEvents($rangeStart, $rangeEnd));
        }

        return $out;
    }

    protected function birthdaysAllowed(string $context, ?User $user): bool
    {
        if ($context === self::CONTEXT_PUBLIC) {
            return false;
        }

        return $user !== null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function birthdayEvents(
        CarbonInterface $rangeStart,
        CarbonInterface $rangeEnd,
        string $context,
        ?User $user
    ): array {
        if (! Schema::hasColumn('users', 'birth_date')) {
            return [];
        }

        $start = Carbon::parse($rangeStart)->startOfDay();
        $end = Carbon::parse($rangeEnd)->endOfDay();

        $users = User::query()
            ->whereNotNull('birth_date')
            ->where('active', true)
            ->get(['id', 'first_name', 'last_name', 'name', 'birth_date']);

        $items = [];
        foreach ($users as $u) {
            $bd = $u->birth_date;
            if (! $bd) {
                continue;
            }
            $occ = $this->nextBirthdayOccurrence(Carbon::parse($bd), $start, $end);
            if (! $occ) {
                continue;
            }

            $title = $this->formatBirthdayTitle($u, $context, $user);
            $color = '#7c3aed';
            $items[] = [
                'id' => 'birthday:user:'.$u->id.':'.$occ->year,
                'title' => $title,
                'start' => $occ->copy()->startOfDay()->toIso8601String(),
                'end' => $occ->copy()->endOfDay()->toIso8601String(),
                'allDay' => true,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'display' => 'block',
                'extendedProps' => [
                    'kind' => 'birthday',
                    'userId' => $u->id,
                ],
            ];
        }

        return $items;
    }

    protected function formatBirthdayTitle(User $subject, string $context, ?User $viewer): string
    {
        $canFull = $viewer && (
            $viewer->can('calendario.events.view')
            || (int) $viewer->id === (int) $subject->id
        );

        if ($canFull) {
            $name = trim(($subject->first_name ?? '').' '.($subject->last_name ?? ''));
            $name = $name !== '' ? $name : (string) $subject->name;

            return 'Aniversário: '.$name;
        }

        return 'Aniversário de jovem';
    }

    protected function nextBirthdayOccurrence(Carbon $birthDate, Carbon $rangeStart, Carbon $rangeEnd): ?Carbon
    {
        $month = (int) $birthDate->month;
        $day = (int) $birthDate->day;
        $year = (int) $rangeStart->year;

        for ($y = $year; $y <= (int) $rangeEnd->year + 1; $y++) {
            try {
                $occ = Carbon::create($y, $month, $day, 0, 0, 0, $rangeStart->timezone);
            } catch (\Throwable) {
                continue;
            }
            if ($occ->betweenIncluded($rangeStart, $rangeEnd)) {
                return $occ;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function churchAnniversaryEvents(CarbonInterface $rangeStart, CarbonInterface $rangeEnd): array
    {
        if (! Schema::hasColumn('igrejas_churches', 'foundation_date')) {
            return [];
        }

        $churches = Church::query()
            ->whereNotNull('foundation_date')
            ->get(['id', 'name', 'foundation_date']);

        $start = Carbon::parse($rangeStart)->startOfDay();
        $end = Carbon::parse($rangeEnd)->endOfDay();

        $items = [];
        foreach ($churches as $ch) {
            $fd = $ch->foundation_date;
            if (! $fd) {
                continue;
            }
            $fd = Carbon::parse($fd);
            $occ = $this->nextBirthdayOccurrence($fd, $start, $end);
            if (! $occ) {
                continue;
            }
            $years = $occ->year - (int) $fd->year;
            if ($years < 1) {
                continue;
            }
            $color = '#0d9488';
            $items[] = [
                'id' => 'church-milestone:'.$ch->id.':'.$occ->year,
                'title' => 'Aniversário da igreja: '.$ch->name.' ('.$years.' anos)',
                'start' => $occ->copy()->startOfDay()->toIso8601String(),
                'end' => $occ->copy()->endOfDay()->toIso8601String(),
                'allDay' => true,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'kind' => 'church_milestone',
                    'churchId' => $ch->id,
                ],
            ];
        }

        return $items;
    }

    protected function colorForType(string $type): string
    {
        return match ($type) {
            'reuniao' => '#2563eb',
            'culto_especial' => '#b45309',
            'campanha' => '#dc2626',
            'formacao' => '#059669',
            default => '#1d4ed8',
        };
    }
}
