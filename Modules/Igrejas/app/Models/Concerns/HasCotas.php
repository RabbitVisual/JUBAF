<?php

namespace Modules\Igrejas\App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;
use Modules\Financeiro\App\Models\FinObligation;

trait HasCotas
{
    /**
     * Obrigações financeiras (cotas) da igreja.
     *
     * @return HasMany<FinObligation, $this>
     */
    public function finObligations(): HasMany
    {
        return $this->hasMany(FinObligation::class, 'church_id');
    }

    /**
     * @return array{pending_count: int, overdue_amount: float, paid_last_year: int}
     */
    public function cotasSummary(): array
    {
        if (! Schema::hasTable('fin_obligations')) {
            return [
                'pending_count' => 0,
                'overdue_amount' => 0.0,
                'paid_last_year' => 0,
            ];
        }

        $pending = $this->finObligations()
            ->where('status', FinObligation::STATUS_PENDING)
            ->count();

        $overdueAmount = (float) $this->finObligations()
            ->where('status', FinObligation::STATUS_PENDING)
            ->sum('amount');

        $year = (int) now()->year;
        $paidLastYear = $this->finObligations()
            ->where('status', FinObligation::STATUS_PAID)
            ->whereYear('paid_at', $year)
            ->count();

        return [
            'pending_count' => $pending,
            'overdue_amount' => $overdueAmount,
            'paid_last_year' => $paidLastYear,
        ];
    }
}
