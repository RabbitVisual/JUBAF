<?php

namespace Modules\Notificacoes\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'title',
        'message',
        'user_id',
        'role',
        'module_source',
        'entity_type',
        'entity_id',
        'is_read',
        'read_at',
        'action_url',
        'data',
        'panel',
    ];

    /**
     * Scope para filtrar notificações por painel
     */
    public function scopeForPanel($query, ?string $panel = null)
    {
        if (!$panel) {
            return $query;
        }

        if ($panel === 'diretoria') {
            return $query->where(function ($subQuery) {
                $subQuery->where('panel', 'diretoria')
                    ->orWhere('panel', 'co-admin');
            });
        }

        return $query->where('panel', $panel);
    }

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            // Disparar evento de broadcasting
            event(new \Modules\Notificacoes\App\Events\NotificacaoLida($this));
        }
    }

    /**
     * Create a notification
     */
    public static function createNotification(
        string $type,
        string $title,
        string $message,
        ?int $userId = null,
        ?string $role = null,
        ?array $data = null,
        ?string $actionUrl = null,
        ?string $moduleSource = null,
        ?string $entityType = null,
        ?int $entityId = null,
        ?string $panel = null
    ): self {
        // Deduplicação: Verificar se já existe uma notificação idêntica criada nos últimos 10 segundos
        if ($userId) {
            $duplicate = static::where('user_id', $userId)
                ->where('type', $type)
                ->where('title', $title)
                ->where('message', $message)
                ->where('panel', $panel)
                ->where('created_at', '>=', now()->subSeconds(10))
                ->first();

            if ($duplicate) {
                return $duplicate;
            }
        }

        $notificacao = static::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'user_id' => $userId,
            'role' => $role,
            'data' => $data,
            'action_url' => $actionUrl,
            'module_source' => $moduleSource,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'panel' => $panel,
            'is_read' => false,
        ]);

        // Disparar evento de broadcasting para tempo real
        event(new \Modules\Notificacoes\App\Events\NotificacaoCriada($notificacao));

        return $notificacao;
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for user notifications
     * Retorna notificações do usuário OU notificações globais (sem user_id)
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere(function($subQ) {
                  // Notificações globais (sem user_id específico)
                  $subQ->whereNull('user_id')
                       ->whereNull('role');
              });
        });
    }

    /**
     * Scope for role notifications
     */
    public function scopeForRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for module notifications
     */
    public function scopeForModule($query, string $module)
    {
        return $query->where('module_source', $module);
    }

    /**
     * Get type badge color
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'success' => 'emerald',
            'warning' => 'yellow',
            'error' => 'red',
            'alert' => 'orange',
            'system' => 'blue',
            default => 'slate',
        };
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'success' => 'check-circle',
            'warning' => 'exclamation-triangle',
            'error' => 'x-circle',
            'alert' => 'bell-alert',
            'system' => 'cog-6-tooth',
            default => 'information-circle',
        };
    }

    /**
     * Get type text in Portuguese
     */
    public function getTypeTextoAttribute(): string
    {
        return match($this->type) {
            'success' => 'Sucesso',
            'info' => 'Informação',
            'warning' => 'Aviso',
            'error' => 'Erro',
            'alert' => 'Alerta',
            'system' => 'Sistema',
            default => 'Informação',
        };
    }
}
