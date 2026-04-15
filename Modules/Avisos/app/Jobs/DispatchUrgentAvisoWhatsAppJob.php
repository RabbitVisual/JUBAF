<?php

namespace Modules\Avisos\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Modules\Avisos\App\Models\Aviso;
use Modules\Avisos\App\Notifications\UrgentAvisoWhatsAppNotification;
use Modules\Avisos\App\Services\AvisoUrgentWhatsAppAudienceResolver;

class DispatchUrgentAvisoWhatsAppJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 3600;

    public function __construct(
        public int $avisoId
    ) {}

    public function uniqueId(): string
    {
        return 'aviso-urgent-wa-'.$this->avisoId;
    }

    public function handle(AvisoUrgentWhatsAppAudienceResolver $resolver): void
    {
        $aviso = Aviso::query()->find($this->avisoId);
        if (! $aviso || ! $aviso->modo_quadro || $aviso->classificacao !== 'urgente') {
            return;
        }

        if ($aviso->whatsapp_dispatched_at !== null) {
            return;
        }

        $users = $resolver->resolve($aviso);
        foreach ($users->chunk(50) as $chunk) {
            Notification::send($chunk, new UrgentAvisoWhatsAppNotification($aviso));
        }

        $aviso->forceFill(['whatsapp_dispatched_at' => now()])->saveQuietly();
    }
}
