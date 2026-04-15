<?php

namespace Modules\Blog\App\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $status
 * @property Carbon|null $published_at
 * @property int $category_id
 * @property int $author_id
 * @property int $views_count
 * @property int $likes_count
 * @property int $shares_count
 * @property-read BlogCategory $category
 * @property-read User $author
 */
class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'gallery_images',
        'category_id',
        'author_id',
        'status',
        'published_at',
        'is_featured',
        'allow_comments',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views_count',
        'likes_count',
        'shares_count',
        'module_data',
        'auto_generated_from',
        'attachments',
        'og_image',
        'og_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'allow_comments' => 'boolean',
        'gallery_images' => 'array',
        'meta_keywords' => 'array',
        'module_data' => 'array',
        'attachments' => 'array',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->author_id)) {
                $post->author_id = auth()->id();
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saved(function () {
            Cache::forget('blog.public.featured');
        });

        static::deleted(function () {
            Cache::forget('blog.public.featured');
        });
    }

    /**
     * Get the category for this post.
     */
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * Get the author for this post.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the tags for this post.
     */
    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags', 'post_id', 'tag_id');
    }

    /**
     * Get the comments for this post.
     */
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'post_id');
    }

    /**
     * Get approved comments for this post.
     */
    public function approvedComments()
    {
        return $this->comments()->where('status', 'approved');
    }

    /**
     * Get the views for this post.
     */
    public function views()
    {
        return $this->hasMany(BlogView::class, 'post_id');
    }

    /**
     * Scope for published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Scope for featured posts.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for posts by category.
     */
    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    /**
     * Scope for posts by tag.
     */
    public function scopeByTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug);
        });
    }

    /**
     * Scope for search.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    /**
     * Get the excerpt or generate one.
     */
    public function getExcerptAttribute($value)
    {
        if (! empty($value)) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Get reading time in minutes.
     */
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // Average reading speed: 200 words per minute

        return max(1, $minutes);
    }

    /**
     * Check if post is published.
     */
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' &&
               $this->published_at &&
               $this->published_at <= now();
    }

    /**
     * Get next published post.
     */
    public function getNextPostAttribute()
    {
        return static::published()
            ->where('id', '>', $this->id)
            ->orderBy('id')
            ->first();
    }

    /**
     * Get previous published post.
     */
    public function getPreviousPostAttribute()
    {
        return static::published()
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * Get related posts.
     */
    public function getRelatedPostsAttribute()
    {
        return static::published()
            ->where('id', '!=', $this->id)
            ->where('category_id', $this->category_id)
            ->limit(3)
            ->get();
    }

    /**
     * Increment views count.
     */
    public function incrementViews($ipAddress = null, $userAgent = null, $userId = null)
    {
        // Verificar se já foi visualizado pelo mesmo IP nas últimas 24h
        $recentView = $this->views()
            ->where('ip_address', $ipAddress ?: request()->ip())
            ->where('viewed_at', '>', now()->subDay())
            ->exists();

        if (! $recentView) {
            $this->views()->create([
                'ip_address' => $ipAddress ?: request()->ip(),
                'user_agent' => $userAgent ?: request()->userAgent(),
                'user_id' => $userId ?: auth()->id(),
                'viewed_at' => now(),
            ]);

            $this->increment('views_count');
        }
    }

    /**
     * Get statistics accessor.
     */
    public function getEstatisticasAttribute()
    {
        return [
            'total_views' => $this->views_count,
            'total_likes' => $this->likes_count,
            'total_shares' => $this->shares_count,
            'total_comments' => $this->comments()->count(),
            'approved_comments' => $this->approvedComments()->count(),
            'pending_comments' => $this->comments()->where('status', 'pending')->count(),
        ];
    }
}
