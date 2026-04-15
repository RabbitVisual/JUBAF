<?php

namespace Modules\Secretaria\App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Modules\Igrejas\App\Models\Church;

final class AtaWhatsAppAudienceResolver
{
    /**
     * @return Collection<int, User>
     */
    public function resolve(): Collection
    {
        return User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['pastor', 'lider']))
            ->where(function ($query) {
                $query->whereHas('church', function ($q) {
                    $q->where('is_active', true)->where('crm_status', Church::CRM_ATIVA);
                })->orWhereHas('assignedChurches', function ($q) {
                    $q->where('is_active', true)->where('crm_status', Church::CRM_ATIVA);
                });
            })
            ->orderBy('id')
            ->get()
            ->unique('id')
            ->values();
    }
}
