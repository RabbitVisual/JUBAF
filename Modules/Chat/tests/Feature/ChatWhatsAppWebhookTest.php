<?php

namespace Modules\Chat\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ChatWhatsAppWebhookTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function webhook_allows_empty_secret_in_testing_environment(): void
    {
        config(['notificacoes.evolution.webhook_secret' => '']);

        $this->postJson('/api/v1/chat/whatsapp/webhook', [
            'data' => [
                'key' => ['remoteJid' => '244900000001@s.whatsapp.net'],
                'message' => ['conversation' => 'Olá'],
            ],
        ])->assertOk()->assertJson(['success' => true]);
    }

    #[Test]
    public function webhook_accepts_valid_secret_header(): void
    {
        config(['app.env' => 'production']);
        config(['notificacoes.evolution.webhook_secret' => 'test-secret']);

        $this->postJson(
            '/api/v1/chat/whatsapp/webhook',
            [
                'data' => [
                    'key' => ['remoteJid' => '244900000001@s.whatsapp.net'],
                    'message' => ['conversation' => 'Olá'],
                ],
            ],
            ['X-Webhook-Token' => 'test-secret']
        )->assertOk()->assertJson(['success' => true]);
    }
}
