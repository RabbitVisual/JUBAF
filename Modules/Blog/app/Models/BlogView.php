<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class BlogView extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'user_id',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime'
    ];

    public $timestamps = false;

    /**
     * Get the post for this view.
     */
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    /**
     * Get the user for this view.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
