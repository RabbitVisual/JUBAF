<?php

namespace App\Observers;

use App\Models\User;
use Modules\Treasury\App\Models\TreasuryPermission;

class UserObserver
{
    public function saved(User $user): void
    {
        if (! $user->wasRecentlyCreated && ! $user->wasChanged('role_id')) {
            return;
        }

        $user->loadMissing('role');
        $slug = $user->role?->slug;

        if ($slug === 'tesoureiro_1') {
            TreasuryPermission::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'permission_level' => 'admin',
                    'can_view_reports' => true,
                    'can_create_entries' => true,
                    'can_edit_entries' => true,
                    'can_delete_entries' => true,
                    'can_manage_campaigns' => true,
                    'can_manage_goals' => true,
                    'can_export_data' => true,
                ]
            );
        } elseif ($slug === 'tesoureiro_2') {
            TreasuryPermission::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'permission_level' => 'editor',
                    'can_view_reports' => true,
                    'can_create_entries' => true,
                    'can_edit_entries' => true,
                    'can_delete_entries' => false,
                    'can_manage_campaigns' => true,
                    'can_manage_goals' => true,
                    'can_export_data' => true,
                ]
            );
        }
    }
}
