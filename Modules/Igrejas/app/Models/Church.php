<?php

namespace Modules\Igrejas\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Church extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const COOPERATION_ATIVA = 'ativa';

    public const COOPERATION_SUSPENSA = 'suspensa';

    public const COOPERATION_EM_ADAPTACAO = 'em_adaptacao';

    public const KIND_CHURCH = 'church';

    public const KIND_CONGREGATION = 'congregation';

    protected $table = 'igrejas_churches';

    protected $fillable = [
        'name',
        'slug',
        'kind',
        'parent_church_id',
        'cnpj',
        'logo_path',
        'cover_path',
        'sector',
        'jubaf_sector_id',
        'foundation_date',
        'cooperation_status',
        'pastor_user_id',
        'unijovem_leader_user_id',
        'city',
        'address',
        'phone',
        'email',
        'asbaf_notes',
        'is_active',
        'joined_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'joined_at' => 'date',
            'foundation_date' => 'date',
            'metadata' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Church $church) {
            if (empty($church->slug)) {
                $church->slug = static::uniqueSlugFromName($church->name);
            }
        });

        static::saving(function (Church $church) {
            if ($church->jubaf_sector_id) {
                $name = JubafSector::query()->whereKey($church->jubaf_sector_id)->value('name');
                if ($name) {
                    $church->sector = $name;
                }
            }
        });
    }

    public static function uniqueSlugFromName(string $name): string
    {
        $base = Str::slug($name) ?: 'igreja';
        $slug = $base;
        $i = 1;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'church_id');
    }

    public function leaders(): HasMany
    {
        return $this->users()->whereHas('roles', fn ($q) => $q->where('name', 'lider'));
    }

    public function jovensMembers(): HasMany
    {
        return $this->users()->whereHas('roles', fn ($q) => $q->where('name', 'jovens'));
    }

    public function parentChurch(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_church_id');
    }

    public function congregations(): HasMany
    {
        return $this->hasMany(self::class, 'parent_church_id');
    }

    public function pastor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pastor_user_id');
    }

    public function unijovemLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unijovem_leader_user_id');
    }

    public function changeRequests(): HasMany
    {
        return $this->hasMany(ChurchChangeRequest::class, 'church_id');
    }

    public function jubafSector(): BelongsTo
    {
        return $this->belongsTo(JubafSector::class, 'jubaf_sector_id');
    }

    public function scopeCooperationStatus($query, string $status)
    {
        return $query->where('cooperation_status', $status);
    }

    public function scopeSector($query, ?string $sector)
    {
        if ($sector === null || $sector === '') {
            return $query;
        }

        return $query->where('sector', $sector);
    }

    public static function cooperationStatuses(): array
    {
        return [
            self::COOPERATION_ATIVA,
            self::COOPERATION_SUSPENSA,
            self::COOPERATION_EM_ADAPTACAO,
        ];
    }

    /** @return list<string> */
    public static function kinds(): array
    {
        return [self::KIND_CHURCH, self::KIND_CONGREGATION];
    }

    public function isCongregation(): bool
    {
        return $this->kind === self::KIND_CONGREGATION;
    }

    public function isChurch(): bool
    {
        return $this->kind === self::KIND_CHURCH;
    }

    public function institutionalAgeYears(): ?int
    {
        $ref = $this->foundation_date ?? $this->joined_at;

        return $ref ? max(0, (int) round($ref->diffInYears(now()))) : null;
    }
}
