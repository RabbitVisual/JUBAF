<?php

namespace Tests\Feature\Modules;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\JubafSector;
use Modules\PainelJovens\App\Services\CensoService;
use Modules\Talentos\App\Models\TalentProfile;
use Modules\Talentos\App\Models\TalentSkill;
use Modules\Talentos\App\Services\BancoTalentosSearchService;
use Modules\Talentos\Database\Seeders\TalentosDatabaseSeeder;
use Tests\TestCase;

class CensoAndTalentSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesPermissionsSeeder::class);
    }

    public function test_censo_service_returns_summary_rows(): void
    {
        if (! module_enabled('Igrejas')) {
            $this->markTestSkipped('Módulo Igrejas inativo.');
        }

        $sector = JubafSector::query()->create([
            'name' => 'Setor Teste',
            'slug' => 'setor-teste',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $church = Church::query()->create([
            'name' => 'Igreja Teste',
            'kind' => Church::KIND_CHURCH,
            'jubaf_sector_id' => $sector->id,
            'is_active' => true,
        ]);

        $youth = User::factory()->create([
            'church_id' => $church->id,
            'birth_date' => now()->subYears(20),
        ]);
        $youth->assignRole('jovens');

        $service = app(CensoService::class);
        $summary = $service->youthSummaryBySector();

        $this->assertNotEmpty($summary);
        $sectorRow = collect($summary)->firstWhere('sector_id', $sector->id);
        $this->assertNotNull($sectorRow);
        $this->assertSame(1, $sectorRow['youth_count']);
        $this->assertIsFloat($sectorRow['average_age'] ?? 0.0);
    }

    public function test_top_validated_skills_counts_profiles(): void
    {
        if (! module_enabled('Talentos')) {
            $this->markTestSkipped('Módulo Talentos inativo.');
        }

        $this->seed(TalentosDatabaseSeeder::class);

        $skill = TalentSkill::query()->firstOrFail();
        $user = User::factory()->create();
        $user->assignRole('jovens');

        $profile = TalentProfile::query()->create([
            'user_id' => $user->id,
            'bio' => 'Bio',
            'is_searchable' => true,
        ]);

        $profile->skills()->attach($skill->id, [
            'level' => TalentSkill::LEVEL_BASIC,
            'validated_at' => now(),
            'validated_by' => $user->id,
        ]);

        $top = app(CensoService::class)->topValidatedSkills(10);
        $this->assertTrue($top->isNotEmpty());
        $this->assertSame($skill->id, (int) $top->first()->talent_skill_id);
    }

    public function test_banco_talentos_search_filters_by_sector(): void
    {
        if (! module_enabled('Igrejas') || ! module_enabled('Talentos')) {
            $this->markTestSkipped('Módulos necessários inativos.');
        }

        $this->seed(TalentosDatabaseSeeder::class);

        $sectorA = JubafSector::query()->create([
            'name' => 'Norte',
            'slug' => 'norte-test',
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $sectorB = JubafSector::query()->create([
            'name' => 'Sul',
            'slug' => 'sul-test',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $churchA = Church::query()->create([
            'name' => 'Igreja A',
            'kind' => Church::KIND_CHURCH,
            'jubaf_sector_id' => $sectorA->id,
            'is_active' => true,
        ]);
        $churchB = Church::query()->create([
            'name' => 'Igreja B',
            'kind' => Church::KIND_CHURCH,
            'jubaf_sector_id' => $sectorB->id,
            'is_active' => true,
        ]);

        $uA = User::factory()->create(['church_id' => $churchA->id]);
        $uB = User::factory()->create(['church_id' => $churchB->id]);
        TalentProfile::query()->create(['user_id' => $uA->id, 'is_searchable' => true]);
        TalentProfile::query()->create(['user_id' => $uB->id, 'is_searchable' => true]);

        $presidente = User::factory()->create();
        $presidente->assignRole('presidente');

        $q = TalentProfile::query()
            ->join('users', 'users.id', '=', 'talent_profiles.user_id')
            ->orderBy('users.name')
            ->select('talent_profiles.*');

        $request = request()->duplicate(['jubaf_sector_id' => $sectorA->id]);
        app(BancoTalentosSearchService::class)->applyDirectoryFilters($q, $request, $presidente);

        $ids = $q->pluck('user_id')->all();
        $this->assertContains($uA->id, $ids);
        $this->assertNotContains($uB->id, $ids);
    }
}
