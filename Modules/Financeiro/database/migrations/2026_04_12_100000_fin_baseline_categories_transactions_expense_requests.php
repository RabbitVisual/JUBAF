<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('fin_bank_accounts')) {
            Schema::create('fin_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('institution')->nullable();
                $table->string('account_type', 24)->default('corrente');
                $table->string('currency', 8)->default('BRL');
                $table->decimal('balance', 15, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('fin_categories')) {
            Schema::create('fin_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('parent_id')->nullable()->constrained('fin_categories')->nullOnDelete();
                $table->string('name');
                $table->string('code', 64)->nullable()->unique();
                $table->string('group_key', 64)->nullable()->index();
                $table->text('description')->nullable();
                $table->string('direction', 8);
                $table->unsignedSmallInteger('sort_order')->default(100);
                $table->boolean('is_active')->default(true);
                $table->boolean('is_system')->default(false);
                $table->boolean('requires_minute_and_receipt')->default(false);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('fin_transactions')) {
            Schema::create('fin_transactions', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('category_id')->constrained('fin_categories')->restrictOnDelete();
                $table->foreignId('bank_account_id')->nullable()->constrained('fin_bank_accounts')->nullOnDelete();
                $table->date('occurred_on');
                $table->date('due_on')->nullable()->index();
                $table->date('paid_on')->nullable();
                $table->decimal('amount', 15, 2);
                $table->string('direction', 8);
                $table->string('scope', 32)->default('regional')->index();
                $table->foreignId('church_id')->nullable()->constrained('igrejas_churches')->nullOnDelete();
                $table->text('description')->nullable();
                $table->string('reference', 120)->nullable();
                $table->string('document_ref', 120)->nullable();
                $table->string('source', 32)->default('manual');
                $table->string('comprovante_path')->nullable();
                $table->string('status', 24)->default('paid')->index();
                $table->boolean('reconciled')->default(false);
                $table->boolean('is_extraordinary')->default(false);
                $table->unsignedBigInteger('secretaria_minute_id')->nullable()->index();
                $table->unsignedBigInteger('calendar_event_id')->nullable();
                $table->json('metadata')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['occurred_on', 'status']);
                $table->index(['church_id', 'category_id']);
            });
        }

        if (! Schema::hasTable('fin_expense_requests')) {
            Schema::create('fin_expense_requests', function (Blueprint $table) {
                $table->id();
                $table->decimal('amount', 15, 2);
                $table->text('justification');
                $table->string('status', 32)->default('draft')->index();
                $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->foreignId('paid_transaction_id')->nullable()->constrained('fin_transactions')->nullOnDelete();
                $table->string('attachment_path')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        $this->backfillTransactionUuids();
    }

    private function backfillTransactionUuids(): void
    {
        if (! Schema::hasTable('fin_transactions') || ! Schema::hasColumn('fin_transactions', 'uuid')) {
            return;
        }

        DB::table('fin_transactions')->whereNull('uuid')->orderBy('id')->chunkById(200, function ($rows): void {
            foreach ($rows as $row) {
                DB::table('fin_transactions')->where('id', $row->id)->update([
                    'uuid' => (string) Str::uuid(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fin_expense_requests');
        Schema::dropIfExists('fin_transactions');
        Schema::dropIfExists('fin_categories');
        Schema::dropIfExists('fin_bank_accounts');
    }
};
