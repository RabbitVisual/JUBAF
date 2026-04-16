<?php

namespace Modules\PainelJovens\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Modules\Avisos\App\Models\Aviso;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['church', 'talentProfile.skills', 'jovemPerfil']);

        $featuredEvents = collect();
        $pastParticipations = collect();
        $registrationSummary = collect();
        $urgentAvisos = collect();

        if (module_enabled('Calendario')) {
            $featuredEvents = CalendarEvent::query()
                ->published()
                ->upcoming()
                ->where(function ($q): void {
                    $q->whereIn('visibility', [
                        CalendarEvent::VIS_JOVENS,
                        CalendarEvent::VIS_PUBLIC,
                        CalendarEvent::VIS_AUTH,
                    ]);
                })
                ->orderByDesc('is_featured')
                ->orderBy('start_date')
                ->limit(6)
                ->get();

            $pastParticipations = CalendarRegistration::query()
                ->where('user_id', $user->id)
                ->whereIn('status', [
                    CalendarRegistration::STATUS_CONFIRMED,
                    CalendarRegistration::STATUS_WAITLIST,
                ])
                ->whereHas('event', function ($q): void {
                    $q->where('start_date', '<', now());
                })
                ->with('event')
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get();

            $registrationSummary = CalendarRegistration::query()
                ->where('user_id', $user->id)
                ->where('status', '!=', CalendarRegistration::STATUS_CANCELLED)
                ->with(['event', 'gatewayPayment'])
                ->orderByDesc('updated_at')
                ->limit(8)
                ->get();
        }

        if (module_enabled('Avisos') && Schema::hasColumn('avisos', 'modo_quadro') && Schema::hasColumn('avisos', 'classificacao')) {
            $urgentAvisos = Aviso::query()
                ->ativos()
                ->forAudience($user)
                ->urgentInstitutional()
                ->orderByDesc('destacar')
                ->orderByDesc('created_at')
                ->limit(8)
                ->get();
        }

        return view('paineljovens::dashboard', [
            'user' => $user,
            'featuredEvents' => $featuredEvents,
            'pastParticipations' => $pastParticipations,
            'registrationSummary' => $registrationSummary,
            'urgentAvisos' => $urgentAvisos,
        ]);
    }
}
