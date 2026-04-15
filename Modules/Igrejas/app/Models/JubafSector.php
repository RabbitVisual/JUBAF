<?php

namespace Modules\Igrejas\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class JubafSector extends Model
{
    protected $table = 'jubaf_sectors';

    public const SCOPE_GEOGRAPHIC = 'geographic';

    public const SCOPE_ADMINISTRATIVE = 'administrative';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'scope_kind',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (JubafSector $sector) {
            if (empty($sector->slug) && ! empty($sector->name)) {
                $sector->slug = static::uniqueSlugFromName($sector->name);
            }
        });
    }

    public static function uniqueSlugFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'setor';
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function churches(): HasMany
    {
        return $this->hasMany(Church::class, 'jubaf_sector_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'jubaf_sector_id');
    }
}
