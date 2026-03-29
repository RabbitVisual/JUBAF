<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('governance_assemblies', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['ordinaria', 'extraordinaria'])->default('ordinaria');
            $table->string('title');
            $table->dateTime('scheduled_at');
            $table->string('location')->nullable();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->text('convocation_notes')->nullable();
            $table->timestamp('convocation_sent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('governance_agenda_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assembly_id')->constrained('governance_assemblies')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('governance_minutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assembly_id')->constrained('governance_assemblies')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->enum('status', ['draft', 'approved', 'published'])->default('draft');
            $table->longText('body');
            $table->string('pdf_path')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('president_signed_at')->nullable();
            $table->timestamp('secretary_signed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('governance_official_communications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary')->nullable();
            $table->longText('body');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('governance_official_communications');
        Schema::dropIfExists('governance_minutes');
        Schema::dropIfExists('governance_agenda_items');
        Schema::dropIfExists('governance_assemblies');
    }
};
