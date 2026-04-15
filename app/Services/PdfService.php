<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfService
{
    /**
     * Gera um PDF a partir de uma view Blade e devolve resposta de download.
     *
     * @param  array<int, float|int>  $margins  Reservado para compatibilidade com chamadas existentes; margens efetivas vêm do CSS da view (@page).
     */
    public function downloadView(
        string $view,
        array $data,
        string $filename,
        string $paper = 'A4',
        string $orientation = 'portrait',
        array $margins = [15, 15, 15, 15]
    ): Response {
        $pdf = Pdf::loadView($view, $data);

        $orient = strtolower($orientation) === 'landscape' ? 'landscape' : 'portrait';
        $pdf->setPaper($paper, $orient);

        return $pdf->download($filename);
    }
}
