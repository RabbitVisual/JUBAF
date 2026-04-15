<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;

class ApiObservabilityTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function health_endpoint_returns_ok_status()
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'timestamp',
                     'environment',
                     'services' => [
                         'database',
                         'storage'
                     ]
                 ])
                 ->assertJsonPath('services.database.status', 'ok')
                 ->assertJsonPath('services.storage.status', 'ok');
    }

    #[Test]
    public function log_error_endpoint_records_error_successfully()
    {
        Log::shouldReceive('error')
            ->once()
            ->withArgs(function ($message, $context) {
                return str_contains($message, '[PWA-CLIENT-ERROR] Critical failure') &&
                       $context['stack'] === 'Error: Trace' &&
                       $context['url'] === 'http://localhost/pwa';
            });

        $response = $this->postJson('/api/v1/log-error', [
            'message' => 'Critical failure',
            'stack' => 'Error: Trace',
            'url' => 'http://localhost/pwa',
            'context' => ['component' => 'Map']
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('success', true);
    }

    #[Test]
    public function log_error_fails_without_message()
    {
        $response = $this->postJson('/api/v1/log-error', [
            'stack' => 'Missing message'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['message']);
    }
}
