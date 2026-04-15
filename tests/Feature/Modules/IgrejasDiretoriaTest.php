<?php

namespace Tests\Feature\Modules;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\JubafSector;
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

    public function test_pastor_can_update_own_church_without_igrejas_edit_permission(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $sector = JubafSector::query()->create([
            'name' => 'Setor Teste',
            'slug' => 'setor-teste-'.uniqid(),
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $church = Church::query()->create([
            'name' => 'Congregação Teste',
            'kind' => Church::KIND_CHURCH,
            'cnpj' => '04252432000105',
            'jubaf_sector_id' => $sector->id,
        ]);

        $pastor = User::factory()->create(['church_id' => $church->id]);
        $pastor->assignRole('pastor');

        $this->assertFalse($pastor->can('igrejas.edit'));
        $this->assertTrue(Gate::forUser($pastor)->allows('update', $church));
    }
}
