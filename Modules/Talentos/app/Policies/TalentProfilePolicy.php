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

    public function validateYouthSkills(User $user, TalentProfile $talentProfile): bool
    {
        if (! $user->can('paineljovens.talentos.validate')) {
            return false;
        }

        $churchIds = $user->affiliatedChurchIds();
        if ($churchIds === []) {
            return false;
        }

        $youthChurchId = $talentProfile->user?->church_id;

        return $youthChurchId !== null && in_array((int) $youthChurchId, array_map('intval', $churchIds), true);
    }
}
