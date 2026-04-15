<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('secretaria_documents') || ! Schema::hasTable('secretaria_ged_documents')) {
            return;
        }

        DB::table('secretaria_documents')
            ->orderBy('id')
            ->lazyById()
            ->each(function ($legacy): void {
                $exists = DB::table('secretaria_ged_documents')
                    ->where('file_path', (string) ($legacy->path ?? ''))
                    ->where('title', (string) ($legacy->title ?? 'Documento sem título'))
                    ->exists();

                if ($exists) {
                    return;
                }

                $isPublic = match ((string) ($legacy->visibility ?? 'public')) {
                    'public', 'leaders' => true,
                    default => false,
                };

                DB::table('secretaria_ged_documents')->insert([
                    'uuid' => (string) Str::uuid(),
                    'title' => (string) ($legacy->title ?? 'Documento sem título'),
                    'category' => 'Outros',
                    'file_path' => (string) ($legacy->path ?? ''),
                    'igreja_id' => $legacy->church_id,
                    'is_public' => $isPublic,
                    'uploaded_by_id' => $legacy->uploaded_by_id,
                    'created_at' => $legacy->created_at ?? now(),
                    'updated_at' => $legacy->updated_at ?? now(),
                ]);
            });
    }

    public function down(): void
    {
        // Intencionalmente sem rollback destrutivo dos dados migrados.
    }
};
