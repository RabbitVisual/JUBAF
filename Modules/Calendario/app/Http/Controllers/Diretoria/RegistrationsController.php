<?php

namespace Modules\Calendario\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarRegistration;

class RegistrationsController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('calendario.registrations.view'), 403);

        $q = CalendarRegistration::query()
            ->with(['user', 'event'])
            ->whereHas('event');

        if ($request->filled('status')) {
            $q->where('status', $request->input('status'));
        }
        if ($request->filled('event_id')) {
            $q->where('event_id', $request->input('event_id'));
        }

        $registrations = $q->orderByDesc('id')->paginate(25)->withQueryString();

        $eventsForFilter = CalendarEvent::query()
            ->orderByDesc('starts_at')
            ->limit(200)
            ->get(['id', 'title', 'starts_at']);

        return view('calendario::paineldiretoria.registrations.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.calendario',
            'registrations' => $registrations,
            'eventsForFilter' => $eventsForFilter,
            'filters' => $request->only(['status', 'event_id']),
        ]);
    }
}
