<?php

namespace Tests\Feature\Modules;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Talentos\App\Models\TalentProfile;
use Modules\Talentos\Database\Seeders\TalentosDatabaseSeeder;
use Tests\TestCase;

class FinanceCalendarTalentosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    public function test_tesoureiro_can_store_fin_transaction(): void
    {
        if (! module_enabled('Financeiro')) {
            $this->markTestSkipped('Módulo Financeiro inativo.');
        }

        $cat = FinCategory::query()->create([
            'name' => 'Categoria teste receita',
            'direction' => 'in',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $user = User::factory()->create();
        $user->assignRole('tesoureiro-1');

        $this->actingAs($user)
            ->post(route('diretoria.financeiro.transactions.store'), [
                'category_id' => $cat->id,
                'occurred_on' => now()->toDateString(),
                'amount' => '100.50',
                'direction' => 'in',
                'scope' => 'regional',
                'description' => 'Teste automático',
            ])
            ->assertRedirect(route('diretoria.financeiro.transactions.index'));

        $this->assertDatabaseHas('fin_transactions', [
            'category_id' => $cat->id,
            'created_by' => $user->id,
        ]);
    }

    public function test_jovens_can_register_for_open_event(): void
    {
        if (! module_enabled('Calendario')) {
            $this->markTestSkipped('Módulo Calendario inativo.');
        }

        $user = User::factory()->create();
        $user->assignRole('jovens');

        $event = CalendarEvent::query()->create([
            'title' => 'Encontro de teste',
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeek()->addHours(2),
            'visibility' => CalendarEvent::VIS_JOVENS,
            'type' => 'evento',
            'registration_open' => true,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user)
            ->from(route('jovens.calendario.show', $event))
            ->post(route('jovens.calendario.register', $event))
            ->assertRedirect();

        $this->assertDatabaseHas('evento_inscricoes', [
            'evento_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'confirmed',
        ]);
    }

    public function test_jovens_can_update_talent_profile(): void
    {
        if (! module_enabled('Talentos')) {
            $this->markTestSkipped('Módulo Talentos inativo.');
        }

        $this->seed(TalentosDatabaseSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('jovens');

        $this->actingAs($user)
            ->put(route('jovens.talentos.profile.update'), [
                'bio' => 'Gosto de música e receção.',
                'availability_text' => 'Sábados',
                'is_searchable' => true,
            ])
            ->assertRedirect(route('jovens.talentos.profile.edit'));

        $p = TalentProfile::query()->where('user_id', $user->id)->first();
        $this->assertNotNull($p);
        $this->assertSame('Gosto de música e receção.', $p->bio);
    }

    public function test_secretario_can_view_calendario_dashboard(): void
    {
        if (! module_enabled('Calendario')) {
            $this->markTestSkipped('Módulo Calendario inativo.');
        }

        $user = User::factory()->create();
        $user->assignRole('secretario-1');

        $this->actingAs($user)
            ->get(route('diretoria.calendario.dashboard'))
            ->assertOk();
    }

    public function test_secretario_can_view_talentos_diretoria_dashboard(): void
    {
        if (! module_enabled('Talentos')) {
            $this->markTestSkipped('Módulo Talentos inativo.');
        }

        $user = User::factory()->create();
        $user->assignRole('secretario-1');

        $this->actingAs($user)
            ->get(route('diretoria.talentos.dashboard'))
            ->assertOk();
    }

    public function test_lider_cannot_access_diretoria_finance_dashboard(): void
    {
        if (! module_enabled('Financeiro')) {
            $this->markTestSkipped('Módulo Financeiro inativo.');
        }

        $user = User::factory()->create();
        $user->assignRole('lider');

        $this->actingAs($user)
            ->get(route('diretoria.financeiro.dashboard'))
            ->assertForbidden();
    }
}
