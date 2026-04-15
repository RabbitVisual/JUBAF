<?php

namespace Modules\PainelJovens\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JovemPerfil extends Model
{
    protected $table = 'jovens_perfis';

    protected $fillable = [
        'user_id',
        'marital_status',
        'profession',
        'census_bio',
        'social_links',
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
