<?php

namespace Modules\LiderancaPanel\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Events\App\Models\Event;
use Modules\Events\App\Models\EventRegistration;

class CaravanFunnelService
{
    /**
     * Inscrições gratuitas ou só com confirmação manual não têm linha em Payment; nesse caso o fluxo segue só pelo status da inscrição.
     */
    public function registrationPaymentSatisfied(?EventRegistration $registration): bool
    {
        if (! $registration) {
            return false;
        }

        $payment = $registration->latestPayment;

        if ($payment === null) {
            return true;
        }

        return $payment->status === 'completed';
    }

    public function memberIdsInChurch(int $churchId): Collection
    {
        return User::query()
            ->where('church_id', $churchId)
            ->where('is_active', true)
            ->pluck('id');
    }

    public function upcomingEventsForSelect(): Collection
    {
        $start = now()->startOfDay();
        $until = now()->copy()->addDays(90)->endOfDay();

        return Event::query()
            ->where('status', Event::STATUS_PUBLISHED)
            ->where('start_date', '>=', $start)
            ->where('start_date', '<=', $until)
            ->orderBy('start_date')
            ->get(['id', 'title', 'start_date', 'is_featured']);
    }

    public function resolveDefaultEventId(): ?int
    {
        $candidates = $this->upcomingEventsForSelect();
        if ($candidates->isEmpty()) {
            return null;
        }

        $featured = $candidates->where('is_featured', true);
        if ($featured->count() === 1) {
            return (int) $featured->first()->id;
        }

        return (int) $candidates->first()->id;
    }

    /**
     * @return array{registered:int, enrolled:int, confirmed:int, paid_ready:int}
     */
    public function funnelStats(int $churchId, int $eventId): array
    {
        $memberIds = $this->memberIdsInChurch($churchId);
        $registered = $memberIds->count();

        $registrations = EventRegistration::query()
            ->where('event_id', $eventId)
            ->whereIn('user_id', $memberIds)
            ->with('latestPayment')
            ->get();

        $enrolled = $registrations->count();
        $confirmed = $registrations->where('status', EventRegistration::STATUS_CONFIRMED)->count();
        $paidReady = $registrations
            ->where('status', EventRegistration::STATUS_CONFIRMED)
            ->filter(fn (EventRegistration $r) => $this->registrationPaymentSatisfied($r))
            ->count();

        return [
            'registered' => $registered,
            'enrolled' => $enrolled,
            'confirmed' => $confirmed,
            'paid_ready' => $paidReady,
        ];
    }

    public function paginateMembersForEvent(int $churchId, int $eventId, int $perPage = 15): LengthAwarePaginator
    {
        return User::query()
            ->where('church_id', $churchId)
            ->where('is_active', true)
            ->with([
                'registrations' => function ($q) use ($eventId) {
                    $q->where('event_id', $eventId)->with('latestPayment');
                },
            ])
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function registrationForEvent(User $user, int $eventId): ?EventRegistration
    {
        return $user->registrations->firstWhere('event_id', $eventId);
    }

    public function paymentStatusLabel(?EventRegistration $registration): string
    {
        if (! $registration) {
            return '—';
        }

        $payment = $registration->latestPayment;
        if ($payment === null) {
            return 'Não aplicável';
        }

        return match ($payment->status) {
            'completed' => 'Pago',
            'pending' => 'Pendente',
            default => ucfirst((string) $payment->status),
        };
    }
}
