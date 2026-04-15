<?php

namespace Modules\Igrejas\App\Listeners;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Schema;
use Modules\Igrejas\App\Events\ChurchSectorAssigned;

/**
 * Trilha de auditoria para mudanças de setor (ERP).
 */
class LogChurchSectorAssignment
{
    public function handle(ChurchSectorAssigned $event): void
    {
        if (! Schema::hasTable('audit_logs')) {
            return;
        }

        $church = $event->church;

        AuditLog::log(
            'igrejas.church.sector_assigned',
            \Modules\Igrejas\App\Models\Church::class,
            $church->id,
            'igrejas',
            'Setor associacional atualizado para «'.$church->name.'».',
            ['previous_jubaf_sector_id' => $event->previousJubafSectorId],
            ['jubaf_sector_id' => $church->jubaf_sector_id, 'sector' => $church->sector]
        );
    }
}
