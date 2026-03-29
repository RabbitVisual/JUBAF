<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diretoria_board_minutes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('meeting_date');
            $table->enum('tag', ['reuniao_ordinaria', 'assembleia', 'conselho'])->default('reuniao_ordinaria');
            $table->string('pdf_path');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diretoria_board_minutes');
    }
};
