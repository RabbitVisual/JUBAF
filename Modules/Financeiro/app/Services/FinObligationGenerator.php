<?php

namespace Modules\Financeiro\App\Services;

use Illuminate\Support\Facades\Schema;
use Modules\Financeiro\App\Events\FinancialObligationGenerated;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Support\FinReportingPeriod;
use Modules\Igrejas\App\Models\Church;

final class FinObligationGenerator
{
    /**
     * @return array{created: int, assoc_start_year: int}
     */
    public function generateForAssociativeYear(?int $assocStartYear = null, bool $dryRun = false): array
    {
        if (! Schema::hasTable('fin_obligations')) {
            throw new \RuntimeException('Tabela de obrigações indisponível.');
        }

        $year = $assocStartYear ?? FinReportingPeriod::defaultAssociativeStartYear();
        $year = max(2000, min(2099, $year));
        $amount = (float) config('financeiro.quota.default_amount', 100);
        $created = 0;

        Church::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->chunkById(100, function ($churches) use ($year, $amount, $dryRun, &$created): void {
                foreach ($churches as $church) {
                    $exists = FinObligation::query()
                        ->where('church_id', $church->id)
                        ->where('assoc_start_year', $year)
                        ->exists();
                    if ($exists) {
                        continue;
                    }
                    if ($dryRun) {
                        $created++;

                        continue;
                    }
                    $obligation = FinObligation::query()->create([
                        'church_id' => $church->id,
                        'assoc_start_year' => $year,
                        'amount' => $amount,
                        'currency' => 'BRL',
                        'status' => FinObligation::STATUS_PENDING,
                        'generated_at' => now(),
                        'metadata' => [
                            'category_hint' => $this->resolveIncomeCategoryHint(),
                        ],
                    ]);
                    event(new FinancialObligationGenerated($obligation));
                    $created++;
                }
            });

        return [
            'created' => $created,
            'assoc_start_year' => $year,
        ];
    }

    private function resolveIncomeCategoryHint(): ?string
    {
        $code = (string) config('financeiro.quota.income_category_code', '');
        if ($code !== '') {
            $c = FinCategory::query()->where('code', $code)->where('direction', 'in')->first();

            return $c ? (string) $c->code : null;
        }

        $fallback = FinCategory::query()
            ->where('direction', 'in')
            ->where(function ($q) {
                $q->where('name', 'like', '%cota%')
                    ->orWhere('name', 'like', '%anuid%')
                    ->orWhere('code', 'like', '%COTA%');
            })
            ->orderBy('sort_order')
            ->first();

        return $fallback?->code;
    }
}
