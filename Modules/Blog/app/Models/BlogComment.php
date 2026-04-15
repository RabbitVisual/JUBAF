<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'author_name',
        'author_email',
        'author_ip',
        'user_agent',
        'content',
        'status',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved_at' => 'datetime'
    ];

    /**
     * Get the post for this comment.
     */
    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }

    /**
     * Get the user for this comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(BlogComment::class, 'parent_id');
    }

    /**
     * Get the replies for this comment.
     */
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_id');
    }

    /**
     * Get the user who approved this comment.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for approved comments.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending comments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for top-level comments.
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the author name.
     */
    public function getAuthorDisplayNameAttribute()
    {
        return $this->user ? $this->user->name : $this->author_name;
    }

    /**
     * Check if comment is approved.
     */
    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }
}
