<?php

namespace Modules\Chat\App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

/**
 * Presença de atendentes do chat (diretoria no painel) via heartbeat em cache.
 */
class ChatAgentPresence
{
    protected const CACHE_TTL_SECONDS = 120;

    protected const AGENT_IDS_CACHE_KEY = 'chat_agent_user_ids_v1';

    protected const AGENT_IDS_TTL_SECONDS = 600;

    public static function touch(int $userId): void
    {
        Cache::put(self::presenceKey($userId), true, now()->addSeconds(self::CACHE_TTL_SECONDS));
    }

    public static function countOnline(): int
    {
        $count = 0;
        foreach (self::agentUserIds() as $id) {
            if (Cache::has(self::presenceKey((int) $id))) {
                $count++;
            }
        }

        return $count;
    }

    public static function forgetAgentIdsCache(): void
    {
        Cache::forget(self::AGENT_IDS_CACHE_KEY);
    }

    protected static function presenceKey(int $userId): string
    {
        return 'chat_agent_presence_'.$userId;
    }

    /**
     * @return \Illuminate\Support\Collection<int, int>
     */
    protected static function agentUserIds()
    {
        return Cache::remember(self::AGENT_IDS_CACHE_KEY, self::AGENT_IDS_TTL_SECONDS, function () {
            return User::query()
                ->whereHas('roles', function ($q) {
                    $q->whereIn('name', jubaf_chat_agent_role_names());
                })
                ->pluck('id');
        });
    }
}
