<?php

namespace Modules\Talentos\App\Policies;

use App\Models\User;
use Modules\Talentos\App\Models\TalentProfile;

class TalentProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('talentos.directory.view');
    }

    public function view(User $user, TalentProfile $talentProfile): bool
    {
        if ($user->id === $talentProfile->user_id) {
            return true;
        }

        return $user->can('talentos.directory.view');
    }

    public function update(User $user, TalentProfile $talentProfile): bool
    {
        return $user->id === $talentProfile->user_id && $user->can('talentos.profile.edit');
    }
}
