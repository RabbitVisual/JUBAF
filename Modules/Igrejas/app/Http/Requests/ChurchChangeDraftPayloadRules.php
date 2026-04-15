<?php

namespace Modules\Igrejas\App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;

final class ChurchChangeDraftPayloadRules
{
    /** @var list<string> */
    public const CONTEXT_KEYS = ['reason', 'leader_notes'];

    /**
     * @return list<string>
     */
    public static function allowedPayloadKeysForType(string $type): array
    {
        $fillable = (new Church)->getFillable();
        $profile = array_values(array_diff($fillable, ['slug', 'is_active', 'metadata']));

        return match ($type) {
            ChurchChangeRequest::TYPE_CREATE,
            ChurchChangeRequest::TYPE_UPDATE_PROFILE => $profile,
            ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE => ['pastor_user_id', 'unijovem_leader_user_id'],
            ChurchChangeRequest::TYPE_DEACTIVATE => [],
            default => [],
        };
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public static function normalizePayload(array $payload, string $type): array
    {
        $allowed = array_merge(self::allowedPayloadKeysForType($type), self::CONTEXT_KEYS);
        $out = [];
        foreach ($payload as $key => $value) {
            if (! in_array($key, $allowed, true)) {
                continue;
            }
            if ($value === '' || $value === null) {
                continue;
            }
            if (in_array($key, ['pastor_user_id', 'unijovem_leader_user_id'], true)) {
                $out[$key] = (int) $value;

                continue;
            }
            $out[$key] = $value;
        }

        return $out;
    }

    /**
     * @return list<string>
     */
    public static function churchIdRules(): array
    {
        $create = ChurchChangeRequest::TYPE_CREATE;

        return [
            'required_unless:type,'.$create,
            'nullable',
            'integer',
            'exists:igrejas_churches,id',
            'prohibited_if:type,'.$create,
        ];
    }

    /**
     * @return array<string, list<mixed>|string>
     */
    public static function rulesForStore(string $type): array
    {
        $base = [
            'type' => ['required', 'string', Rule::in(ChurchChangeRequest::types())],
            'church_id' => self::churchIdRules(),
            'payload' => ['required', 'array'],
        ];

        return array_merge($base, self::payloadKeyRules($type));
    }

    /**
     * @return array<string, list<mixed>|string>
     */
    public static function rulesForUpdate(string $type): array
    {
        $base = [
            'payload' => ['required', 'array'],
        ];

        return array_merge($base, self::payloadKeyRules($type));
    }

    /**
     * @return array<string, list<mixed>|string>
     */
    private static function payloadKeyRules(string $type): array
    {
        $profile = [
            'payload.name' => ['nullable', 'string', 'max:255'],
            'payload.city' => ['nullable', 'string', 'max:120'],
            'payload.address' => ['nullable', 'string', 'max:500'],
            'payload.phone' => ['nullable', 'string', 'max:80'],
            'payload.email' => ['nullable', 'email', 'max:255'],
            'payload.sector' => ['nullable', 'string', 'max:120'],
            'payload.foundation_date' => ['nullable', 'date'],
            'payload.joined_at' => ['nullable', 'date'],
            'payload.cooperation_status' => ['nullable', 'string', Rule::in(Church::cooperationStatuses())],
            'payload.asbaf_notes' => ['nullable', 'string', 'max:5000'],
            'payload.pastor_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'payload.unijovem_leader_user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];

        $notes = [
            'payload.leader_notes' => ['nullable', 'string', 'max:2000'],
            'payload.reason' => ['nullable', 'string', 'max:2000'],
        ];

        return match ($type) {
            ChurchChangeRequest::TYPE_CREATE => array_merge($profile, $notes, [
                'payload.name' => ['required', 'string', 'max:255'],
            ]),
            ChurchChangeRequest::TYPE_UPDATE_PROFILE => array_merge($profile, $notes),
            ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE => [
                'payload.pastor_user_id' => ['nullable', 'integer', 'exists:users,id'],
                'payload.unijovem_leader_user_id' => ['nullable', 'integer', 'exists:users,id'],
                'payload.leader_notes' => ['nullable', 'string', 'max:2000'],
            ],
            ChurchChangeRequest::TYPE_DEACTIVATE => [
                'payload.reason' => ['nullable', 'string', 'max:2000'],
                'payload.leader_notes' => ['nullable', 'string', 'max:2000'],
            ],
            default => $notes,
        };
    }

    /**
     * @return list<string>
     */
    public static function profilePayloadKeys(): array
    {
        return [
            'name', 'city', 'address', 'phone', 'email', 'sector',
            'foundation_date', 'cooperation_status', 'joined_at', 'asbaf_notes',
        ];
    }

    public static function assertPayloadMeetsType(Validator $validator, string $type, array $payload): void
    {
        if ($type === ChurchChangeRequest::TYPE_UPDATE_PROFILE) {
            $keys = self::profilePayloadKeys();
            $has = collect($keys)->contains(fn (string $k) => filled($payload[$k] ?? null));
            if (! $has) {
                $validator->errors()->add('payload', 'Indique pelo menos um campo da ficha a atualizar.');
            }
        }

        if ($type === ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE) {
            $has = filled($payload['pastor_user_id'] ?? null)
                || filled($payload['unijovem_leader_user_id'] ?? null);
            if (! $has) {
                $validator->errors()->add('payload', 'Indique o pastor e/ou o líder Unijovem a atribuir.');
            }
        }
    }
}
