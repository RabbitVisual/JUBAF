<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Modules\Events\App\Models\Event;
use Modules\Events\App\Models\EventRegistration;
use Modules\PaymentGateway\App\Models\Payment;
use Modules\PaymentGateway\App\Models\PaymentGateway;
use Modules\Treasury\App\Models\FinancialEntry;
use Tests\TestCase;

class PixWebhookIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_pix_webhook_confirms_registration_and_creates_single_financial_entry(): void
    {
        Http::fake([
            'https://api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => 'mp-evt-1',
                'status' => 'approved',
            ], 200),
        ]);

        $user = User::factory()->create();
        $gateway = PaymentGateway::query()->create([
            'name' => 'mercado_pago',
            'display_name' => 'Mercado Pago',
            'is_active' => true,
            'is_test_mode' => true,
            'credentials' => ['access_token' => 'fake-token'],
            'settings' => [],
            'supported_methods' => ['pix'],
            'sort_order' => 1,
        ]);

        $event = Event::query()->create([
            'title' => 'Conjubaf Teste',
            'slug' => 'conjubaf-teste',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(12),
            'status' => Event::STATUS_PUBLISHED,
            'visibility' => Event::VISIBILITY_MEMBERS,
            'created_by' => $user->id,
        ]);

        $registration = EventRegistration::query()->create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'event_id' => $event->id,
            'user_id' => $user->id,
            'total_amount' => 150,
            'status' => EventRegistration::STATUS_PENDING,
            'payment_method' => 'pix',
        ]);

        $payment = Payment::query()->create([
            'user_id' => $user->id,
            'payment_gateway_id' => $gateway->id,
            'payment_type' => 'event_registration',
            'payable_type' => EventRegistration::class,
            'payable_id' => $registration->id,
            'transaction_id' => 'TXN-EVT-1',
            'gateway_transaction_id' => 'mp-evt-1',
            'amount' => 150,
            'currency' => 'BRL',
            'status' => 'pending',
            'payment_method' => 'pix',
            'metadata' => ['registration_id' => $registration->id],
        ]);

        $url = route('api.gateway.webhook', ['driver' => 'mercado_pago']);
        $this->postJson($url.'?topic=payment&id=mp-evt-1')->assertOk();
        $this->postJson($url.'?topic=payment&id=mp-evt-1')->assertOk();

        $payment->refresh();
        $registration->refresh();

        $this->assertSame('completed', $payment->status);
        $this->assertSame(EventRegistration::STATUS_CONFIRMED, $registration->status);
        $this->assertDatabaseCount('financial_entries', 1);
        $this->assertDatabaseHas('financial_entries', [
            'payment_id' => $payment->id,
            'type' => 'income',
            'category' => 'event',
        ]);
    }

    public function test_pix_webhook_for_donation_is_idempotent_in_treasury(): void
    {
        Http::fake([
            'https://api.mercadopago.com/v1/payments/*' => Http::response([
                'id' => 'mp-don-1',
                'status' => 'approved',
            ], 200),
        ]);

        $user = User::factory()->create();
        $gateway = PaymentGateway::query()->create([
            'name' => 'mercado_pago',
            'display_name' => 'Mercado Pago',
            'is_active' => true,
            'is_test_mode' => true,
            'credentials' => ['access_token' => 'fake-token'],
            'settings' => [],
            'supported_methods' => ['pix'],
            'sort_order' => 1,
        ]);

        $payment = Payment::query()->create([
            'user_id' => $user->id,
            'payment_gateway_id' => $gateway->id,
            'payment_type' => 'donation',
            'payable_type' => null,
            'payable_id' => null,
            'transaction_id' => 'TXN-DON-1',
            'gateway_transaction_id' => 'mp-don-1',
            'amount' => 50,
            'currency' => 'BRL',
            'status' => 'pending',
            'payment_method' => 'pix',
            'description' => 'Doacao teste',
        ]);

        $url = route('api.gateway.webhook', ['driver' => 'mercado_pago']);
        $this->postJson($url.'?topic=payment&id=mp-don-1')->assertOk();
        $this->postJson($url.'?topic=payment&id=mp-don-1')->assertOk();

        $payment->refresh();
        $this->assertSame('completed', $payment->status);

        $entries = FinancialEntry::query()->where('payment_id', $payment->id)->get();
        $this->assertCount(1, $entries);
        $this->assertSame('donation', $entries->first()->category);
    }
}
