<?php

namespace Tests\Feature\Diretoria;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\JubafSector;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PainelDiretoriaKpiScopeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    #[Test]
    public function vice_president_sees_only_own_sector_kpis(): void
    {
        $sectorA = JubafSector::query()->create([
            'name' => 'Setor Leste',
            'slug' => 'setor-leste',
            'is_active' => true,
        ]);
        $sectorB = JubafSector::query()->create([
            'name' => 'Setor Oeste',
            'slug' => 'setor-oeste',
            'is_active' => true,
        ]);

        $churchA = Church::query()->create([
            'name' => 'Igreja Leste',
            'kind' => Church::KIND_CHURCH,
            'cnpj' => '01234567000189',
            'jubaf_sector_id' => $sectorA->id,
        ]);
        $churchB = Church::query()->create([
            'name' => 'Igreja Oeste',
            'kind' => Church::KIND_CHURCH,
            'cnpj' => '98234567000189',
            'jubaf_sector_id' => $sectorB->id,
        ]);

        $vice = User::factory()->create(['jubaf_sector_id' => $sectorA->id]);
        $vice->assignRole('vice-presidente-1');

        $jovensRole = Role::query()->where('name', 'jovens')->firstOrFail();
        User::factory()->count(3)->create(['church_id' => $churchA->id])->each(fn (User $user) => $user->assignRole($jovensRole));
        User::factory()->count(8)->create(['church_id' => $churchB->id])->each(fn (User $user) => $user->assignRole($jovensRole));

        $category = FinCategory::query()->create([
            'name' => 'Contribuicoes',
            'direction' => 'in',
            'is_active' => true,
        ]);

        FinTransaction::query()->create([
            'category_id' => $category->id,
            'occurred_on' => now()->startOfMonth()->addDay()->toDateString(),
            'amount' => 100,
            'direction' => 'in',
            'scope' => FinTransaction::SCOPE_CHURCH,
            'church_id' => $churchA->id,
            'status' => FinTransaction::STATUS_PAID,
            'source' => FinTransaction::SOURCE_MANUAL,
        ]);
        FinTransaction::query()->create([
            'category_id' => $category->id,
            'occurred_on' => now()->startOfMonth()->addDay()->toDateString(),
            'amount' => 20,
            'direction' => 'out',
            'scope' => FinTransaction::SCOPE_CHURCH,
            'church_id' => $churchA->id,
            'status' => FinTransaction::STATUS_PAID,
            'source' => FinTransaction::SOURCE_MANUAL,
        ]);
        FinTransaction::query()->create([
            'category_id' => $category->id,
            'occurred_on' => now()->startOfMonth()->addDay()->toDateString(),
            'amount' => 999,
            'direction' => 'in',
            'scope' => FinTransaction::SCOPE_CHURCH,
            'church_id' => $churchB->id,
            'status' => FinTransaction::STATUS_PAID,
            'source' => FinTransaction::SOURCE_MANUAL,
        ]);

        $this->actingAs($vice)
            ->get(route('diretoria.dashboard'))
            ->assertOk()
            ->assertSee('R$ 80,00')
            ->assertSee('Setor Leste')
            ->assertDontSee('Setor Oeste');
    }

    #[Test]
    public function diretoria_user_without_finance_permission_does_not_see_finance_kpis(): void
    {
        Role::firstOrCreate(['name' => 'co-admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('co-admin');

        $this->actingAs($user)
            ->get(route('diretoria.dashboard'))
            ->assertOk()
            ->assertSee('Bloco financeiro indisponivel')
            ->assertDontSee('Receita do mes');
    }
}
