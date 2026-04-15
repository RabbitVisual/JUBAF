<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfflineSyncLog extends Model
{
    protected $fillable = [
        'client_uuid',
        'user_id',
        'action_type',
        'model_type',
        'model_id',
        'payload',
        'payload_hash',
        'status',
        'error_message',
        'result_data',
        'client_timestamp',
        'synced_at',
        'processed_at',
        'device_id',
        'device_info',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'payload' => 'array',
        'result_data' => 'array',
        'client_timestamp' => 'datetime',
        'synced_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    // Status possíveis
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_DUPLICATE = 'duplicate';

    /**
     * Relacionamento com o usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Verifica se uma ação já foi processada (idempotência)
     */
    public static function wasAlreadyProcessed(string $clientUuid): bool
    {
        return self::where('client_uuid', $clientUuid)
            ->whereIn('status', [self::STATUS_COMPLETED, self::STATUS_PROCESSING])
            ->exists();
    }

    /**
     * Registra uma nova ação de sincronização
     */
    public static function registerAction(array $data): self
    {
        $payload = $data['payload'] ?? [];
        
        return self::create([
            'client_uuid' => $data['client_uuid'],
            'user_id' => $data['user_id'],
            'action_type' => $data['action_type'],
            'model_type' => $data['model_type'] ?? null,
            'model_id' => $data['model_id'] ?? null,
            'payload' => $payload,
            'payload_hash' => hash('sha256', json_encode($payload)),
            'status' => self::STATUS_PENDING,
            'client_timestamp' => $data['client_timestamp'] ?? null,
            'synced_at' => now(),
            'device_id' => $data['device_id'] ?? null,
            'device_info' => $data['device_info'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => substr(request()->userAgent() ?? '', 0, 500),
        ]);
    }

    /**
     * Marca como duplicada
     */
    public function markAsDuplicate(): self
    {
        $this->update([
            'status' => self::STATUS_DUPLICATE,
            'processed_at' => now(),
            'error_message' => 'Ação já foi processada anteriormente',
        ]);
        
        return $this;
    }

    /**
     * Marca como processando
     */
    public function markAsProcessing(): self
    {
        $this->update(['status' => self::STATUS_PROCESSING]);
        return $this;
    }

    /**
     * Marca como concluída
     */
    public function markAsCompleted(array $resultData = []): self
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'processed_at' => now(),
            'result_data' => $resultData,
        ]);
        
        return $this;
    }

    /**
     * Marca como falha
     */
    public function markAsFailed(string $errorMessage): self
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'processed_at' => now(),
            'error_message' => $errorMessage,
        ]);
        
        return $this;
    }

    /**
     * Scope para ações do usuário
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para ações pendentes
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope para ações concluídas
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}

