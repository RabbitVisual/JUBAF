<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Get config value by key
     */
    public static function get(string $key, $default = null)
    {
        $config = static::where('key', $key)->first();
        
        if (!$config) {
            return $default;
        }

        return match($config->type) {
            'integer' => (int) $config->value,
            'boolean' => filter_var($config->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($config->value, true),
            default => $config->value,
        };
    }

    /**
     * Set config value by key
     */
    public static function set(string $key, $value, string $type = 'string', string $group = 'general', ?string $description = null): self
    {
        $config = static::firstOrNew(['key' => $key]);
        
        $config->value = match($type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            'integer' => (string) (int) $value,
            default => (string) $value,
        };
        
        $config->type = $type;
        $config->group = $group;
        $config->description = $description;
        $config->save();

        return $config;
    }

    /**
     * Get all configs by group
     */
    public static function getByGroup(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('group', $group)->get();
    }
}
