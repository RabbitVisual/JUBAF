<?php

namespace Tests\Feature\Modules;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;
use Tests\TestCase;

class IgrejasChangeRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    public function test_lider_submits_and_secretario_approves_update_profile(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $church = Church::query()->create([
            'name' => 'Igreja Fluxo',
            'slug' => 'igreja-fluxo-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $lider = User::factory()->create(['church_id' => $church->id]);
        $lider->assignRole('lider');

        $secretario = User::factory()->create();
        $secretario->assignRole('secretario-1');

        $this->actingAs($lider)
            ->post(route('lideres.igrejas.requests.store'), [
                'church_id' => $church->id,
                'type' => ChurchChangeRequest::TYPE_UPDATE_PROFILE,
                'payload' => ['city' => 'Feira de Santana'],
            ])
            ->assertRedirect();

        $req = ChurchChangeRequest::query()->latest('id')->first();
        $this->assertNotNull($req);
        $this->assertSame(ChurchChangeRequest::STATUS_DRAFT, $req->status);

        $this->actingAs($lider)
            ->post(route('lideres.igrejas.requests.submit', $req))
            ->assertRedirect();

        $req->refresh();
        $this->assertSame(ChurchChangeRequest::STATUS_SUBMITTED, $req->status);

        $this->actingAs($secretario)
            ->post(route('diretoria.igrejas.requests.approve', $req), [
                'review_notes' => 'OK',
            ])
            ->assertRedirect();

        $church->refresh();
        $this->assertSame('Feira de Santana', $church->city);
    }

    public function test_affiliated_church_ids_used_in_policy_for_lider(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $a = Church::query()->create([
            'name' => 'A',
            'slug' => 'a-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);
        $b = Church::query()->create([
            'name' => 'B',
            'slug' => 'b-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $lider = User::factory()->create(['church_id' => $a->id]);
        $lider->assignRole('lider');
        $lider->assignedChurches()->sync([$b->id => ['role_on_church' => 'lider_unijovem']]);

        $this->assertTrue($lider->can('view', $a));
        $this->assertTrue($lider->can('view', $b));
        $this->assertFalse($lider->can('view', Church::query()->create([
            'name' => 'C',
            'slug' => 'c-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ])));
    }
}
