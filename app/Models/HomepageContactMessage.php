<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageContactMessage extends Model
{
    protected $table = 'homepage_contact_messages';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'read_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }
}
