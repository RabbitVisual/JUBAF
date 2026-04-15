<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'token',
        'token_hash',
        'abilities',
        'last_used_at',
        'expires_at',
        'is_active',
        'ip_whitelist',
        'rate_limit',
        'description',
    ];

    protected $casts = [
        'abilities' => 'array',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'ip_whitelist' => 'array',
        'rate_limit' => 'integer',
    ];

    protected $hidden = [
        'token_hash',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Gera um novo token
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Verifica se o token está ativo
     */
    public function isActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Verifica se o IP está na whitelist
     */
    public function isIpAllowed(string $ip): bool
    {
        if (empty($this->ip_whitelist)) {
            return true; // Sem whitelist = todos permitidos
        }

        return in_array($ip, $this->ip_whitelist);
    }

    /**
     * Scope para tokens ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }
}
