<?php

namespace Modules\Igrejas\App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Igrejas\App\Models\Church;
use Modules\Igrejas\App\Models\ChurchChangeRequest;

final class ChurchChangeRequestProcessor
{
    public static function approve(ChurchChangeRequest $request, User $reviewer, ?string $reviewNotes = null): ?Church
    {
        if (! $request->isSubmitted()) {
            throw ValidationException::withMessages([
                'request' => ['Pedido não está enviado para análise.'],
            ]);
        }

        $payload = $request->payload ?? [];

        return DB::transaction(function () use ($request, $reviewer, $reviewNotes, $payload) {
            $church = null;

            switch ($request->type) {
                case ChurchChangeRequest::TYPE_CREATE:
                    $data = static::onlyChurchFillable($payload);
                    $church = Church::create($data);
                    $church->refresh();
                    ChurchLeadershipSync::syncFromChurch($church);
                    $request->forceFill(['church_id' => $church->id])->save();
                    break;

                case ChurchChangeRequest::TYPE_UPDATE_PROFILE:
                    $church = $request->church;
                    if (! $church) {
                        throw ValidationException::withMessages(['church' => ['Igreja não encontrada.']]);
                    }
                    $church->update(static::onlyChurchFillable($payload));
                    $church->refresh();
                    ChurchLeadershipSync::syncFromChurch($church);
                    break;

                case ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE:
                    $church = $request->church;
                    if (! $church) {
                        throw ValidationException::withMessages(['church' => ['Igreja não encontrada.']]);
                    }
                    $church->update(static::onlyChurchFillable($payload, [
                        'pastor_user_id', 'unijovem_leader_user_id',
                    ]));
                    $church->refresh();
                    ChurchLeadershipSync::syncFromChurch($church);
                    break;

                case ChurchChangeRequest::TYPE_DEACTIVATE:
                    $church = $request->church;
                    if (! $church) {
                        throw ValidationException::withMessages(['church' => ['Igreja não encontrada.']]);
                    }
                    $church->update(['is_active' => false]);
                    break;

                default:
                    throw ValidationException::withMessages(['type' => ['Tipo de pedido inválido.']]);
            }

            $request->forceFill([
                'status' => ChurchChangeRequest::STATUS_APPROVED,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'review_notes' => $reviewNotes,
            ])->save();

            if ($church) {
                AuditLog::log(
                    'igrejas.request.approve',
                    Church::class,
                    $church->id,
                    'igrejas',
                    "Pedido #{$request->id} aprovado ({$request->type}).",
                    null,
                    ['request_id' => $request->id]
                );
            }

            return $church;
        });
    }

    public static function reject(ChurchChangeRequest $request, User $reviewer, ?string $reviewNotes = null): void
    {
        if (! $request->isSubmitted()) {
            throw ValidationException::withMessages([
                'request' => ['Só é possível recusar pedidos enviados.'],
            ]);
        }

        DB::transaction(function () use ($request, $reviewer, $reviewNotes) {
            $request->forceFill([
                'status' => ChurchChangeRequest::STATUS_REJECTED,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => now(),
                'review_notes' => $reviewNotes,
            ])->save();

            AuditLog::log(
                'igrejas.request.reject',
                ChurchChangeRequest::class,
                $request->id,
                'igrejas',
                "Pedido #{$request->id} recusado.",
                null,
                ['church_id' => $request->church_id, 'type' => $request->type]
            );
        });
    }

    /**
     * @param  list<string>|null  $only
     * @return array<string, mixed>
     */
    public static function onlyChurchFillable(array $payload, ?array $only = null): array
    {
        $church = new Church;
        $allowed = array_flip($only ?? $church->getFillable());
        $out = [];
        foreach ($payload as $key => $value) {
            if (isset($allowed[$key])) {
                $out[$key] = $value;
            }
        }

        return $out;
    }
}
