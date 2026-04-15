<?php

namespace Modules\Financeiro\App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Financeiro\App\Models\FinTransaction;

class MarkFinTransactionsOverdueCommand extends Command
{
    protected $signature = 'financeiro:mark-overdue';

    protected $description = 'Marca lançamentos pendentes com vencimento passado como atrasados.';

    public function handle(): int
    {
        $today = now()->toDateString();

        $n = FinTransaction::query()
            ->where('status', FinTransaction::STATUS_PENDING)
            ->whereNotNull('due_on')
            ->whereDate('due_on', '<', $today)
            ->update(['status' => FinTransaction::STATUS_OVERDUE]);

        $this->info("Actualizados: {$n} lançamento(s).");

        return self::SUCCESS;
    }
}
