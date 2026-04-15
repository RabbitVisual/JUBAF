<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class GatewayWebhookTest extends TestCase
{
    public function test_gateway_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('gateway.webhooks.handle'));
        $this->assertTrue(Route::has('gateway.public.checkout'));
        $this->assertTrue(Route::has('diretoria.gateway.dashboard'));
    }
}
