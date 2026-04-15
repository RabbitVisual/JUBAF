<?php

namespace Tests\Feature\Modules;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IgrejasDiretoriaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    public function test_secretario_can_open_igrejas_hub_and_list(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $user = User::factory()->create();
        $user->assignRole('secretario-1');

        $this->actingAs($user);

        $this->get(route('diretoria.igrejas.dashboard'))
            ->assertOk();

        $this->get(route('diretoria.igrejas.index'))
            ->assertOk();
    }

    public function test_jovens_cannot_open_igrejas_diretoria_hub(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $user = User::factory()->create();
        $user->assignRole('jovens');

        $this->actingAs($user)
            ->get(route('diretoria.igrejas.dashboard'))
            ->assertForbidden();
    }
}
