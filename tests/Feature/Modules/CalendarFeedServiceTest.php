<?php

namespace Tests\Feature\Modules;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Calendario\App\Services\CalendarFeedService;
use Tests\TestCase;

class CalendarFeedServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_returns_published_public_events_for_public_context(): void
    {
        $starts = now()->addDays(3)->startOfHour();

        CalendarEvent::query()->create([
            'title' => 'Teste público',
            'slug' => 'teste-publico-'.uniqid(),
            'start_date' => $starts,
            'end_date' => $starts->copy()->addHours(2),
            'visibility' => CalendarEvent::VIS_PUBLIC,
            'type' => 'evento',
            'status' => CalendarEvent::STATUS_PUBLISHED,
            'registration_open' => false,
        ]);

        CalendarEvent::query()->create([
            'title' => 'Rascunho',
            'slug' => 'rascunho-'.uniqid(),
            'start_date' => $starts,
            'visibility' => CalendarEvent::VIS_PUBLIC,
            'type' => 'evento',
            'status' => CalendarEvent::STATUS_DRAFT,
            'registration_open' => false,
        ]);

        $feed = app(CalendarFeedService::class)->fullCalendarEvents(
            now()->startOfDay(),
            now()->addDays(30)->endOfDay(),
            null,
            CalendarFeedService::CONTEXT_PUBLIC,
            null,
            false,
            false,
        );

        $titles = collect($feed)->pluck('title')->all();
        $this->assertContains('Teste público', $titles);
        $this->assertNotContains('Rascunho', $titles);
    }

    public function test_pricing_service_applies_discount_code(): void
    {
        $event = CalendarEvent::query()->create([
            'title' => 'Pago',
            'slug' => 'pago-'.uniqid(),
            'start_date' => now()->addWeek(),
            'visibility' => CalendarEvent::VIS_PUBLIC,
            'type' => 'evento',
            'status' => CalendarEvent::STATUS_PUBLISHED,
            'ticket_price' => 100,
            'is_paid' => true,
            'registration_open' => true,
        ]);

        $event->priceRules()->create([
            'rule_type' => \Modules\Calendario\App\Models\CalendarPriceRule::TYPE_DISCOUNT_CODE,
            'priority' => 1,
            'is_active' => true,
            'config' => ['code' => 'JUBAF10', 'percent' => 10],
        ]);

        $fee = app(\Modules\Calendario\App\Services\CalendarPricingService::class)->calculateRegistrationTotal(
            $event->fresh(['priceRules', 'batches']),
            ['discount_code' => 'jubaf10']
        );

        $this->assertEquals(90.0, $fee);
    }
}
