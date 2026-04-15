<?php

namespace Modules\Calendario\App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Avisos\App\Models\Aviso;
use Modules\Blog\App\Models\BlogPost;
use Modules\Igrejas\App\Models\Church;

class CalendarEvent extends Model
{
    use SoftDeletes;

    public const VIS_PUBLIC = 'publico';

    public const VIS_AUTH = 'autenticado';

    public const VIS_DIRETORIA = 'diretoria';

    public const VIS_LIDERES = 'lideres';

    public const VIS_JOVENS = 'jovens';

    public const STATUS_DRAFT = 'draft';

    public const STATUS_WAITING_APPROVAL = 'waiting_approval';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_CANCELLED = 'cancelled';

    protected $table = 'calendar_events';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'cover_path',
        'banner_path',
        'theme_config',
        'is_featured',
        'starts_at',
        'ends_at',
        'all_day',
        'timezone',
        'visibility',
        'type',
        'location',
        'church_id',
        'registration_open',
        'registration_deadline',
        'max_participants',
        'registration_fee',
        'form_fields',
        'schedule',
        'requires_council_approval',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_whatsapp',
        'min_age',
        'max_age',
        'max_per_registration',
        'metadata',
        'preview_token',
        'blog_post_id',
        'aviso_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'all_day' => 'boolean',
            'registration_open' => 'boolean',
            'registration_deadline' => 'datetime',
            'registration_fee' => 'decimal:2',
            'theme_config' => 'array',
            'form_fields' => 'array',
            'schedule' => 'array',
            'metadata' => 'array',
            'requires_council_approval' => 'boolean',
            'is_featured' => 'boolean',
            'min_age' => 'integer',
            'max_age' => 'integer',
            'max_per_registration' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CalendarEvent $e): void {
            if (empty($e->slug)) {
                $e->slug = Str::slug($e->title).'-'.Str::random(4);
            }
            if (empty($e->status)) {
                $e->status = self::STATUS_PUBLISHED;
            }
            if (empty($e->preview_token)) {
                $e->preview_token = Str::random(48);
            }
        });
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class, 'church_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }

    public function aviso(): BelongsTo
    {
        return $this->belongsTo(Aviso::class, 'aviso_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(CalendarRegistration::class, 'event_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(CalendarEventBatch::class, 'event_id')->orderBy('sort_order')->orderBy('id');
    }

    public function priceRules(): HasMany
    {
        return $this->hasMany(CalendarPriceRule::class, 'event_id')->orderBy('priority')->orderBy('id');
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', self::STATUS_PUBLISHED);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('starts_at', '>=', now());
    }

    public function confirmedCount(): int
    {
        return $this->registrations()->where('status', 'confirmed')->count();
    }

    /**
     * @return array{theme: string, primary_color: string, secondary_color: string}
     */
    public function resolvedThemeConfig(): array
    {
        $c = $this->theme_config ?? [];

        return [
            'theme' => $c['theme'] ?? 'corporate',
            'primary_color' => $c['primary_color'] ?? '#1e40af',
            'secondary_color' => $c['secondary_color'] ?? '#0f172a',
        ];
    }

    public function userCanView(?User $user): bool
    {
        if ($this->visibility === self::VIS_PUBLIC) {
            return true;
        }

        if (! $user) {
            return false;
        }

        if ($this->visibility === self::VIS_AUTH) {
            return true;
        }

        if ($this->visibility === self::VIS_DIRETORIA) {
            return $user->can('calendario.events.view');
        }

        if ($this->visibility === self::VIS_LIDERES) {
            return $user->hasRole('lider') || $user->hasRole('super-admin')
                || $user->hasAnyRole(['presidente', 'vice-presidente-1', 'vice-presidente-2', 'secretario-1', 'secretario-2', 'tesoureiro-1', 'tesoureiro-2']);
        }

        if ($this->visibility === self::VIS_JOVENS) {
            return $user->hasRole('jovens') || $user->hasRole('super-admin')
                || $user->hasAnyRole(['presidente', 'vice-presidente-1', 'vice-presidente-2', 'secretario-1', 'secretario-2', 'tesoureiro-1', 'tesoureiro-2']);
        }

        return false;
    }

    public function churchScopeAllows(?User $user): bool
    {
        if ($this->church_id === null) {
            return true;
        }

        if (! $user) {
            return false;
        }

        return in_array((int) $this->church_id, $user->affiliatedChurchIds(), true);
    }

    public function isRegistrationPeriodOpen(?\Carbon\CarbonInterface $at = null): bool
    {
        if (! $this->registration_open) {
            return false;
        }
        $at = $at ?? now();
        if ($this->registration_deadline && $at->gt($this->registration_deadline)) {
            return false;
        }
        if ($this->starts_at && $at->gt($this->starts_at) && ! $this->all_day) {
            // allow until event day for all_day
        }

        return true;
    }

    /**
     * @return \Illuminate\Support\Collection<int, static>
     */
    public static function localChurchEventsOverlapping(\Carbon\CarbonInterface $startsAt, \Carbon\CarbonInterface $endsAt)
    {
        return static::query()
            ->whereNotNull('church_id')
            ->where('starts_at', '<', $endsAt)
            ->where(function ($q) use ($startsAt) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', $startsAt);
            })
            ->with(['church:id,name'])
            ->orderBy('starts_at')
            ->limit(40)
            ->get();
    }
}
