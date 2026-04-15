<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('chat_conversations')) {
            Schema::create('chat_conversations', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->boolean('is_group')->default(false);
                $table->string('name')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->string('whatsapp_remote_jid', 191)->nullable()->index();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('chat_conversation_user')) {
            Schema::create('chat_conversation_user', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('last_read_at')->nullable();
                $table->timestamps();

                $table->unique(['conversation_id', 'user_id']);
                $table->index('user_id');
            });
        }

        if (! Schema::hasTable('chat_conversation_messages')) {
            Schema::create('chat_conversation_messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->text('body');
                $table->string('attachment_path')->nullable();
                $table->timestamps();

                $table->index(['conversation_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_conversation_messages');
        Schema::dropIfExists('chat_conversation_user');
        Schema::dropIfExists('chat_conversations');
    }
};
