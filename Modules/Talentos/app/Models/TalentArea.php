<?php

namespace Modules\Talentos\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TalentArea extends Model
{
    protected $fillable = ['name'];

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(TalentProfile::class, 'talent_profile_area')
            ->withTimestamps();
    }
}
