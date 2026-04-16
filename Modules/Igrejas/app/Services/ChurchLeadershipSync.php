<?php

namespace Modules\Igrejas\App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Igrejas\App\Events\LeaderAssignedToChurch;
use Modules\Igrejas\App\Models\Church;

final class ChurchLeadershipSync
{
    public const PIVOT_ROLE_PASTOR = 'pastor';

    public const PIVOT_ROLE_UNIJOVEM = 'lider_unijovem';

    /**
     * Mantém pivot user_churches e church_id principal do líder Unijovem alinhados aos FK da igreja.
     */
    public static function syncFromChurch(Church $church): void
    {
        if (! Schema::hasTable('user_churches')) {
            return;
        }

        DB::transaction(function () use ($church) {
            $churchId = (int) $church->id;

            DB::table('user_churches')
                ->where('church_id', $churchId)
                ->whereIn('role_on_church', [self::PIVOT_ROLE_PASTOR, self::PIVOT_ROLE_UNIJOVEM])
                ->delete();

            if ($church->pastor_user_id) {
                DB::table('user_churches')->updateOrInsert(
                    [
                        'user_id' => (int) $church->pastor_user_id,
                        'church_id' => $churchId,
                    ],
                    [
                        'role_on_church' => self::PIVOT_ROLE_PASTOR,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            if ($church->unijovem_leader_user_id) {
                DB::table('user_churches')->updateOrInsert(
                    [
                        'user_id' => (int) $church->unijovem_leader_user_id,
                        'church_id' => $churchId,
                    ],
                    [
                        'role_on_church' => self::PIVOT_ROLE_UNIJOVEM,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                User::query()->whereKey($church->unijovem_leader_user_id)->update([
                    'church_id' => $churchId,
                ]);
            }
        });
    }

    /**
     * Dispara LeaderAssignedToChurch quando pastor ou líder Unijovem passa a estar vinculado à igreja
     * (novo id diferente do anterior). Não dispara remoções.
     *
     * @param  mixed  $previousUnijovemLeaderId  id antes da alteração (null se criação)
     */
    public static function dispatchLeaderAssignedEventsForChurch(Church $church, mixed $previousUnijovemLeaderId, mixed $previousPastorUserId): void
    {
        $churchId = (int) $church->id;
        $norm = static function (mixed $v): ?int {
            if ($v === null || $v === '') {
                return null;
            }

            return (int) $v;
        };

        $prevLeader = $norm($previousUnijovemLeaderId);
        $prevPastor = $norm($previousPastorUserId);
        $newLeader = $norm($church->unijovem_leader_user_id);
        $newPastor = $norm($church->pastor_user_id);

        $userIdsToNotify = [];
        if ($newLeader !== null && $newLeader !== $prevLeader) {
            $userIdsToNotify[$newLeader] = true;
        }
        if ($newPastor !== null && $newPastor !== $prevPastor) {
            $userIdsToNotify[$newPastor] = true;
        }

        foreach (array_keys($userIdsToNotify) as $userId) {
            $user = User::query()->find($userId);
            if ($user) {
                event(new LeaderAssignedToChurch($user, $churchId));
            }
        }
    }
}
