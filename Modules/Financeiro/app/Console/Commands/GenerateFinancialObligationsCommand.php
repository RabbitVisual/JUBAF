<?php

namespace Modules\Financeiro\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Modules\Financeiro\App\Services\FinObligationGenerator;
use Modules\Financeiro\App\Support\FinReportingPeriod;

class GenerateFinancialObligationsCommand extends Command
{
    protected $signature = 'financeiro:generate-obligations
                            {--year= : Ano de início associativo (padrão: ano associativo actual)}
                            {--dry-run : Não gravar}';

    protected $description = 'Gera registos de cota/obrigação associativa por igreja activa (idempotente por ano)';

    public function handle(FinObligationGenerator $generator): int
    {
        if (! Schema::hasTable('fin_obligations')) {
            $this->error('Tabela fin_obligations inexistente. Execute as migrações.');

            return 1;
        }

        $yearOpt = $this->option('year');
        $assocYear = $yearOpt !== null && $yearOpt !== ''
            ? max(2000, min(2099, (int) $yearOpt))
            : FinReportingPeriod::defaultAssociativeStartYear();

        $dry = (bool) $this->option('dry-run');

        $result = $generator->generateForAssociativeYear($assocYear, $dry);

        $this->info($dry ? "Dry-run: {$result['created']} obrigações seriam criadas." : "Criadas {$result['created']} obrigações para o ano associativo {$result['assoc_start_year']}.");

        return 0;
    }
}
