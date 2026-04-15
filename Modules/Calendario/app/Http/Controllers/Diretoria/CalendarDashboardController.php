<?php

namespace Modules\Calendario\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;

class CalendarDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $now = now();
        $horizon = (clone $now)->addDays(30);

        $upcomingCount = CalendarEvent::query()
            ->where('starts_at', '>=', $now)
            ->where('starts_at', '<=', $horizon)
            ->count();

        $openRegistrationCount = CalendarEvent::query()
            ->where('registration_open', true)
            ->where('status', CalendarEvent::STATUS_PUBLISHED)
            ->where('starts_at', '>=', $now)
            ->count();

        $registrationsThisMonth = CalendarRegistration::query()
            ->where('status', '!=', CalendarRegistration::STATUS_CANCELLED)
            ->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->count();

        $pendingPaymentsCount = CalendarRegistration::query()
            ->where('status', CalendarRegistration::STATUS_PENDING_PAYMENT)
            ->count();

        $nextEvents = CalendarEvent::query()
            ->withCount('registrations')
            ->where('starts_at', '>=', $now)
            ->orderBy('starts_at')
            ->limit(8)
            ->get();

        return view('calendario::paineldiretoria.dashboard', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.calendario',
            'upcomingCount' => $upcomingCount,
            'openRegistrationCount' => $openRegistrationCount,
            'registrationsThisMonth' => $registrationsThisMonth,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'nextEvents' => $nextEvents,
            'feedUrl' => route('diretoria.calendario.feed'),
        ]);
    }
}
