<?php

namespace Modules\Calendario\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Calendario\App\Models\CalendarEvent;

class PublicCalendarController extends Controller
{
    public function index(Request $request): View
    {
        $events = CalendarEvent::query()
            ->published()
            ->where('visibility', CalendarEvent::VIS_PUBLIC)
            ->where('start_date', '>=', now()->startOfDay())
            ->orderBy('start_date')
            ->paginate(12);

        return view('calendario::public.index', [
            'events' => $events,
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $event = CalendarEvent::query()
            ->where('slug', $slug)
            ->with(['blogPost', 'batches', 'church', 'aviso'])
            ->firstOrFail();

        $preview = $request->query('preview');
        $previewOk = $preview && hash_equals((string) ($event->preview_token ?? ''), (string) $preview);

        $visible = $event->status === CalendarEvent::STATUS_PUBLISHED
            || $previewOk;

        abort_unless($visible, 404);
        abort_unless($event->visibility === CalendarEvent::VIS_PUBLIC, 404);

        $theme = $event->resolvedThemeConfig()['theme'];
        if (! view()->exists('calendario::public.themes.'.$theme)) {
            $theme = 'corporate';
        }

        return view('calendario::public.show', [
            'event' => $event,
            'theme' => $theme,
            'isPreview' => $previewOk && $event->status !== CalendarEvent::STATUS_PUBLISHED,
        ]);
    }
}
