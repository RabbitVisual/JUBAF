<?php

namespace App\Traits;

use DateTime;
use Carbon\Carbon;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Barryvdh\DomPDF\Facade\Pdf;

trait ExportsData
{
    /**
     * Exporta dados para CSV com formatação profissional e institucionalizada
     */
    public function exportCsv($data, array $columns, string $filename, string $title = null): StreamedResponse
    {
        // Converter para Collection se necessário
        if (!$data instanceof \Illuminate\Support\Collection) {
            $data = collect($data);
        }

        // Se não fornecido, gerar título a partir do filename
        if (!$title) {
            $title = ucwords(str_replace(['_', '-'], ' ', $filename));
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data, $columns, $title) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // ============================================
            // CABEÇALHO INSTITUCIONAL
            // ============================================
            fputcsv($file, ['JUBAF — plataforma modular'], ';');
            fputcsv($file, [''], ';'); // Linha em branco

            // Título do Relatório
            fputcsv($file, [$title], ';');
            fputcsv($file, [''], ';'); // Linha em branco

            // Informações de geração
            $dataGeracao = now()->format('d/m/Y H:i:s');
            $usuario = 'Sistema';
            try {
                if (auth()->check() && auth()->user()) {
                    $usuario = auth()->user()->name ?? 'Sistema';
                }
            } catch (\Exception $e) {
                // Se não houver autenticação, usar 'Sistema'
            }
            fputcsv($file, ['Data/Hora de Geração:', $dataGeracao], ';');
            fputcsv($file, ['Gerado por:', $usuario], ';');
            fputcsv($file, ['Total de Registros:', $data->count()], ';');
            fputcsv($file, [''], ';'); // Linha em branco
            fputcsv($file, ['=' . str_repeat('=', 80)], ';'); // Separador
            fputcsv($file, [''], ';'); // Linha em branco

            // ============================================
            // CABEÇALHOS DAS COLUNAS
            // ============================================
            fputcsv($file, array_values($columns), ';');

            // ============================================
            // DADOS
            // ============================================
            foreach ($data as $row) {
                $csvRow = [];
                foreach (array_keys($columns) as $key) {
                    $value = is_object($row) ? ($row->{$key} ?? null) : ($row[$key] ?? null);
                    $csvRow[] = $this->formatValueForCsv($value);
                }
                fputcsv($file, $csvRow, ';');
            }

            // ============================================
            // RODAPÉ INSTITUCIONAL
            // ============================================
            fputcsv($file, [''], ';'); // Linha em branco
            fputcsv($file, ['=' . str_repeat('=', 80)], ';'); // Separador
            fputcsv($file, [''], ';'); // Linha em branco
            fputcsv($file, ['Este documento foi gerado automaticamente pelo sistema JUBAF.'], ';');
            fputcsv($file, ['Para mais informações, entre em contato com a administração do sistema.'], ';');
            fputcsv($file, [''], ';'); // Linha em branco
            fputcsv($file, ['© ' . date('Y') . ' — JUBAF. Todos os direitos reservados.'], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exporta dados para Excel
     */
    public function exportExcel($data, array $columns, string $filename): BinaryFileResponse
    {
        // Converter para Collection se necessário
        if (!$data instanceof \Illuminate\Support\Collection) {
            $data = collect($data);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Estilo do cabeçalho
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '28a745']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];

        // Cabeçalhos
        $col = 1;
        foreach (array_values($columns) as $header) {
            $cellCoordinate = Coordinate::stringFromColumnIndex($col) . '1';
            $sheet->setCellValue($cellCoordinate, $header);
            $sheet->getStyle($cellCoordinate)->applyFromArray($headerStyle);
            $col++;
        }

        // Dados
        $row = 2;
        foreach ($data as $item) {
            $col = 1;
            foreach (array_keys($columns) as $key) {
                $value = is_object($item) ? ($item->{$key} ?? null) : ($item[$key] ?? null);
                $cellCoordinate = Coordinate::stringFromColumnIndex($col) . $row;
                $sheet->setCellValue($cellCoordinate, $this->formatValueForExcel($value));
                $col++;
            }
            $row++;
        }

        // Ajustar largura das colunas
        foreach (range(1, count($columns)) as $col) {
            $columnLetter = Coordinate::stringFromColumnIndex($col);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'export_');
        $writer->save($tempFile);

        return response()->download($tempFile, "{$filename}.xlsx")->deleteFileAfterSend(true);
    }

    /**
     * Exporta dados para PDF
     */
    public function exportPdf($data, array $columns, string $filename, string $title = 'Relatório')
    {
        // Converter para Collection se necessário
        if (!$data instanceof \Illuminate\Support\Collection) {
            $data = collect($data);
        }

        try {
            $pdf = Pdf::loadView('exports.pdf-template', [
                'title' => $title,
                'columns' => $columns,
                'data' => $data,
            ]);

            return $pdf->download("{$filename}.pdf");
        } catch (\Exception $e) {
            // Fallback: retornar HTML se PDF não estiver disponível
            return response()->view('exports.pdf-template', [
                'title' => $title,
                'columns' => $columns,
                'data' => $data,
            ], 200)->header('Content-Type', 'text/html');
        }
    }

    /**
     * Formata valor para CSV com formatação profissional
     */
    protected function formatValueForCsv($value): string
    {
        if (is_null($value)) {
            return '-';
        }

        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if ($value instanceof DateTime || $value instanceof Carbon) {
            return $value->format('d/m/Y H:i:s');
        }

        // Formatação de números decimais
        if (is_numeric($value) && strpos((string)$value, '.') !== false) {
            $num = (float)$value;
            // Se for um número muito pequeno (tempo médio, etc.), formatar com 2 decimais
            if ($num < 1000) {
                return number_format($num, 2, ',', '.');
            }
            // Números maiores com separador de milhar
            return number_format($num, 2, ',', '.');
        }

        // Formatação de números inteiros com separador de milhar
        if (is_numeric($value) && (int)$value == $value) {
            return number_format((int)$value, 0, ',', '.');
        }

        return (string) $value;
    }

    /**
     * Formata valor para Excel
     */
    protected function formatValueForExcel($value)
    {
        if (is_null($value)) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Sim' : 'Não';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        if ($value instanceof DateTime || $value instanceof Carbon) {
            return $value->format('d/m/Y H:i:s');
        }

        return $value;
    }
}
