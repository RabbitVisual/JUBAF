<?php

namespace Modules\Financeiro\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use App\Support\ErpChurchScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Financeiro\App\Support\FinReportingPeriod;
use Modules\Igrejas\App\Models\Church;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('financeiro.reports.view'), 403);

        return view('financeiro::paineldiretoria.reports.index', array_merge(
            [
                'layout' => 'layouts.app',
                'routePrefix' => 'diretoria.financeiro',
            ],
            $this->buildReportData($request)
        ));
    }

    public function csv(Request $request): StreamedResponse
    {
        abort_unless($request->user()?->can('financeiro.reports.view'), 403);

        $data = $this->buildReportData($request);
        $from = $data['from'];
        $to = $data['to'];

        $txBase = $this->finTransactionsScoped($request);
        $rows = (clone $txBase)
            ->with(['category', 'church'])
            ->whereBetween('occurred_on', [$from, $to])
            ->orderBy('occurred_on')
            ->get();

        $filename = 'jubaf_financeiro_'.$from.'_'.$to.'.csv';

        return response()->streamDownload(function () use ($rows): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'Data',
                'Direcção',
                'Âmbito',
                'Origem',
                'ID Gateway',
                'Código categoria',
                'Categoria',
                'Grupo',
                'Igreja',
                'Valor',
                'Ref. interna',
                'Documento (NF/recibo)',
                'Descrição',
            ]);
            foreach ($rows as $r) {
                $meta = $r->metadata;
                $gwId = is_array($meta) ? ($meta['gateway_payment_id'] ?? null) : null;
                fputcsv($out, [
                    $r->occurred_on->format('Y-m-d'),
                    $r->direction,
                    FinTransaction::normalizeScopeLabel($r->scope),
                    FinTransaction::sourceLabel($r->source),
                    $gwId,
                    $r->category?->code,
                    $r->category?->name,
                    FinCategory::groupLabel($r->category?->group_key),
                    $r->church?->name,
                    number_format((float) $r->amount, 2, '.', ''),
                    $r->reference,
                    $r->document_ref,
                    $r->description,
                ]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function pdf(Request $request)
    {
        abort_unless($request->user()?->can('financeiro.reports.view'), 403);

        $data = $this->buildReportData($request);
        $data['generatedAt'] = Carbon::now();
        $data['issuerName'] = $request->user()?->name;
        $data['logoDataUri'] = $this->resolvePdfLogoDataUri();

        $pdf = Pdf::loadView('financeiro::reports.balancete_pdf', $data);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('defaultFont', 'DejaVu Sans');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('dpi', 120);
        $chroot = realpath(public_path());
        if ($chroot !== false) {
            $pdf->setOption('chroot', $chroot);
        }

        $filename = 'JUBAF_balancete_'.$data['from'].'_'.$data['to'].'.pdf';

        return $pdf->download($filename);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildReportData(Request $request): array
    {
        $range = FinReportingPeriod::fromRequest($request);
        $from = $range['from'];
        $to = $range['to'];
        $periodLabel = $range['label'];
        $periodPreset = $range['preset'];

        $now = Carbon::now();
        $defaultAssocYear = $now->month >= 3 ? $now->year : $now->year - 1;

        $txBase = $this->finTransactionsScoped($request);

        $byCategory = (clone $txBase)
            ->selectRaw('category_id, direction, SUM(amount) as total')
            ->whereBetween('occurred_on', [$from, $to])
            ->groupBy('category_id', 'direction')
            ->get();

        $categories = FinCategory::query()->get()->keyBy('id');

        $totals = [
            'in' => (float) (clone $txBase)->where('direction', 'in')->whereBetween('occurred_on', [$from, $to])->sum('amount'),
            'out' => (float) (clone $txBase)->where('direction', 'out')->whereBetween('occurred_on', [$from, $to])->sum('amount'),
        ];

        $txCount = (int) (clone $txBase)->whereBetween('occurred_on', [$from, $to])->count();

        $scopeBreakdown = (clone $txBase)
            ->selectRaw('scope, direction, SUM(amount) as total')
            ->whereBetween('occurred_on', [$from, $to])
            ->groupBy('scope', 'direction')
            ->get();

        $churchBreakdown = $this->churchBreakdown($request, $from, $to);

        return [
            'from' => $from,
            'to' => $to,
            'periodLabel' => $periodLabel,
            'periodPreset' => $periodPreset,
            'anchor' => $request->input('anchor', $now->toDateString()),
            'year' => (int) $request->input('year', $now->year),
            'assocYear' => (int) $request->input('assoc_year', $defaultAssocYear),
            'byCategory' => $byCategory,
            'categories' => $categories,
            'totals' => $totals,
            'txCount' => $txCount,
            'churchBreakdown' => $churchBreakdown,
            'scopeBreakdown' => $scopeBreakdown,
        ];
    }

    /**
     * @return Builder<\Modules\Financeiro\App\Models\FinTransaction>
     */
    private function finTransactionsScoped(Request $request): Builder
    {
        $q = FinTransaction::query();
        $user = $request->user();
        if ($user) {
            ErpChurchScope::applyToFinTransactionQuery($q, $user);
        }

        return $q;
    }

    private function churchBreakdown(Request $request, string $from, string $to): Collection
    {
        if (! module_enabled('Igrejas') || ! class_exists(Church::class)) {
            return collect();
        }

        $txBase = $this->finTransactionsScoped($request);
        $byChurchRows = (clone $txBase)
            ->selectRaw('church_id, direction, SUM(amount) as total')
            ->whereBetween('occurred_on', [$from, $to])
            ->whereNotNull('church_id')
            ->groupBy('church_id', 'direction')
            ->get();

        if ($byChurchRows->isEmpty()) {
            return collect();
        }

        $churchNames = Church::query()->orderBy('name')->pluck('name', 'id');

        return $byChurchRows
            ->groupBy('church_id')
            ->map(function ($group, $churchId) use ($churchNames) {
                $id = (int) $churchId;
                $in = (float) $group->where('direction', 'in')->sum('total');
                $out = (float) $group->where('direction', 'out')->sum('total');

                return [
                    'id' => $id,
                    'name' => (string) $churchNames->get($id, 'Igreja #'.$id),
                    'in' => $in,
                    'out' => $out,
                    'balance' => $in - $out,
                ];
            })
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    private function resolvePdfLogoDataUri(): ?string
    {
        // Logótipo oficial JUBAF (prioridade). Não usar SVG legado do sistema.
        $candidates = [
            'images/logo/logo.png',
            'images/logo/logo.jpg',
            'images/logo/logo.jpeg',
            'images/logo/logo.webp',
        ];
        foreach ($candidates as $rel) {
            $path = public_path($rel);
            if (! is_readable($path)) {
                continue;
            }
            $lower = strtolower($path);
            $mime = match (true) {
                str_ends_with($lower, '.png') => 'image/png',
                str_ends_with($lower, '.jpg'), str_ends_with($lower, '.jpeg') => 'image/jpeg',
                str_ends_with($lower, '.webp') => 'image/webp',
                str_ends_with($lower, '.svg') => 'image/svg+xml',
                default => 'image/png',
            };
            $raw = @file_get_contents($path);
            if ($raw === false || $raw === '') {
                continue;
            }

            return 'data:'.$mime.';base64,'.base64_encode($raw);
        }

        return null;
    }
}
