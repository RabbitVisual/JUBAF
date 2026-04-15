<?php

namespace Modules\Avisos\App\Services;

use App\Models\User;
use App\Support\JubafRoleRegistry;
use Illuminate\Support\Collection;
use Modules\Avisos\App\Models\Aviso;

final class AvisoUrgentWhatsAppAudienceResolver
{
    /**
     * Utilizadores com telefone para alerta WhatsApp (espelho do aviso urgente).
     *
     * @return Collection<int, User>
     */
    public function resolve(Aviso $aviso): Collection
    {
        $target = $aviso->target_role;

        $q = User::query()
            ->where('active', true)
            ->whereNotNull('phone')
            ->where('phone', '!=', '');

        if ($target === null || $target === '' || $target === 'all') {
            $roles = array_merge(
                ['lider', 'pastor'],
                JubafRoleRegistry::directorateRoleNames(),
            );
            $q->whereHas('roles', fn ($r) => $r->whereIn('name', array_unique($roles)));

            return $q->orderBy('id')->get();
        }

        $q->whereHas('roles', fn ($r) => $r->where('name', $target));

        return $q->orderBy('id')->get();
    }
}
