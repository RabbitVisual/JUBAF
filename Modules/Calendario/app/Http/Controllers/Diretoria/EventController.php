<?php

namespace Modules\Calendario\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Modules\Calendario\App\Http\Requests\StoreCalendarEventRequest;
use Modules\Calendario\App\Http\Requests\UpdateCalendarEventRequest;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Models\CalendarPriceRule;
use Modules\Calendario\App\Models\CalendarRegistration;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Igrejas\App\Models\Church;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', CalendarEvent::class);

        $q = CalendarEvent::query()->withCount('registrations');

        if ($request->filled('from')) {
            $q->whereDate('start_date', '>=', $request->input('from'));
        }
        if ($request->filled('to')) {
            $q->whereDate('start_date', '<=', $request->input('to'));
        }
        if ($request->filled('visibility')) {
            $q->where('visibility', $request->input('visibility'));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->input('status'));
        }

        $events = $q->orderBy('start_date')->paginate(20)->withQueryString();

        return view('calendario::paineldiretoria.events.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.calendario',
            'events' => $events,
            'filters' => $request->only(['from', 'to', 'visibility', 'status']),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', CalendarEvent::class);

        $startsAt = null;
        $endsAt = null;
        if ($request->filled('date')) {
            try {
                $day = Carbon::parse($request->string('date')->toString())->startOfDay();
                $startsAt = $day->copy()->setTime(9, 0);
                $endsAt = $day->copy()->setTime(11, 0);
            } catch (\Throwable) {
                // ignore invalid date query
            }
        }

        return view('calendario::paineldiretoria.events.create', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.calendario',
            'churches' => module_enabled('Igrejas') ? Church::query()->orderBy('name')->get() : collect(),
            'event' => new CalendarEvent([
                'visibility' => CalendarEvent::VIS_PUBLIC,
                'type' => 'evento',
                'registration_open' => false,
                'all_day' => false,
                'status' => CalendarEvent::STATUS_PUBLISHED,
                'max_per_registration' => 1,
                'start_date' => $startsAt,
                'end_date' => $endsAt,
            ]),
        ]);
    }

    public function store(StoreCalendarEventRequest $request): RedirectResponse
    {
        $data = $this->preparePayloadFromRequest($request);
        $data['created_by'] = $request->user()->id;
        $data['all_day'] = $request->boolean('all_day');
        $data['registration_open'] = $request->boolean('registration_open');

        $event = CalendarEvent::query()->create($data);

        $this->storeMedia($request, $event);
        $this->syncBatches($request, $event);
        $this->syncDiscountRule($request, $event);

        $msg = 'Evento criado.';
        $msg = $this->appendOverlapWarning($msg, $data, null);

        return redirect()->route('diretoria.calendario.events.edit', $event)->with('success', $msg);
    }

    public function edit(CalendarEvent $event): View
    {
        $this->authorize('update', $event);

        $event->load(['batches', 'priceRules']);

        return view('calendario::paineldiretoria.events.edit', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.calendario',
            'churches' => module_enabled('Igrejas') ? Church::query()->orderBy('name')->get() : collect(),
            'event' => $event,
            'registrations' => $event->registrations()->with('user')->orderByDesc('id')->limit(50)->get(),
            'discountRule' => $event->priceRules->firstWhere('rule_type', CalendarPriceRule::TYPE_DISCOUNT_CODE),
        ]);
    }

    public function update(UpdateCalendarEventRequest $request, CalendarEvent $event): RedirectResponse
    {
        $data = $this->preparePayloadFromRequest($request);
        $data['all_day'] = $request->boolean('all_day');
        $data['registration_open'] = $request->boolean('registration_open');

        $event->update($data);

        $this->storeMedia($request, $event);
        $this->syncBatches($request, $event);
        $this->syncDiscountRule($request, $event);

        $msg = 'Evento actualizado.';
        $msg = $this->appendOverlapWarning($msg, $data, $event);

        return redirect()->route('diretoria.calendario.events.index')->with('success', $msg);
    }

    public function destroy(CalendarEvent $event): RedirectResponse
    {
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('diretoria.calendario.events.index')->with('success', 'Evento removido.');
    }

    public function checkIn(Request $request, CalendarEvent $event, CalendarRegistration $registration): RedirectResponse
    {
        $this->authorize('manageRegistrations', $event);
        abort_unless((int) $registration->evento_id === (int) $event->id, 404);

        if ($request->boolean('undo')) {
            $registration->update(['checked_in_at' => null]);
        } else {
            $registration->update(['checked_in_at' => now()]);
        }

        return back()->with('success', 'Check-in actualizado.');
    }

    private function preparePayloadFromRequest(StoreCalendarEventRequest|UpdateCalendarEventRequest $request): array
    {
        $data = Arr::except($request->validated(), [
            'cover',
            'banner',
            'theme',
            'primary_color',
            'secondary_color',
            'schedule_json',
            'form_fields_json',
            'metadata_json',
            'schedule_items',
            'meta_tips',
            'meta_dress_code',
            'batches',
            'pricing_discount_code',
            'pricing_discount_percent',
        ]);

        $data['theme_config'] = [
            'theme' => $request->input('theme', 'corporate'),
            'primary_color' => $request->input('primary_color', '#1e40af'),
            'secondary_color' => $request->input('secondary_color', '#0f172a'),
        ];

        $data['schedule'] = $this->buildScheduleFromRequest($request);
        $data['form_fields'] = $this->decodeJsonField($request->input('form_fields_json'));
        $data['metadata'] = $this->buildMetadataFromRequest($request);

        return $data;
    }

    private function buildScheduleFromRequest(FormRequest $request): ?array
    {
        $items = $request->input('schedule_items');
        if (is_array($items)) {
            $out = [];
            foreach ($items as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $label = trim((string) ($row['label'] ?? ''));
                if ($label === '') {
                    continue;
                }
                $out[] = [
                    'time' => trim((string) ($row['time'] ?? '')),
                    'label' => $label,
                ];
            }

            return $out === [] ? null : $out;
        }

        return $this->decodeJsonField($request->input('schedule_json'));
    }

    private function buildMetadataFromRequest(FormRequest $request): ?array
    {
        $meta = [];
        if ($request->filled('meta_tips')) {
            $meta['tips'] = $request->input('meta_tips');
        }
        if ($request->filled('meta_dress_code')) {
            $meta['dress_code'] = $request->input('meta_dress_code');
        }
        $fromJson = $this->decodeJsonField($request->input('metadata_json'));
        if (is_array($fromJson)) {
            $meta = array_merge($fromJson, $meta);
        }

        return $meta === [] ? null : $meta;
    }

    private function decodeJsonField(?string $raw): ?array
    {
        if ($raw === null || trim($raw) === '') {
            return null;
        }
        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

            return is_array($decoded) ? $decoded : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function storeMedia(Request $request, CalendarEvent $event): void
    {
        if ($request->hasFile('cover')) {
            $event->update([
                'cover_path' => $request->file('cover')->store('calendario/covers', 'public'),
            ]);
        }
        if ($request->hasFile('banner')) {
            $event->update([
                'banner_path' => $request->file('banner')->store('calendario/banners', 'public'),
            ]);
        }
    }

    private function syncBatches(Request $request, CalendarEvent $event): void
    {
        if (! $request->has('batches')) {
            return;
        }
        $rows = $request->input('batches', []);
        if (! is_array($rows)) {
            return;
        }

        if ($rows === []) {
            $event->batches()->delete();

            return;
        }

        $kept = [];
        foreach ($rows as $row) {
            if (! isset($row['price']) && ! isset($row['id'])) {
                continue;
            }
            $payload = [
                'name' => $row['name'] ?? null,
                'price' => (float) ($row['price'] ?? 0),
                'sale_starts_at' => ! empty($row['sale_starts_at']) ? $row['sale_starts_at'] : null,
                'sale_ends_at' => ! empty($row['sale_ends_at']) ? $row['sale_ends_at'] : null,
                'capacity' => isset($row['capacity']) ? (int) $row['capacity'] : null,
                'sort_order' => (int) ($row['sort_order'] ?? 0),
            ];

            if (! empty($row['id'])) {
                $b = $event->batches()->whereKey((int) $row['id'])->first();
                if ($b) {
                    $b->update($payload);
                    $kept[] = $b->id;
                }
            } else {
                $b = $event->batches()->create($payload);
                $kept[] = $b->id;
            }
        }

        if ($kept === []) {
            return;
        }

        $event->batches()->whereNotIn('id', $kept)->delete();
    }

    private function syncDiscountRule(Request $request, CalendarEvent $event): void
    {
        $code = $request->input('pricing_discount_code');
        $pct = $request->input('pricing_discount_percent');

        $existing = $event->priceRules()
            ->where('rule_type', CalendarPriceRule::TYPE_DISCOUNT_CODE)
            ->whereNull('event_batch_id')
            ->first();

        if (filled($code) && $pct !== null && $pct !== '') {
            CalendarPriceRule::query()->updateOrCreate(
                [
                    'event_id' => $event->id,
                    'rule_type' => CalendarPriceRule::TYPE_DISCOUNT_CODE,
                    'event_batch_id' => null,
                ],
                [
                    'priority' => 10,
                    'is_active' => true,
                    'config' => [
                        'code' => (string) $code,
                        'percent' => (float) $pct,
                    ],
                ]
            );
        } else {
            if ($existing) {
                $existing->delete();
            }
        }
    }

    private function appendOverlapWarning(string $msg, array $data, ?CalendarEvent $ignore): string
    {
        if (
            ! module_enabled('Igrejas')
            || ! (bool) config('igrejas.integrations.calendario_warn_local_overlap', true)
            || ! empty($data['church_id'])
        ) {
            return $msg;
        }

        $starts = $data['start_date'];
        $ends = $data['end_date'] ?? $starts->copy()->addHours(2);
        $overlap = CalendarEvent::localChurchEventsOverlapping($starts, $ends)
            ->when($ignore, fn ($c) => $c->filter(fn ($e) => (int) $e->id !== (int) $ignore->id));
        if ($overlap->isNotEmpty()) {
            $msg .= ' Aviso: '.$overlap->count().' evento(s) de congregações no mesmo período — confirme conflitos.';
        }

        return $msg;
    }

    public function monitor(CalendarEvent $event): View
    {
        $this->authorize('manageRegistrations', $event);

        $event->load(['registrations.user']);
        $totalRevenue = FinTransaction::query()
            ->where('source', FinTransaction::SOURCE_GATEWAY)
            ->where('evento_id', $event->id)
            ->sum('amount');
        $confirmed = $event->registrations->where('status', CalendarRegistration::STATUS_CONFIRMED)->count();
        $capacity = $event->capacity;
        $occupancyPercent = $capacity ? round(($confirmed / max($capacity, 1)) * 100, 1) : null;

        return view('calendario::paineldiretoria.events.monitor', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'routePrefix' => 'diretoria.calendario',
            'event' => $event,
            'totalRevenue' => $totalRevenue,
            'confirmed' => $confirmed,
            'capacity' => $capacity,
            'occupancyPercent' => $occupancyPercent,
            'registrations' => $event->registrations->sortByDesc('id')->values(),
        ]);
    }
}
