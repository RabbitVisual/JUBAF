<?php

namespace Modules\Church\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\Church\Database\Factories\ChurchFactory;

class Church extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'unijovem_name',
        'sector',
        'leader_name',
        'leader_phone',
        'unijovem_representative_user_id',
        'city',
        'neighborhood',
        'address',
        'is_active',
        'logo_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class, 'church_id');
    }

    public function unijovemRepresentative()
    {
        return $this->belongsTo(\App\Models\User::class, 'unijovem_representative_user_id');
    }

    /**
     * Inscrições em eventos (participantes com esta igreja local).
     */
    public function participants()
    {
        return $this->hasMany(\Modules\Events\App\Models\Participant::class, 'church_id');
    }

    /** @deprecated Use participants() */
    public function registrations()
    {
        return $this->participants();
    }

    // protected static function newFactory(): ChurchFactory
    // {
    //     // return ChurchFactory::new();
    // }
}
