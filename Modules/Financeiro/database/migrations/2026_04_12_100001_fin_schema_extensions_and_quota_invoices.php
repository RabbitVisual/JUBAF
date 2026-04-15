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
        if (Schema::hasTable('fin_categories')) {
            Schema::table('fin_categories', function (Blueprint $table) {
                if (! Schema::hasColumn('fin_categories', 'parent_id')) {
                    $table->foreignId('parent_id')->nullable()->after('id')->constrained('fin_categories')->nullOnDelete();
                }
                if (! Schema::hasColumn('fin_categories', 'requires_minute_and_receipt')) {
                    $table->boolean('requires_minute_and_receipt')->default(false)->after('is_system');
                }
            });
        }

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

        if (Schema::hasTable('fin_transactions')) {
            Schema::table('fin_transactions', function (Blueprint $table) {
                if (! Schema::hasColumn('fin_transactions', 'uuid')) {
                    $table->uuid('uuid')->nullable()->unique()->after('id');
                }
                if (! Schema::hasColumn('fin_transactions', 'bank_account_id')) {
                    $table->foreignId('bank_account_id')->nullable()->after('category_id')->constrained('fin_bank_accounts')->nullOnDelete();
                }
                if (! Schema::hasColumn('fin_transactions', 'due_on')) {
                    $table->date('due_on')->nullable()->after('occurred_on');
                }
                if (! Schema::hasColumn('fin_transactions', 'paid_on')) {
                    $table->date('paid_on')->nullable()->after('due_on');
                }
                if (! Schema::hasColumn('fin_transactions', 'comprovante_path')) {
                    $table->string('comprovante_path')->nullable()->after('source');
                }
                if (! Schema::hasColumn('fin_transactions', 'status')) {
                    $table->string('status', 24)->default('paid')->after('comprovante_path');
                }
                if (! Schema::hasColumn('fin_transactions', 'reconciled')) {
                    $table->boolean('reconciled')->default(false)->after('status');
                }
                if (! Schema::hasColumn('fin_transactions', 'is_extraordinary')) {
                    $table->boolean('is_extraordinary')->default(false)->after('reconciled');
                }
            });
        }

        if (! Schema::hasTable('fin_quota_invoices')) {
            Schema::create('fin_quota_invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('church_id')->constrained('igrejas_churches')->cascadeOnDelete();
                $table->string('billing_month', 7)->index()->comment('YYYY-MM');
                $table->decimal('amount', 15, 2);
                $table->string('currency', 8)->default('BRL');
                $table->string('status', 24)->default('pending')->index();
                $table->foreignId('fin_transaction_id')->nullable()->constrained('fin_transactions')->nullOnDelete();
                $table->date('due_on')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->unique(['church_id', 'billing_month'], 'fin_quota_invoices_church_month_unique');
            });
        }

        if (Schema::hasTable('fin_transactions') && Schema::hasColumn('fin_transactions', 'uuid')) {
            DB::table('fin_transactions')->whereNull('uuid')->orderBy('id')->chunkById(300, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('fin_transactions')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fin_quota_invoices');
    }
};
