<?php

namespace Modules\Financeiro\App\Support;

use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Resolve relatório / balancete date ranges (mês, trimestre, semestre, ano civil, YTD, personalizado).
 */
final class FinReportingPeriod
{
    public const PRESET_CUSTOM = 'custom';

    public const PRESET_MONTH = 'month';

    public const PRESET_QUARTER = 'quarter';

    public const PRESET_SEMESTER = 'semester';

    public const PRESET_YEAR = 'year';

    public const PRESET_YTD = 'ytd';

    /** Março (ano Y) a fevereiro (ano Y+1), comum em mandatos / relatórios associativos. */
    public const PRESET_ASSOCIATIVE_YEAR = 'associative_year';

    /**
     * @return array{from: string, to: string, label: string, preset: string}
     */
    public static function fromRequest(Request $request): array
    {
        $preset = $request->input('period');
        if ($preset === null || $preset === '') {
            $preset = ($request->filled('from') && $request->filled('to'))
                ? self::PRESET_CUSTOM
                : self::PRESET_MONTH;
        }
        $preset = (string) $preset;
        $now = Carbon::now();

        if ($preset === self::PRESET_CUSTOM) {
            $from = (string) $request->input('from', $now->copy()->startOfMonth()->toDateString());
            $to = (string) $request->input('to', $now->copy()->endOfMonth()->toDateString());

            return [
                'from' => $from,
                'to' => $to,
                'label' => 'Personalizado',
                'preset' => self::PRESET_CUSTOM,
            ];
        }

        if ($preset === self::PRESET_YTD) {
            return [
                'from' => $now->copy()->startOfYear()->toDateString(),
                'to' => $now->toDateString(),
                'label' => 'Ano a hoje ('.$now->year.')',
                'preset' => self::PRESET_YTD,
            ];
        }

        if ($preset === self::PRESET_YEAR) {
            $y = (int) $request->input('year', $now->year);
            $y = max(2000, min(2100, $y));
            $start = Carbon::create($y, 1, 1)->startOfDay();
            $end = Carbon::create($y, 12, 31)->endOfDay();

            return [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
                'label' => 'Ano civil '.$y,
                'preset' => self::PRESET_YEAR,
            ];
        }

        if ($preset === self::PRESET_ASSOCIATIVE_YEAR) {
            $defaultStart = $now->month >= 3 ? $now->year : $now->year - 1;
            $startY = (int) $request->input('assoc_year', $defaultStart);
            $startY = max(2000, min(2099, $startY));
            $from = Carbon::create($startY, 3, 1)->toDateString();
            $end = Carbon::create($startY + 1, 2, 1)->endOfMonth()->toDateString();

            return [
                'from' => $from,
                'to' => $end,
                'label' => sprintf('Ano associativo %d/%d (mar.–fev.)', $startY, $startY + 1),
                'preset' => self::PRESET_ASSOCIATIVE_YEAR,
            ];
        }

        $anchor = $request->filled('anchor')
            ? Carbon::parse((string) $request->input('anchor'))->startOfDay()
            : $now->copy();

        if ($preset === self::PRESET_MONTH) {
            $start = $anchor->copy()->startOfMonth();
            $end = $anchor->copy()->endOfMonth();

            return [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
                'label' => 'Mês: '.$anchor->translatedFormat('F Y'),
                'preset' => self::PRESET_MONTH,
            ];
        }

        if ($preset === self::PRESET_QUARTER) {
            $m = (int) $anchor->month;
            $q = (int) ceil($m / 3);
            $startMonth = ($q - 1) * 3 + 1;
            $start = $anchor->copy()->month($startMonth)->startOfMonth();
            $end = $start->copy()->addMonths(2)->endOfMonth();

            return [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
                'label' => 'T'.$q.' '.$anchor->year,
                'preset' => self::PRESET_QUARTER,
            ];
        }

        if ($preset === self::PRESET_SEMESTER) {
            if ($anchor->month <= 6) {
                $start = $anchor->copy()->startOfYear();
                $end = $anchor->copy()->month(6)->endOfMonth();
                $label = '1.º semestre '.$anchor->year;
            } else {
                $start = $anchor->copy()->month(7)->startOfMonth();
                $end = $anchor->copy()->endOfYear();
                $label = '2.º semestre '.$anchor->year;
            }

            return [
                'from' => $start->toDateString(),
                'to' => $end->toDateString(),
                'label' => $label,
                'preset' => self::PRESET_SEMESTER,
            ];
        }

        $from = (string) $request->input('from', $now->copy()->startOfMonth()->toDateString());
        $to = (string) $request->input('to', $now->copy()->endOfMonth()->toDateString());

        return [
            'from' => $from,
            'to' => $to,
            'label' => 'Personalizado',
            'preset' => self::PRESET_CUSTOM,
        ];
    }

    /** Ano de início do ciclo associativo (março a fevereiro do ano seguinte). */
    public static function defaultAssociativeStartYear(?Carbon $ref = null): int
    {
        $ref = $ref ?? Carbon::now();

        return $ref->month >= 3 ? $ref->year : $ref->year - 1;
    }
}
