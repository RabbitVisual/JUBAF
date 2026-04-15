<?php

namespace Modules\Talentos\App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Talentos\App\Models\TalentProfile;

class BancoTalentosSearchService
{
    /**
     * @param  Builder<TalentProfile>  $q
     */
    public function applyDirectoryFilters(Builder $q, Request $request, ?User $viewer = null): void
    {
        if ($request->filled('church_id') && module_enabled('Igrejas')) {
            $q->where('users.church_id', $request->integer('church_id'));
        }

        if ($request->filled('jubaf_sector_id') && module_enabled('Igrejas')) {
            $sectorId = $request->integer('jubaf_sector_id');
            $q->whereHas('user.church', function ($c) use ($sectorId): void {
                $c->where('jubaf_sector_id', $sectorId);
            });
        }

        if ($request->filled('skill_id')) {
            $skillId = $request->integer('skill_id');
            $q->whereExists(function ($sub) use ($skillId) {
                $sub->selectRaw('1')
                    ->from('talent_profile_skill')
                    ->whereColumn('talent_profile_skill.talent_profile_id', 'talent_profiles.id')
                    ->where('talent_profile_skill.talent_skill_id', $skillId);
            });
        }

        if ($request->filled('area_id')) {
            $areaId = $request->integer('area_id');
            $q->whereExists(function ($sub) use ($areaId) {
                $sub->selectRaw('1')
                    ->from('talent_profile_area')
                    ->whereColumn('talent_profile_area.talent_profile_id', 'talent_profiles.id')
                    ->where('talent_profile_area.talent_area_id', $areaId);
            });
        }

        if ($request->boolean('searchable_only')) {
            $q->where('talent_profiles.is_searchable', true);
        }

        if ($request->boolean('validated_skills_only')) {
            $q->whereExists(function ($sub) {
                $sub->selectRaw('1')
                    ->from('talent_profile_skill')
                    ->whereColumn('talent_profile_skill.talent_profile_id', 'talent_profiles.id')
                    ->whereNotNull('talent_profile_skill.validated_at');
            });
        }

        $term = trim((string) $request->input('q', ''));
        if ($term !== '') {
            $like = '%'.addcslashes($term, '%_\\').'%';
            $q->where(function ($w) use ($like) {
                $w->where('users.name', 'like', $like)
                    ->orWhere('users.email', 'like', $like);
            });
        }

        if ($viewer && $viewer->restrictsChurchDirectoryToSector() && $viewer->jubaf_sector_id) {
            $sid = (int) $viewer->jubaf_sector_id;
            $q->whereHas('user.church', function ($c) use ($sid): void {
                $c->where('jubaf_sector_id', $sid);
            });
        }
    }
}
