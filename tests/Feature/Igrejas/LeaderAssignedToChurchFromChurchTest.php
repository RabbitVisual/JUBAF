<?php

namespace Tests\Feature\Igrejas;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Igrejas\App\Events\LeaderAssignedToChurch;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Services\ChurchService;
use Tests\TestCase;

class LeaderAssignedToChurchFromChurchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    public function test_dispatches_leader_assigned_when_unijovem_leader_set_via_church_service(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        Event::fake([LeaderAssignedToChurch::class]);

        $church = Church::query()->create([
            'name' => 'Igreja Evento',
            'slug' => 'igreja-evento-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
            'unijovem_leader_user_id' => null,
            'pastor_user_id' => null,
        ]);

        $lider = User::factory()->create(['church_id' => null]);
        $lider->assignRole('lider');

        app(ChurchService::class)->updateChurch($church, [
            'unijovem_leader_user_id' => $lider->id,
        ]);

        Event::assertDispatched(LeaderAssignedToChurch::class, function (LeaderAssignedToChurch $e) use ($lider, $church): bool {
            return (int) $e->user->id === (int) $lider->id
                && (int) $e->churchId === (int) $church->id;
        });
    }

    public function test_does_not_duplicate_when_same_leader_saved_again(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $lider = User::factory()->create(['church_id' => null]);
        $lider->assignRole('lider');

        $church = Church::query()->create([
            'name' => 'Igreja Sem Mudança',
            'slug' => 'igreja-sem-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
            'unijovem_leader_user_id' => $lider->id,
        ]);

        Event::fake([LeaderAssignedToChurch::class]);

        app(ChurchService::class)->updateChurch($church, [
            'name' => 'Igreja Sem Mudança Renomeada',
        ]);

        Event::assertNotDispatched(LeaderAssignedToChurch::class);
    }

    public function test_dispatches_once_when_leader_changes_to_another_user(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $liderA = User::factory()->create();
        $liderB = User::factory()->create();

        $church = Church::query()->create([
            'name' => 'Igreja Troca Líder',
            'slug' => 'igreja-troca-'.uniqid(),
            'is_active' => true,
            'cooperation_status' => Church::COOPERATION_ATIVA,
            'unijovem_leader_user_id' => $liderA->id,
        ]);

        Event::fake([LeaderAssignedToChurch::class]);

        app(ChurchService::class)->updateChurch($church, [
            'unijovem_leader_user_id' => $liderB->id,
        ]);

        Event::assertDispatchedTimes(LeaderAssignedToChurch::class, 1);
        Event::assertDispatched(LeaderAssignedToChurch::class, function (LeaderAssignedToChurch $e) use ($liderB, $church): bool {
            return (int) $e->user->id === (int) $liderB->id
                && (int) $e->churchId === (int) $church->id;
        });
    }
}
