<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained('churches')->cascadeOnDelete();
            $table->dateTime('visited_at');
            $table->text('notes')->nullable();
            $table->text('next_steps')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('field_visit_attendee', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_visit_id')->constrained('field_visits')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['field_visit_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_visit_attendee');
        Schema::dropIfExists('field_visits');
    }
};
