<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Consolidates: UserPhotos, PasswordResetLogs, and UserRelationships.
     */
    public function up(): void
    {
        // 1. User Photos
        Schema::create('user_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('path');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Password Reset Logs
        Schema::create('password_reset_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });

        // 3. User Relationships
        Schema::create('user_relationships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('related_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('related_name')->nullable();
            $table->string('relationship_type', 50)->index();
            $table->string('status', 20)->default('pending')->index();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'related_user_id', 'relationship_type'], 'user_rel_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relationships');
        Schema::dropIfExists('password_reset_logs');
        Schema::dropIfExists('user_photos');
    }
};
