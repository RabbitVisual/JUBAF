<?php

namespace Modules\PainelJovens\App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Igrejas\App\Models\JubafSector;
use Spatie\Permission\Models\Role;

class CensoService
{
    /**
     * Resumo por setor JUBAF: contagem de jovens (role jovens), idade média e faixas etárias.
     *
     * @return list<array{sector_id: int|null, sector_name: string, youth_count: int, average_age: float|null, age_buckets: array<string, int>}>
     */
    public function youthSummaryBySector(): array
    {
        $jovensRole = Role::query()->where('name', 'jovens')->where('guard_name', 'web')->first();
        if (! $jovensRole) {
            return [];
        }

        $sectors = JubafSector::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $out = [];

        $globalUsers = $this->jovensUsersQuery($jovensRole->id);
        $out[] = $this->summarizeGroup('Geral (todos os setores)', null, $globalUsers);

        foreach ($sectors as $sector) {
            $q = $this->jovensUsersQuery($jovensRole->id);
            $q->whereHas('church', function ($c) use ($sector): void {
                $c->where('jubaf_sector_id', $sector->id);
            });
            $out[] = $this->summarizeGroup($sector->name, $sector->id, $q);
        }

        return $out;
    }

    /**
     * Competências mais frequentes entre perfis pesquisáveis com pelo menos uma competência validada.
     *
     * @return Collection<int, object{talent_skill_id: int, name: string, profile_count: int}>
     */
    public function topValidatedSkills(int $limit = 12): Collection
    {
        return DB::table('talent_profile_skill')
            ->join('talent_skills', 'talent_skills.id', '=', 'talent_profile_skill.talent_skill_id')
            ->join('talent_profiles', 'talent_profiles.id', '=', 'talent_profile_skill.talent_profile_id')
            ->whereNotNull('talent_profile_skill.validated_at')
            ->where('talent_profiles.is_searchable', true)
            ->groupBy('talent_skills.id', 'talent_skills.name')
            ->orderByDesc(DB::raw('count(distinct talent_profiles.id)'))
            ->limit($limit)
            ->selectRaw('talent_skills.id as talent_skill_id, talent_skills.name as name, count(distinct talent_profiles.id) as profile_count')
            ->get();
    }

    /**
     * @param  Builder<User>  $usersQuery
     * @return array{sector_id: int|null, sector_name: string, youth_count: int, average_age: float|null, age_buckets: array<string, int>}
     */
    protected function summarizeGroup(string $label, ?int $sectorId, $usersQuery): array
    {
        $users = (clone $usersQuery)
            ->whereNotNull('birth_date')
            ->get(['birth_date']);

        $allCount = (clone $usersQuery)->count();

        $ages = $users->map(fn (User $u) => Carbon::parse($u->birth_date)->age)->filter(fn ($a) => $a >= 0 && $a < 120);

        $avg = $ages->isEmpty() ? null : round($ages->avg(), 1);

        $buckets = [
            '0-17' => 0,
            '18-24' => 0,
            '25-34' => 0,
            '35+' => 0,
        ];
        foreach ($ages as $age) {
            if ($age <= 17) {
                $buckets['0-17']++;
            } elseif ($age <= 24) {
                $buckets['18-24']++;
            } elseif ($age <= 34) {
                $buckets['25-34']++;
            } else {
                $buckets['35+']++;
            }
        }

        return [
            'sector_id' => $sectorId,
            'sector_name' => $label,
            'youth_count' => $allCount,
            'average_age' => $avg,
            'age_buckets' => $buckets,
        ];
    }

    /**
     * @return Builder<User>
     */
    protected function jovensUsersQuery(int $jovensRoleId)
    {
        return User::query()
            ->whereHas('roles', function ($r) use ($jovensRoleId): void {
                $r->where('roles.id', $jovensRoleId);
            });
    }
}
