<?php

namespace Modules\Calendario\App\Models;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
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

    public const STATUS_FINISHED = 'finished';

    protected $table = 'eventos';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'status',
        'cover_path',
        'banner_path',
        'theme_config',
        'is_featured',
        'uuid',
        'start_date',
        'end_date',
        'all_day',
        'timezone',
        'visibility',
        'type',
        'location',
        'church_id',
        'registration_open',
        'registration_deadline',
        'capacity',
        'is_paid',
        'ticket_price',
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
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'all_day' => 'boolean',
            'registration_open' => 'boolean',
            'registration_deadline' => 'datetime',
            'is_paid' => 'boolean',
            'ticket_price' => 'decimal:2',
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

        static::saved(function () {
            Cache::forget('homepage.portal.upcoming_event_ids');
        });

        static::deleted(function () {
            Cache::forget('homepage.portal.upcoming_event_ids');
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
        return $q->where('start_date', '>=', now());
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

    public function isRegistrationPeriodOpen(?CarbonInterface $at = null): bool
    {
        if (! $this->registration_open) {
            return false;
        }
        $at = $at ?? now();
        if ($this->registration_deadline && $at->gt($this->registration_deadline)) {
            return false;
        }
        if ($this->start_date && $at->gt($this->start_date) && ! $this->all_day) {
            // allow until event day for all_day
        }

        return true;
    }

    public function getStartsAtAttribute(): mixed
    {
        return $this->start_date;
    }

    public function setStartsAtAttribute(mixed $value): void
    {
        $this->attributes['start_date'] = $value;
    }

    public function getEndsAtAttribute(): mixed
    {
        return $this->end_date;
    }

    public function setEndsAtAttribute(mixed $value): void
    {
        $this->attributes['end_date'] = $value;
    }

    public function getMaxParticipantsAttribute(): mixed
    {
        return $this->capacity;
    }

    public function setMaxParticipantsAttribute(mixed $value): void
    {
        $this->attributes['capacity'] = $value;
    }

    public function getRegistrationFeeAttribute(): mixed
    {
        return $this->ticket_price;
    }

    public function setRegistrationFeeAttribute(mixed $value): void
    {
        $this->attributes['ticket_price'] = $value;
    }

    /**
     * @return Collection<int, static>
     */
    public static function localChurchEventsOverlapping(CarbonInterface $startsAt, CarbonInterface $endsAt)
    {
        return static::query()
            ->whereNotNull('church_id')
            ->where('start_date', '<', $endsAt)
            ->where(function ($q) use ($startsAt) {
                $q->whereNull('end_date')->orWhere('end_date', '>', $startsAt);
            })
            ->with(['church:id,name'])
            ->orderBy('start_date')
            ->limit(40)
            ->get();
    }
}
