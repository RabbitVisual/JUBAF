<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function isSystemPermission(): bool
    {
        if (! array_key_exists('is_system', $this->attributes)) {
            return true;
        }

        $v = $this->attributes['is_system'];

        return $v === null ? true : (bool) $v;
    }
}
