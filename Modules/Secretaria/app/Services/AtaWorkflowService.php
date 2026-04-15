<?php

namespace Modules\Secretaria\App\Services;

use App\Models\User;
use App\Support\ErpChurchScope;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Secretaria\App\Events\AtaPublished;
use Modules\Secretaria\App\Models\Minute;
use Modules\Secretaria\App\Models\MinuteSignature;

final class AtaWorkflowService
{
    public function __construct(
        private readonly MinuteIntegrityHasher $hasher,
        private readonly PdfGenerationService $pdfGenerationService
    ) {
    }

    public function requestSignatures(Minute $minute): Minute
    {
        if ($minute->status !== 'draft') {
            throw new AuthorizationException('A ata já não está em rascunho.');
        }

        if (blank(strip_tags((string) $minute->content))) {
            throw new AuthorizationException('A ata não pode seguir para assinatura sem conteúdo.');
        }

        $minute->update(['status' => 'pending_signatures']);

        return $minute->refresh();
    }

    public function sign(Minute $minute, User $user, string $password, Request $request): Minute
    {
        if ($minute->status !== 'pending_signatures') {
            throw new AuthorizationException('A ata não está pendente de assinaturas.');
        }

        if (! Hash::check($password, (string) $user->password)) {
            throw new AuthorizationException('Senha inválida para assinatura.');
        }

        return DB::transaction(function () use ($minute, $user, $request): Minute {
            MinuteSignature::query()->updateOrCreate(
                [
                    'minute_id' => $minute->id,
                    'user_id' => $user->id,
                ],
                [
                    'role_at_the_time' => (string) ($user->roles()->orderBy('id')->value('name') ?? 'user'),
                    'ip_address' => $request->ip(),
                    'user_agent' => substr((string) $request->userAgent(), 0, 65000),
                    'signed_at' => now(),
                ]
            );

            $minute->refresh()->loadMissing('signatures');
            $signerIds = $minute->signatures->pluck('user_id')->map(fn ($id) => (int) $id)->all();
            $hash = $this->hasher->hashForMinute($minute, $signerIds);
            $minute->forceFill(['document_hash' => $hash])->saveQuietly();

            if ($this->allRequiredSignersSigned($minute)) {
                $publishedAt = now();
                $minute->forceFill([
                    'status' => 'published',
                    'published_at' => $publishedAt,
                    'locked_at' => $publishedAt,
                    'content_checksum' => $hash,
                ])->saveQuietly();

                $this->pdfGenerationService->generateAndStore($minute->refresh());
                event(new AtaPublished($minute->refresh(), $user));
            }

            return $minute->refresh();
        });
    }

    public function pendingMinutesCount(User $user): int
    {
        return $this->basePendingMinutesQuery($user)->count();
    }

    /**
     * @return Collection<int, Minute>
     */
    public function pendingMinutesForUser(User $user, int $limit = 8): Collection
    {
        return $this->basePendingMinutesQuery($user)
            ->with(['creator', 'church'])
            ->orderByDesc('updated_at')
            ->limit($limit)
            ->get();
    }

    private function allRequiredSignersSigned(Minute $minute): bool
    {
        $roles = collect((array) config('secretaria.required_minute_signers', ['presidente', 'secretario-1']));
        if ($roles->isEmpty()) {
            return true;
        }

        $signedRoles = $minute->signatures()
            ->pluck('role_at_the_time')
            ->map(fn (string $role) => strtolower($role))
            ->unique();

        return $roles
            ->map(fn (string $role) => strtolower($role))
            ->every(fn (string $role) => $signedRoles->contains($role));
    }

    private function basePendingMinutesQuery(User $user)
    {
        $query = Minute::query()->where('status', 'pending_signatures');
        ErpChurchScope::applyToSecretariaMinuteQuery($query, $user);

        return $query;
    }
}
