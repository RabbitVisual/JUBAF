<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('secretaria_ged_documents')) {
            return;
        }

        Schema::create('secretaria_ged_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('category', 40)->default('Outros');
            $table->string('file_path');
            $table->foreignId('igreja_id')->nullable()->constrained('igrejas_churches')->nullOnDelete();
            $table->boolean('is_public')->default(false);
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category', 'is_public']);
            $table->index('igreja_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('secretaria_ged_documents');
    }
};
