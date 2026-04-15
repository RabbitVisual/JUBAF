<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the posts for this category.
     */
    public function posts()
    {
        return $this->hasMany(BlogPost::class, 'category_id');
    }

    /**
     * Get published posts for this category.
     */
    public function publishedPosts()
    {
        return $this->posts()->where('status', 'published')->whereNotNull('published_at');
    }

    /**
     * Scope for active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered categories.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
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
