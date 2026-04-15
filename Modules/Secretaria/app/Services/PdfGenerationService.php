<?php

namespace Modules\Secretaria\App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Modules\Secretaria\App\Models\Minute;

final class PdfGenerationService
{
    public function generateAndStore(Minute $minute): string
    {
        $minute->loadMissing(['meeting', 'church', 'creator', 'approvedBy', 'attachments', 'signatures.user']);

        $path = 'secretaria/minutes/pdf/ata-'.$minute->id.'-'.now()->format('YmdHis').'.pdf';

        $pdf = Pdf::loadView('secretaria::components.minute-pdf', [
            'minute' => $minute,
            'signatures' => $minute->signatures,
        ]);

        Storage::disk('local')->put($path, $pdf->output());

        $minute->forceFill(['pdf_path' => $path])->saveQuietly();

        return $path;
    }
}
