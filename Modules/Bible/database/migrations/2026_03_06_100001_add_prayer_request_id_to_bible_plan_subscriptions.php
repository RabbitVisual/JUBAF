<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Legado (Intercessor / prayer_requests): mantido só para histórico de migrations.
     *
     * Instalações JUBAF atuais não devem criar esta coluna; a remoção está em
     * 2026_03_28_120000_drop_prayer_request_id_from_bible_plan_subscriptions_table.
     */
    public function up(): void
    {
        // Intentionally empty — prayer_request_id removido do desenho do projeto.
    }

    public function down(): void
    {
        // Par com up() vazio; rollback da coluna fica a cargo da migration de drop, se necessário.
    }
};
