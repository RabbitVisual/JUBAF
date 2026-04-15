<?php

namespace Modules\Secretaria\App\Services;

use Modules\Secretaria\App\Models\Minute;

final class MinuteIntegrityHasher
{
    public function hashForMinute(Minute $minute, array $signerIds): string
    {
        sort($signerIds);

        $payload = [
            'minute_id' => (int) $minute->id,
            'uuid' => (string) ($minute->uuid ?? ''),
            'title' => (string) $minute->title,
            'content' => (string) $minute->content,
            'meeting_date' => $minute->meeting_date?->format('Y-m-d'),
            'signers' => array_values(array_map('intval', $signerIds)),
        ];

        return hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
