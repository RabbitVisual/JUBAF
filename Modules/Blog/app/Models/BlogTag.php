<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class BlogTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'color'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name') && empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    /**
     * Get the posts for this tag.
     */
    public function posts()
    {
        return $this->belongsToMany(BlogPost::class, 'blog_post_tags', 'tag_id', 'post_id');
    }

    /**
     * Get published posts for this tag.
     */
    public function publishedPosts()
    {
        return $this->posts()->where('status', 'published')->whereNotNull('published_at');
    }

    /**
     * Get posts count accessor.
     */
    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    /**
     * Get published posts count accessor.
     */
    public function getPublishedPostsCountAttribute()
    {
        return $this->publishedPosts()->count();
    }
}
