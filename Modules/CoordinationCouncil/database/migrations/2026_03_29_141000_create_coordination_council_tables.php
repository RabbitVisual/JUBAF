<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('council_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone', 40)->nullable();
            $table->enum('kind', ['effective', 'supplement'])->default('effective');
            $table->date('term_started_at')->nullable();
            $table->date('term_ended_at')->nullable();
            $table->unsignedTinyInteger('mandate_third')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('council_meetings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('scheduled_at');
            $table->string('location')->nullable();
            $table->enum('meeting_type', ['ordinary', 'extraordinary'])->default('ordinary');
            $table->unsignedInteger('quorum_required')->default(1);
            $table->unsignedInteger('quorum_actual')->nullable();
            $table->text('minutes_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('council_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('council_meeting_id')->constrained('council_meetings')->cascadeOnDelete();
            $table->foreignId('council_member_id')->constrained('council_members')->cascadeOnDelete();
            $table->enum('status', ['present', 'absent', 'excused'])->default('present');
            $table->text('justification')->nullable();
            $table->timestamps();
            $table->unique(['council_meeting_id', 'council_member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('council_attendances');
        Schema::dropIfExists('council_meetings');
        Schema::dropIfExists('council_members');
    }
};
