<?php

namespace Tests\Feature;

use App\Models\SystemConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SystemConfigRuntimeProviderTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function runtime_provider_overrides_mail_and_broadcast_from_system_configs(): void
    {
        SystemConfig::updateOrCreate(
            ['key' => 'mail.mailer'],
            ['value' => 'smtp', 'type' => 'string', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.host'],
            ['value' => 'smtp.hostinger.com', 'type' => 'string', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.port'],
            ['value' => '465', 'type' => 'integer', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.username'],
            ['value' => 'noreply@semagricm.com', 'type' => 'string', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.password'],
            ['value' => 'vfzo lwow lvtd ksrh', 'type' => 'password', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.encryption'],
            ['value' => 'ssl', 'type' => 'string', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.from_address'],
            ['value' => 'noreply@semagricm.com', 'type' => 'string', 'group' => 'email', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'mail.from_name'],
            ['value' => 'JUBAF', 'type' => 'string', 'group' => 'email', 'description' => null]
        );

        SystemConfig::updateOrCreate(
            ['key' => 'broadcast.driver'],
            ['value' => 'pusher', 'type' => 'string', 'group' => 'integrations', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'pusher.app_id'],
            ['value' => '2132462', 'type' => 'string', 'group' => 'integrations', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'pusher.app_key'],
            ['value' => 'a9cc8dcc4205b21e0d59', 'type' => 'string', 'group' => 'integrations', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'pusher.app_secret'],
            ['value' => '5f5a0c50d1c582922346', 'type' => 'password', 'group' => 'integrations', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'pusher.cluster'],
            ['value' => 'sa1', 'type' => 'string', 'group' => 'integrations', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'pusher.port'],
            ['value' => '443', 'type' => 'integer', 'group' => 'integrations', 'description' => null]
        );
        SystemConfig::updateOrCreate(
            ['key' => 'pusher.scheme'],
            ['value' => 'https', 'type' => 'string', 'group' => 'integrations', 'description' => null]
        );

        $this->get(route('api.documentation.json'))
            ->assertStatus(200);

        $this->assertSame('smtp', config('mail.default'));
        $this->assertSame('smtp.hostinger.com', config('mail.mailers.smtp.host'));
        $this->assertSame(465, config('mail.mailers.smtp.port'));
        $this->assertSame('ssl', config('mail.mailers.smtp.encryption'));
        $this->assertSame('noreply@semagricm.com', config('mail.mailers.smtp.username'));
        $this->assertSame('noreply@semagricm.com', config('mail.from.address'));
        $this->assertSame('JUBAF', config('mail.from.name'));

        $this->assertSame('pusher', config('broadcasting.default'));
        $this->assertSame('a9cc8dcc4205b21e0d59', config('broadcasting.connections.pusher.key'));
        $this->assertSame('2132462', config('broadcasting.connections.pusher.app_id'));
        $this->assertSame('sa1', config('broadcasting.connections.pusher.options.cluster'));
        $this->assertSame('5f5a0c50d1c582922346', config('broadcasting.connections.pusher.secret'));
    }
}

