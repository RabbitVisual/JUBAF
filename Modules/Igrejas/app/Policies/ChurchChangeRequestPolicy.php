<?php

namespace Modules\Igrejas\App\Policies;

use App\Models\User;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;

class ChurchChangeRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return ChurchPolicy::canBrowseAllChurches($user)
            || $user->can('igrejas.requests.submit');
    }

    public function view(User $user, ChurchChangeRequest $request): bool
    {
        if (ChurchPolicy::canBrowseAllChurches($user)) {
            return true;
        }

        if ($user->can('igrejas.requests.submit') && (int) $request->submitted_by === (int) $user->id) {
            return true;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('igrejas.requests.submit');
    }

    public function update(User $user, ChurchChangeRequest $request): bool
    {
        if (! $request->isDraft()) {
            return false;
        }

        if (ChurchPolicy::canBrowseAllChurches($user)) {
            return true;
        }

        return $user->can('igrejas.requests.submit')
            && (int) $request->submitted_by === (int) $user->id;
    }

    public function submit(User $user, ChurchChangeRequest $request): bool
    {
        if (! $request->isDraft()) {
            return false;
        }

        if (ChurchPolicy::canBrowseAllChurches($user)) {
            return true;
        }

        if (! $user->can('igrejas.requests.submit')) {
            return false;
        }

        if ((int) $request->submitted_by !== (int) $user->id) {
            return false;
        }

        return self::userMayActOnRequestChurch($user, $request);
    }

    public function review(User $user, ChurchChangeRequest $request): bool
    {
        if (! $user->can('igrejas.requests.review')) {
            return false;
        }

        return ChurchPolicy::canBrowseAllChurches($user);
    }

    /**
     * Líder só pede alterações para igrejas com que está afiliado (exceto rascunho “nova igreja”).
     */
    public static function userMayActOnRequestChurch(User $user, ChurchChangeRequest $request): bool
    {
        if (ChurchPolicy::canBrowseAllChurches($user)) {
            return true;
        }

        if ($request->type === ChurchChangeRequest::TYPE_CREATE && $request->church_id === null) {
            return $user->hasRole('lider');
        }

        if ($request->church_id === null) {
            return false;
        }

        $church = Church::query()->find($request->church_id);

        return $church && in_array((int) $church->id, $user->affiliatedChurchIds(), true);
    }
}
