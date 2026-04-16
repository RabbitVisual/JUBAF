<?php

namespace Modules\Talentos\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TalentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'availability_text',
        'is_searchable',
    ];

    protected function casts(): array
    {
        return [
            'is_searchable' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(TalentSkill::class, 'talent_profile_skill')
            ->withPivot(['level', 'validated_at', 'validated_by'])
            ->withTimestamps();
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(TalentArea::class, 'talent_profile_area')
            ->withTimestamps();
    }
}
