<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * @var \Illuminate\Database\Eloquent\Model $this
 */
trait HasHistory
{
    /**
     * Registra uma mudança no histórico
     */
    public function recordChange(string $action, array $oldData = null, array $newData = null, $userId = null): void
    {
        if (!Schema::hasTable('audit_logs')) {
            return;
        }

        $userId = $userId ?? Auth::id();

        DB::table('audit_logs')->insert([
            'user_id' => $userId,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => $action, // created, updated, deleted, restored
            'old_values' => $oldData ? json_encode($oldData) : null,
            'new_values' => $newData ? json_encode($newData) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Obtém o histórico completo do modelo
     */
    public function getHistory(int $limit = 50)
    {
        if (!Schema::hasTable('audit_logs')) {
            return collect([]);
        }

        return DB::table('audit_logs')
            ->where('model_type', get_class($this))
            ->where('model_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($log) {
                $log->old_data = $log->old_values ? json_decode($log->old_values, true) : null;
                $log->new_data = $log->new_values ? json_decode($log->new_values, true) : null;
                return $log;
            });
    }

    /**
     * Compara duas versões do modelo
     */
    public function compareVersions(array $oldData, array $newData): array
    {
        $changes = [];

        foreach ($newData as $key => $newValue) {
            $oldValue = $oldData[$key] ?? null;

            if ($oldValue !== $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changes;
    }

    /**
     * Boot do trait - registra eventos automaticamente
     * Nota: Este método será chamado automaticamente pelo Laravel se o modelo usar este trait
     */
    protected static function bootHasHistory(): void
    {
        static::created(function (\Illuminate\Database\Eloquent\Model $model) {
            if (method_exists($model, 'recordChange')) {
                $model->recordChange('created', null, $model->getAttributes());
            }
        });

        static::updated(function (\Illuminate\Database\Eloquent\Model $model) {
            if (method_exists($model, 'recordChange')) {
                $oldData = $model->getOriginal();
                $newData = $model->getChanges();
                $model->recordChange('updated', $oldData, $newData);
            }
        });

        static::deleted(function (\Illuminate\Database\Eloquent\Model $model) {
            if (method_exists($model, 'recordChange')) {
                $model->recordChange('deleted', $model->getAttributes(), null);
            }
        });
    }
}
