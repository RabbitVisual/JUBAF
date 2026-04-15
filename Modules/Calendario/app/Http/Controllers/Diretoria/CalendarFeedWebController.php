<?php

namespace Modules\Calendario\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Services\CalendarFeedService;

class CalendarFeedWebController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
            'church_id' => ['nullable', 'integer', 'exists:igrejas_churches,id'],
        ]);

        $user = $request->user();
        $feed = app(CalendarFeedService::class)->fullCalendarEvents(
            Carbon::parse($request->input('start')),
            Carbon::parse($request->input('end')),
            $user,
            CalendarFeedService::CONTEXT_DIRETORIA,
            $request->filled('church_id') ? (int) $request->input('church_id') : null,
            true,
            true,
        );

        return response()->json($feed);
    }
}
