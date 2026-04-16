<?php

namespace Tests\Feature\Igrejas;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Services\ChurchService;
use Tests\TestCase;

class CongregacaoJovensTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    public function test_lider_creates_youth_with_email_reset_flow(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        Notification::fake();

        $church = Church::query()->create([
            'name' => 'Igreja Jovens Test',
            'slug' => 'igreja-jovens-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $email = 'novo.jovem.'.uniqid().'@example.com';

        $lider = User::factory()->create(['church_id' => $church->id]);
        $lider->assignRole('lider');
        $lider->givePermissionTo('igrejas.jovens.provision');

        $this->actingAs($lider)
            ->post(route('lideres.congregacao.jovens.store'), [
                'first_name' => 'Novo',
                'last_name' => 'Jovem',
                'email' => $email,
                'phone' => '',
            ])
            ->assertRedirect(route('lideres.congregacao.index'));

        $youth = User::query()->where('email', $email)->first();
        $this->assertNotNull($youth);
        $this->assertTrue($youth->hasRole('jovens'));
        $this->assertSame((int) $church->id, (int) $youth->church_id);
        $this->assertSame((int) $lider->id, (int) $youth->provisioned_by_user_id);
        $this->assertNotNull($youth->provisioned_at);

        Notification::assertSentTo($youth, ResetPasswordNotification::class);
    }

    public function test_lider_assigned_via_church_service_can_provision_youth(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        Notification::fake();

        $church = Church::query()->create([
            'name' => 'Igreja Via Serviço',
            'slug' => 'igreja-svc-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $lider = User::factory()->create(['church_id' => null]);
        $lider->assignRole('lider');
        $lider->givePermissionTo('igrejas.jovens.provision');

        app(ChurchService::class)->updateChurch($church, [
            'unijovem_leader_user_id' => $lider->id,
        ]);

        $lider->refresh();
        $this->assertSame((int) $church->id, (int) $lider->church_id);

        $email = 'jovem.svc.'.uniqid().'@example.com';
        $this->actingAs($lider)
            ->post(route('lideres.congregacao.jovens.store'), [
                'first_name' => 'Jovem',
                'last_name' => 'Serviço',
                'email' => $email,
                'phone' => '',
            ])
            ->assertRedirect(route('lideres.congregacao.index'));

        $youth = User::query()->where('email', $email)->first();
        $this->assertNotNull($youth);
        $this->assertSame((int) $church->id, (int) $youth->church_id);
        $this->assertSame((int) $lider->id, (int) $youth->provisioned_by_user_id);
    }

    public function test_lider_can_export_youth_csv(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $church = Church::query()->create([
            'name' => 'Igreja Export',
            'slug' => 'igreja-export-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $lider = User::factory()->create(['church_id' => $church->id]);
        $lider->assignRole('lider');
        $lider->givePermissionTo('igrejas.jovens.provision');

        $response = $this->actingAs($lider)
            ->get(route('lideres.congregacao.jovens.export'));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('name', (string) $response->streamedContent());
    }

    public function test_lider_cannot_edit_youth_from_other_church(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $a = Church::query()->create([
            'name' => 'Igreja A',
            'slug' => 'a-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);
        $b = Church::query()->create([
            'name' => 'Igreja B',
            'slug' => 'b-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $lider = User::factory()->create(['church_id' => $a->id]);
        $lider->assignRole('lider');
        $lider->givePermissionTo('igrejas.jovens.provision');

        $other = User::factory()->create(['church_id' => $b->id]);
        $other->assignRole('jovens');

        $this->actingAs($lider)
            ->get(route('lideres.congregacao.jovens.edit', $other))
            ->assertForbidden();
    }

    public function test_lider_without_church_id_cannot_access_youth_create(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $lider = User::factory()->create(['church_id' => null]);
        $lider->assignRole('lider');

        $this->actingAs($lider)
            ->get(route('lideres.congregacao.jovens.create'))
            ->assertForbidden();
    }

    public function test_duplicate_email_rejected_on_store(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $church = Church::query()->create([
            'name' => 'Igreja D',
            'slug' => 'd-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
        ]);

        $existing = User::factory()->create(['email' => 'dup@example.com']);

        $lider = User::factory()->create(['church_id' => $church->id]);
        $lider->assignRole('lider');
        $lider->givePermissionTo('igrejas.jovens.provision');

        $this->actingAs($lider)
            ->from(route('lideres.congregacao.jovens.create'))
            ->post(route('lideres.congregacao.jovens.store'), [
                'first_name' => 'X',
                'last_name' => 'Y',
                'email' => $existing->email,
            ])
            ->assertSessionHasErrors('email');
    }
}
