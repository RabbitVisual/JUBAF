<?php

namespace Modules\Bible\App\Console\Commands;

use Illuminate\Console\Command;

class ImportBibleStudyDataCommand extends Command
{
    protected $signature = 'bible:import-study {--fresh-interlinear : Trunca tokens antes da importação interlinear}';

    protected $description = 'Importa léxico Strong e tokens interlineares (ordem: strongs → interlinear). Na consola use: php artisan bible:import-study [--fresh-interlinear]';

    public function handle(): int
    {
        $this->info('1/2 Léxico Strong...');
        $code = $this->call('bible:import-strongs');
        if ($code !== 0) {
            return $code;
        }

        $this->newLine();
        $this->info('2/2 Tokens interlineares...');

        $args = [];
        if ($this->option('fresh-interlinear')) {
            $args['--fresh'] = true;
        }

        return $this->call('bible:import-interlinear', $args);
    }
}
