<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Nwidart\Modules\Facades\Module;
use App\Support\Database\SeedCatalog;

/*
|--------------------------------------------------------------------------
| Console Routes (DevOps & Maintenance)
|--------------------------------------------------------------------------
|
| Comandos personalizados para gestão do Vertex Semagri via terminal (SSH).
| Útil para diagnósticos rápidos e automação na Hostinger.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * 🩺 HEALTH CHECK
 */
Artisan::command('vertex:health', function () {
    $this->info('🔍 Iniciando diagnóstico do Vertex Semagri...');

    // 1. Banco de Dados
    try {
        $dbName = DB::connection()->getDatabaseName();
        DB::connection()->getPdo();
        $this->info("✅ Banco de Dados [{$dbName}]: Conectado");
    } catch (\Exception $e) {
        $this->error("❌ Banco de Dados: FALHA - " . $e->getMessage());
        return 1;
    }

    // 2. Permissões de Escrita
    $paths = ['storage', 'bootstrap/cache'];
    foreach ($paths as $path) {
        if (is_writable(base_path($path))) {
            $this->info("✅ Permissão [{$path}]: OK");
        } else {
            $this->error("❌ Permissão [{$path}]: SEM ESCRITA (Corrija com chmod -R 775)");
        }
    }

    // 3. Módulos
    try {
        $enabled = count(Module::allEnabled());
        $disabled = count(Module::allDisabled());
        $this->info("📦 Módulos: {$enabled} Ativos | {$disabled} Inativos");
    } catch (\Exception $e) {
        $this->error("⚠️ Erro ao verificar módulos: " . $e->getMessage());
    }

    $this->comment('Diagnóstico concluído.');
})->purpose('Verifica a saúde do sistema (DB, Permissões, Módulos)');

/**
 * 🧹 DEPLOY FIX
 */
Artisan::command('vertex:fix', function () {
    $this->info('🚀 Otimizando sistema para produção...');

    $this->call('optimize:clear');
    $this->call('view:clear');
    $this->call('config:clear');
    $this->call('route:clear');

    $this->info('⚡ Gerando novos caches...');
    $this->call('optimize');
    $this->call('view:cache');

    if (!File::exists(public_path('storage'))) {
        $this->call('storage:link');
    }

    $this->info('✅ Sistema limpo e otimizado com sucesso!');
})->purpose('Limpa e recria todos os caches (Use após update)');

/**
 * 🗑️ LIMPEZA DE LOGS ANTIGOS
 */
Artisan::command('vertex:prune-logs {days=365}', function ($days) {
    $date = now()->subDays($days);
    $this->info("🗑️ Limpando logs de auditoria anteriores a {$date->format('d/m/Y')}...");

    // Tenta detectar a tabela de auditoria (audit_logs ou audits)
    $table = 'audit_logs';
    if (!Schema::hasTable($table) && Schema::hasTable('audits')) {
        $table = 'audits';
    }

    try {
        $deleted = DB::table($table)
            ->where('created_at', '<', $date)
            ->delete();

        $this->info("✅ {$deleted} registros antigos removidos da tabela '{$table}'.");
        Log::info("Limpeza automática: {$deleted} logs antigos removidos via console da tabela '{$table}'.");
    } catch (\Exception $e) {
        $this->error("❌ Erro ao limpar logs: " . $e->getMessage());
    }
})->purpose('Remove logs de auditoria antigos para liberar espaço');

Artisan::command('vertex:db-update {--seed : Executa os seeders de atualização após migrate}', function () {
    $this->info('🔄 Executando atualização de banco (migrations + update seeders)...');

    $this->call('migrate', ['--force' => true]);

    if (! $this->option('seed')) {
        $this->warn('Migrations concluídas. Use --seed para executar os seeders de atualização.');
        return 0;
    }

    foreach (SeedCatalog::updateSeeders() as $seederClass) {
        $this->line("▶ Seeding: {$seederClass}");
        $this->call('db:seed', [
            '--class' => $seederClass,
            '--force' => true,
        ]);
    }

    $this->info('✅ Atualização concluída com sucesso.');
    return 0;
})->purpose('Aplica migrate e, opcionalmente, seeds de atualização idempotentes');

Artisan::command('vertex:db-reset', function () {
    if ($this->confirm('⚠️ Isso apaga TODOS os dados e repovoa tudo. Deseja continuar?', false) === false) {
        $this->warn('Operação cancelada.');
        return 1;
    }

    $this->info('🧨 Executando reset completo do banco...');
    $this->call('migrate:fresh', ['--force' => true]);

    foreach (SeedCatalog::fullSeeders() as $seederClass) {
        $this->line("▶ Seeding: {$seederClass}");
        $this->call('db:seed', [
            '--class' => $seederClass,
            '--force' => true,
        ]);
    }

    $this->info('✅ Reset completo finalizado.');
    return 0;
})->purpose('Apaga e recria todas as tabelas, depois repovoa tudo');

