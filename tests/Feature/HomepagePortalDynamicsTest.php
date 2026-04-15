<?php

namespace Tests\Feature;

use App\Models\SystemConfig;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\Igrejas\App\Models\Church;
use Nwidart\Modules\Facades\Module;
use Tests\TestCase;

class HomepagePortalDynamicsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('module:enable', ['module' => 'Homepage']);
    }

    public function test_homepage_loads_with_portal_data_when_igrejas_present(): void
    {
        SystemConfig::updateOrCreate(['key' => 'homepage_hero_title'], ['value' => 'Portal Teste']);

        if (! Schema::hasTable('igrejas_churches')) {
            $this->markTestSkipped('Tabela igrejas_churches indisponível nesta suite.');
        }

        Module::enable('Igrejas');

        Church::query()->create([
            'name' => 'Congregação Teste Portal',
            'kind' => Church::KIND_CHURCH,
            'is_active' => true,
            'state' => 'BA',
        ]);

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('Portal Teste', false);
        $response->assertSee('Rede e agenda', false);
        $response->assertSee('Igrejas associadas', false);
    }
}
