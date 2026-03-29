<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_entries', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['income', 'expense']); // Entrada ou Saída
            $table->enum('category', [
                'tithe',           // Dízimo
                'offering',        // Oferta
                'donation',        // Doação
                'campaign',        // Campanha
                'maintenance',     // Manutenção
                'utilities',       // Contas (água, luz, etc)
                'salary',         // Salários
                'equipment',       // Equipamentos
                'event',          // Eventos
                'other',           // Outros
            ]);
            $table->unsignedBigInteger('category_id')->nullable(); // Relacionamento com FinancialCategory
            $table->string('title'); // Título/Descrição
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2); // Valor
            $table->date('entry_date'); // Data da entrada/saída
            
            $table->unsignedBigInteger('user_id')->nullable(); // Usuário que registrou (quem lançou)
            $table->unsignedBigInteger('member_id')->nullable(); // Membro relacionado (opcional)
            $table->unsignedBigInteger('payment_id')->nullable(); // Relacionamento com Payment (Gateway)
            $table->unsignedBigInteger('campaign_id')->nullable(); // Relacionamento com Campanha
            $table->unsignedBigInteger('fund_id')->nullable(); // Relacionamento com Fundo/Centro de Custo
            $table->unsignedBigInteger('reversal_of_id')->nullable(); // ID da entrada que esta aqui estorna
            
            $table->string('payment_method')->nullable(); // Método de pagamento (cash, transfer, etc)
            $table->string('reference_number')->nullable(); // Número de referência/comprovante
            
            $table->enum('expense_status', ['pending', 'approved', 'paid'])->nullable(); // Status de despesa
            
            $table->json('metadata')->nullable(); // Dados extras
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('member_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('set null');
            $table->foreign('reversal_of_id')->references('id')->on('financial_entries')->onDelete('set null');

            if (Schema::hasTable('payments')) {
                $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_entries');
    }
};
