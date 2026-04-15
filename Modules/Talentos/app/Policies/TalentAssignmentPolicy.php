<?php

namespace Modules\Talentos\App\Policies;

use App\Models\User;
use Modules\Talentos\App\Models\TalentAssignment;

class TalentAssignmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('talentos.assignments.view');
    }

    public function view(User $user, TalentAssignment $talentAssignment): bool
    {
        return $user->can('talentos.assignments.view');
    }

    public function create(User $user): bool
    {
        return $user->can('talentos.assignments.create');
    }

    public function update(User $user, TalentAssignment $talentAssignment): bool
    {
        return $user->can('talentos.assignments.edit');
    }

    public function delete(User $user, TalentAssignment $talentAssignment): bool
    {
        return $user->can('talentos.assignments.delete');
    }

    /**
     * O próprio membro pode responder a um convite pendente (confirmar ou declinar).
     */
    public function respond(User $user, TalentAssignment $talentAssignment): bool
    {
        if ($user->id !== $talentAssignment->user_id) {
            return false;
        }

        return $talentAssignment->status === TalentAssignment::STATUS_INVITED;
    }
}
