<?php

namespace Modules\Igrejas\App\Services;

use Illuminate\Support\Facades\DB;
use Modules\Igrejas\App\Events\ChurchSectorAssigned;
use Modules\Igrejas\App\Events\IgrejaAtualizada;
use Modules\Igrejas\App\Events\IgrejaRegistrada;
use Modules\Igrejas\App\Models\Church;

class ChurchService
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function createChurch(array $attributes): Church
    {
        return DB::transaction(function () use ($attributes) {
            $church = Church::create($attributes);
            $church->refresh();
            ChurchLeadershipSync::syncFromChurch($church);

            if ($church->jubaf_sector_id) {
                event(new ChurchSectorAssigned($church, null));
            }

            IgrejaRegistrada::dispatch($church);

            return $church;
        });
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function updateChurch(Church $church, array $attributes): Church
    {
        return DB::transaction(function () use ($church, $attributes) {
            $originalCrm = $church->crm_status;
            $previousSectorId = $church->jubaf_sector_id;

            $church->update($attributes);
            $church->refresh();
            ChurchLeadershipSync::syncFromChurch($church);

            if ((int) $church->jubaf_sector_id !== (int) $previousSectorId) {
                event(new ChurchSectorAssigned($church, $previousSectorId));
            }

            IgrejaAtualizada::dispatch($church, $originalCrm);

            return $church;
        });
    }
}
