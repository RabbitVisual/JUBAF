<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Bible\App\Models\Book;
use Modules\Bible\App\Models\BibleVersion;

class Devotional extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    public const AUTHOR_USER = 'user';

    public const AUTHOR_BOARD_MEMBER = 'board_member';

    public const AUTHOR_PASTOR_GUEST = 'pastor_guest';

    protected $fillable = [
        'title',
        'slug',
        'devotional_date',
        'scripture_reference',
        'scripture_text',
        'bible_version_id',
        'body',
        'theme',
        'cover_image_path',
        'video_path',
        'video_url',
        'status',
        'author_type',
        'user_id',
        'board_member_id',
        'guest_author_name',
        'guest_author_title',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'devotional_date' => 'date',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function boardMember(): BelongsTo
    {
        return $this->belongsTo(BoardMember::class, 'board_member_id');
    }

    public function resolvedBibleVersion(): ?BibleVersion
    {
        if (! module_enabled('Bible') || ! $this->bible_version_id) {
            return null;
        }

        return BibleVersion::query()->find($this->bible_version_id);
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at');
    }

    public function scopePublishedOrdered($query)
    {
        return $query->published()
            ->orderByDesc('devotional_date')
            ->orderByDesc('published_at');
    }

    public function coverImageUrl(): ?string
    {
        if (! $this->cover_image_path) {
            return null;
        }

        return asset('storage/'.$this->cover_image_path);
    }

    public function videoUrl(): ?string
    {
        if ($this->video_path) {
            return asset('storage/'.$this->video_path);
        }

        return $this->video_url ?: null;
    }

    public function authorDisplayName(): string
    {
        return match ($this->author_type) {
            self::AUTHOR_PASTOR_GUEST => $this->guest_author_name ?: 'Convidado',
            self::AUTHOR_BOARD_MEMBER => $this->boardMember?->full_name ?: ($this->user?->name ?: 'Diretoria'),
            default => $this->user?->name ?: 'Autor',
        };
    }

    public function authorSubtitle(): ?string
    {
        return match ($this->author_type) {
            self::AUTHOR_PASTOR_GUEST => $this->guest_author_title,
            self::AUTHOR_BOARD_MEMBER => $this->boardMember?->public_title,
            default => null,
        };
    }

    /** User for avatar component when linked account exists */
    public function avatarUser(): ?User
    {
        if ($this->user_id && $this->user) {
            return $this->user;
        }
        if ($this->author_type === self::AUTHOR_BOARD_MEMBER && $this->boardMember?->user_id) {
            return $this->boardMember->user;
        }

        return null;
    }

    /**
     * Internal Bible reader URL for the passage chapter (no external sites).
     */
    public function scriptureChapterPublicUrl(): ?string
    {
        if (! module_enabled('Bible') || ! Route::has('bible.public.chapter')) {
            return null;
        }

        $ref = trim($this->scripture_reference);
        if (! preg_match('/^(.+?)\s+(\d+):/u', $ref, $m)) {
            return null;
        }

        $bookName = trim($m[1]);
        $chapter = (int) $m[2];

        $versionAbbr = $this->resolvedBibleVersion()?->abbreviation
            ?? (module_enabled('Bible')
                ? BibleVersion::query()->where('is_active', true)->orderByDesc('is_default')->value('abbreviation')
                : null);

        if (! $versionAbbr) {
            return null;
        }

        $book = Book::query()
            ->whereHas('bibleVersion', fn ($q) => $q->where('abbreviation', $versionAbbr))
            ->where(function ($q) use ($bookName) {
                $q->where('name', $bookName)->orWhere('abbreviation', $bookName);
            })
            ->first();

        if (! $book) {
            $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $bookName);

            $book = Book::query()
                ->whereHas('bibleVersion', fn ($q) => $q->where('abbreviation', $versionAbbr))
                ->where('name', 'like', '%'.$escaped.'%')
                ->first();
        }

        if (! $book) {
            return null;
        }

        return route('bible.public.chapter', [$versionAbbr, $book->book_number, $chapter]);
    }

    /**
     * Leitor Bíblia no Painel de Jovens (mesma passagem que o site público).
     */
    public function scriptureChapterJovensUrl(): ?string
    {
        if (! module_enabled('Bible') || ! Route::has('jovens.bible.chapter')) {
            return null;
        }

        $ref = trim($this->scripture_reference);
        if (! preg_match('/^(.+?)\s+(\d+):/u', $ref, $m)) {
            return null;
        }

        $bookName = trim($m[1]);
        $chapter = (int) $m[2];

        $versionAbbr = $this->resolvedBibleVersion()?->abbreviation
            ?? BibleVersion::query()->where('is_active', true)->orderByDesc('is_default')->value('abbreviation');

        if (! $versionAbbr) {
            return null;
        }

        $book = Book::query()
            ->whereHas('bibleVersion', fn ($q) => $q->where('abbreviation', $versionAbbr))
            ->where(function ($q) use ($bookName) {
                $q->where('name', $bookName)->orWhere('abbreviation', $bookName);
            })
            ->first();

        if (! $book) {
            $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $bookName);

            $book = Book::query()
                ->whereHas('bibleVersion', fn ($q) => $q->where('abbreviation', $versionAbbr))
                ->where('name', 'like', '%'.$escaped.'%')
                ->first();
        }

        if (! $book) {
            return null;
        }

        return route('jovens.bible.chapter', [$versionAbbr, $book->book_number, $chapter]);
    }

    public static function slugFromTitle(string $title): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = 'devocional';
        }

        $slug = $base;
        $i = 1;
        while (static::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
