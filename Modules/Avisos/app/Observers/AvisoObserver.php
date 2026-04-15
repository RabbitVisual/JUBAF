<?php

namespace Modules\Avisos\App\Observers;

use Modules\Avisos\App\Jobs\DispatchUrgentAvisoWhatsAppJob;
use Modules\Avisos\App\Models\Aviso;

class AvisoObserver
{
    public function saved(Aviso $aviso): void
    {
        if (! $aviso->modo_quadro || $aviso->classificacao !== 'urgente') {
            return;
        }

        if ($aviso->whatsapp_dispatched_at !== null) {
            return;
        }

        DispatchUrgentAvisoWhatsAppJob::dispatch($aviso->id)->afterCommit();
    }
}
