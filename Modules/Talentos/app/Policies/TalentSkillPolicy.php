<?php

namespace Modules\Talentos\App\Policies;

use App\Models\User;
use Modules\Talentos\App\Models\TalentSkill;

class TalentSkillPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function view(User $user, TalentSkill $talentSkill): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function update(User $user, TalentSkill $talentSkill): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function delete(User $user, TalentSkill $talentSkill): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }
}
