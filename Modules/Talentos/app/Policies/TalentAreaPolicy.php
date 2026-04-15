<?php

namespace Modules\Talentos\App\Policies;

use App\Models\User;
use Modules\Talentos\App\Models\TalentArea;

class TalentAreaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function view(User $user, TalentArea $talentArea): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function create(User $user): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function update(User $user, TalentArea $talentArea): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }

    public function delete(User $user, TalentArea $talentArea): bool
    {
        return $user->can('talentos.taxonomy.manage');
    }
}
