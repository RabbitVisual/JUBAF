<?php

namespace Modules\Talentos\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TalentSkill extends Model
{
    public const LEVEL_BASIC = 'basic';

    public const LEVEL_INTERMEDIATE = 'intermediate';

    public const LEVEL_ADVANCED = 'advanced';

    protected $fillable = ['name'];

    /**
     * @return array<string, string>
     */
    public static function levelOptions(): array
    {
        return [
            self::LEVEL_BASIC => 'Básico',
            self::LEVEL_INTERMEDIATE => 'Intermediário',
            self::LEVEL_ADVANCED => 'Avançado',
        ];
    }

    public static function levelLabel(?string $level): ?string
    {
        if ($level === null || $level === '') {
            return null;
        }

        return self::levelOptions()[$level] ?? $level;
    }

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(TalentProfile::class, 'talent_profile_skill')
            ->withPivot('level')
            ->withTimestamps();
    }
}
