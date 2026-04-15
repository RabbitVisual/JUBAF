<?php

namespace Modules\Financeiro\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Financeiro\App\Jobs\GerarCotasAssociativasJob;

class GenerateMonthlyQuotaInvoicesCommand extends Command
{
    protected $signature = 'financeiro:generate-monthly-invoices {--month=}';

    protected $description = 'Gera faturas mensais de cotas associativas (fin_quota_invoices) para igrejas activas.';

    public function handle(): int
    {
        $month = $this->option('month') ? (string) $this->option('month') : null;
        GerarCotasAssociativasJob::dispatch($month);

        $this->info('Job de cotas mensais despachado.');

        return self::SUCCESS;
    }
}
