<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('secretaria_minutes') || Schema::hasTable('secretaria_minute_signatures')) {
            return;
        }

        Schema::create('secretaria_minute_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('minute_id')->constrained('secretaria_minutes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role_at_the_time', 120);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('signed_at');
            $table->timestamps();

            $table->unique(['minute_id', 'user_id']);
            $table->index(['minute_id', 'signed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secretaria_minute_signatures');
    }
};
