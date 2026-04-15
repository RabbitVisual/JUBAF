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
        Schema::disableForeignKeyConstraints();

        $this->renameSupportTables();
        $this->renameMainTables();
        $this->alignEventosColumns();
        $this->alignGatewayTransactionsColumns();
        $this->alignCrossModuleForeignKeys();

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('talent_assignments') && Schema::hasColumn('talent_assignments', 'evento_id')) {
            Schema::table('talent_assignments', function (Blueprint $table): void {
                $table->renameColumn('evento_id', 'calendar_event_id');
            });
        }

        if (Schema::hasTable('secretaria_meetings') && Schema::hasColumn('secretaria_meetings', 'evento_id')) {
            Schema::table('secretaria_meetings', function (Blueprint $table): void {
                $table->renameColumn('evento_id', 'calendar_event_id');
            });
        }

        if (Schema::hasTable('fin_transactions') && Schema::hasColumn('fin_transactions', 'evento_id')) {
            Schema::table('fin_transactions', function (Blueprint $table): void {
                $table->renameColumn('evento_id', 'calendar_event_id');
            });
        }

        if (Schema::hasTable('eventos')) {
            if (Schema::hasColumn('eventos', 'start_date')) {
                Schema::table('eventos', function (Blueprint $table): void {
                    $table->renameColumn('start_date', 'starts_at');
                });
            }

            if (Schema::hasColumn('eventos', 'end_date')) {
                Schema::table('eventos', function (Blueprint $table): void {
                    $table->renameColumn('end_date', 'ends_at');
                });
            }

            if (Schema::hasColumn('eventos', 'capacity')) {
                Schema::table('eventos', function (Blueprint $table): void {
                    $table->renameColumn('capacity', 'max_participants');
                });
            }

            if (Schema::hasColumn('eventos', 'ticket_price')) {
                Schema::table('eventos', function (Blueprint $table): void {
                    $table->renameColumn('ticket_price', 'registration_fee');
                });
            }
        }

        if (Schema::hasTable('gateway_transactions')) {
            if (Schema::hasColumn('gateway_transactions', 'external_reference')) {
                Schema::table('gateway_transactions', function (Blueprint $table): void {
                    $table->renameColumn('external_reference', 'provider_reference');
                });
            }
        }

        if (Schema::hasTable('evento_inscricoes') && Schema::hasColumn('evento_inscricoes', 'payment_id')) {
            Schema::table('evento_inscricoes', function (Blueprint $table): void {
                $table->renameColumn('payment_id', 'gateway_payment_id');
            });
        }

        if (Schema::hasTable('evento_price_rules')) {
            Schema::rename('evento_price_rules', 'calendar_price_rules');
        }
        if (Schema::hasTable('evento_batches')) {
            Schema::rename('evento_batches', 'calendar_event_batches');
        }
        if (Schema::hasTable('gateway_transactions')) {
            Schema::rename('gateway_transactions', 'gateway_payments');
        }
        if (Schema::hasTable('evento_inscricoes')) {
            Schema::rename('evento_inscricoes', 'calendar_registrations');
        }
        if (Schema::hasTable('eventos')) {
            Schema::rename('eventos', 'calendar_events');
        }

        Schema::enableForeignKeyConstraints();
    }

    private function renameSupportTables(): void
    {
        if (Schema::hasTable('calendar_event_batches') && ! Schema::hasTable('evento_batches')) {
            Schema::rename('calendar_event_batches', 'evento_batches');
        }

        if (Schema::hasTable('calendar_price_rules') && ! Schema::hasTable('evento_price_rules')) {
            Schema::rename('calendar_price_rules', 'evento_price_rules');
        }
    }

    private function renameMainTables(): void
    {
        if (Schema::hasTable('calendar_events') && ! Schema::hasTable('eventos')) {
            Schema::rename('calendar_events', 'eventos');
        }

        if (Schema::hasTable('calendar_registrations') && ! Schema::hasTable('evento_inscricoes')) {
            Schema::rename('calendar_registrations', 'evento_inscricoes');
        }

        if (Schema::hasTable('gateway_payments') && ! Schema::hasTable('gateway_transactions')) {
            Schema::rename('gateway_payments', 'gateway_transactions');
        }
    }

    private function alignEventosColumns(): void
    {
        if (! Schema::hasTable('eventos')) {
            return;
        }

        Schema::table('eventos', function (Blueprint $table): void {
            if (Schema::hasColumn('eventos', 'starts_at') && ! Schema::hasColumn('eventos', 'start_date')) {
                $table->renameColumn('starts_at', 'start_date');
            }

            if (Schema::hasColumn('eventos', 'ends_at') && ! Schema::hasColumn('eventos', 'end_date')) {
                $table->renameColumn('ends_at', 'end_date');
            }

            if (Schema::hasColumn('eventos', 'max_participants') && ! Schema::hasColumn('eventos', 'capacity')) {
                $table->renameColumn('max_participants', 'capacity');
            }

            if (Schema::hasColumn('eventos', 'registration_fee') && ! Schema::hasColumn('eventos', 'ticket_price')) {
                $table->renameColumn('registration_fee', 'ticket_price');
            }

            if (! Schema::hasColumn('eventos', 'uuid')) {
                $table->uuid('uuid')->nullable()->after('id');
            }

            if (! Schema::hasColumn('eventos', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('capacity');
            }
        });

        DB::table('eventos')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('eventos')->where('id', $row->id)->update(['uuid' => (string) Str::uuid()]);
                }
            });

        DB::table('eventos')->update([
            'is_paid' => DB::raw('CASE WHEN COALESCE(ticket_price, 0) > 0 THEN 1 ELSE 0 END'),
        ]);
    }

    private function alignGatewayTransactionsColumns(): void
    {
        if (! Schema::hasTable('gateway_transactions')) {
            return;
        }

        Schema::table('gateway_transactions', function (Blueprint $table): void {
            if (Schema::hasColumn('gateway_transactions', 'provider_reference') && ! Schema::hasColumn('gateway_transactions', 'external_reference')) {
                $table->renameColumn('provider_reference', 'external_reference');
            }

            if (! Schema::hasColumn('gateway_transactions', 'payment_method')) {
                $table->string('payment_method', 32)->nullable()->after('amount');
            }

            if (! Schema::hasColumn('gateway_transactions', 'qr_code_base64')) {
                $table->longText('qr_code_base64')->nullable()->after('status');
            }

            if (! Schema::hasColumn('gateway_transactions', 'ticket_url')) {
                $table->string('ticket_url')->nullable()->after('qr_code_base64');
            }
        });
    }

    private function alignCrossModuleForeignKeys(): void
    {
        if (Schema::hasTable('evento_inscricoes') && Schema::hasColumn('evento_inscricoes', 'gateway_payment_id') && ! Schema::hasColumn('evento_inscricoes', 'payment_id')) {
            Schema::table('evento_inscricoes', function (Blueprint $table): void {
                $table->renameColumn('gateway_payment_id', 'payment_id');
            });
        }

        if (Schema::hasTable('fin_transactions') && Schema::hasColumn('fin_transactions', 'calendar_event_id') && ! Schema::hasColumn('fin_transactions', 'evento_id')) {
            Schema::table('fin_transactions', function (Blueprint $table): void {
                $table->renameColumn('calendar_event_id', 'evento_id');
            });
        }

        if (Schema::hasTable('talent_assignments') && Schema::hasColumn('talent_assignments', 'calendar_event_id') && ! Schema::hasColumn('talent_assignments', 'evento_id')) {
            Schema::table('talent_assignments', function (Blueprint $table): void {
                $table->renameColumn('calendar_event_id', 'evento_id');
            });
        }

        if (Schema::hasTable('secretaria_meetings') && Schema::hasColumn('secretaria_meetings', 'calendar_event_id') && ! Schema::hasColumn('secretaria_meetings', 'evento_id')) {
            Schema::table('secretaria_meetings', function (Blueprint $table): void {
                $table->renameColumn('calendar_event_id', 'evento_id');
            });
        }
    }
};
