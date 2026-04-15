<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomepageNewsletterSubscriber extends Model
{
    use SoftDeletes;

    protected $table = 'homepage_newsletter_subscribers';

    protected $fillable = [
        'email',
        'name',
        'is_active',
        'is_confirmed',
        'subscribed_at',
        'confirmation_token',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_confirmed' => 'boolean',
            'subscribed_at' => 'datetime',
        ];
    }

    public function scopeActiveList($query)
    {
        return $query->where('is_active', true)->where('is_confirmed', true);
    }

    public static function isEmailSubscribed(string $email): bool
    {
        return static::query()
            ->where('email', mb_strtolower(trim($email)))
            ->where('is_active', true)
            ->exists();
    }
}
