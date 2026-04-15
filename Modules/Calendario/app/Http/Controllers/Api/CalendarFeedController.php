<?php

namespace Modules\Calendario\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Calendario\App\Services\CalendarFeedService;

class CalendarFeedController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
            'church_id' => ['nullable', 'integer', 'exists:igrejas_churches,id'],
            'include_birthdays' => ['sometimes', 'boolean'],
            'include_church' => ['sometimes', 'boolean'],
        ]);

        $user = $request->user();
        abort_unless($user, 403);

        $context = $user->can('calendario.events.view')
            ? CalendarFeedService::CONTEXT_DIRETORIA
            : CalendarFeedService::CONTEXT_PUBLIC;

        $feed = app(CalendarFeedService::class)->fullCalendarEvents(
            Carbon::parse($request->input('start')),
            Carbon::parse($request->input('end')),
            $user,
            $context,
            $request->filled('church_id') ? (int) $request->input('church_id') : null,
            $request->boolean('include_birthdays', true),
            $request->boolean('include_church', true),
        );

        return response()->json($feed);
    }
}
