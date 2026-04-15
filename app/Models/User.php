<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected static function booted(): void
    {
        static::saving(function (User $user) {
            $first = trim((string) ($user->first_name ?? ''));
            $last = trim((string) ($user->last_name ?? ''));

            if ($first === '' && filled($user->name)) {
                $parts = preg_split('/\s+/u', trim((string) $user->name), 2, PREG_SPLIT_NO_EMPTY) ?: [];
                $user->first_name = $parts[0] ?? 'Utilizador';
                if ($last === '' && isset($parts[1])) {
                    $user->last_name = $parts[1];
                    $last = $parts[1];
                }
                $first = $user->first_name;
            }

            $composed = trim($first.' '.$last);

            $user->name = $composed !== '' ? $composed : ($first !== '' ? $first : 'Utilizador');
            if ($user->first_name === null || trim((string) $user->first_name) === '') {
                $user->first_name = $user->name;
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'cpf',
        'password',
        'phone',
        'church_phone',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'birth_date',
        'active',
        'photo',
        'cover_photo',
        'cover_position_x',
        'cover_position_y',
        'church_id',
        'jubaf_sector_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }

    // Relacionamentos
    public function blogPosts()
    {
        return $this->hasMany(\Modules\Blog\App\Models\BlogPost::class, 'author_id');
    }

    public function blogComments()
    {
        return $this->hasMany(\Modules\Blog\App\Models\BlogComment::class, 'user_id');
    }

    /**
     * Versículos da Bíblia marcados como favoritos pelo utilizador.
     *
     * @return HasMany<\Modules\Bible\App\Models\BibleFavorite, $this>
     */
    public function bibleFavorites(): HasMany
    {
        return $this->hasMany(\Modules\Bible\App\Models\BibleFavorite::class);
    }

    public function church()
    {
        return $this->belongsTo(\Modules\Igrejas\App\Models\Church::class, 'church_id');
    }

    public function jubafSector()
    {
        return $this->belongsTo(\Modules\Igrejas\App\Models\JubafSector::class, 'jubaf_sector_id');
    }

    /**
     * Vice-presidentes com setor atribuído: cadastro e relatórios filtrados ao setor.
     */
    public function restrictsChurchDirectoryToSector(): bool
    {
        return $this->hasAnyRole(['vice-presidente-1', 'vice-presidente-2'])
            && $this->jubaf_sector_id !== null;
    }

    public function canAccessChurchInSectorScope(\Modules\Igrejas\App\Models\Church $church): bool
    {
        if (! $this->restrictsChurchDirectoryToSector()) {
            return true;
        }

        return (int) $church->jubaf_sector_id === (int) $this->jubaf_sector_id;
    }

    /**
     * Igrejas adicionais (líder/pastor com várias congregações).
     *
     * @return BelongsToMany<\Modules\Igrejas\App\Models\Church, $this>
     */
    public function assignedChurches(): BelongsToMany
    {
        return $this->belongsToMany(
            \Modules\Igrejas\App\Models\Church::class,
            'user_churches',
            'user_id',
            'church_id'
        )->withPivot('role_on_church')->withTimestamps();
    }

    /**
     * IDs de igreja para filtrar atas/documentos publicados (principal + pivot).
     *
     * @return list<int>
     */
    public function churchIdsForSecretariaScope(): array
    {
        return $this->affiliatedChurchIds();
    }

    /**
     * Igreja principal + congregações ligadas no pivot (líder/pastor multi-igreja).
     *
     * @return list<int>
     */
    public function affiliatedChurchIds(): array
    {
        $fromPivot = [];
        if (Schema::hasTable('user_churches')) {
            $fromPivot = DB::table('user_churches')
                ->where('user_id', $this->id)
                ->pluck('church_id')
                ->all();
        }

        return collect([$this->church_id])
            ->merge($fromPivot)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    public function talentProfile()
    {
        return $this->hasOne(\Modules\Talentos\App\Models\TalentProfile::class);
    }

    public function talentAssignments()
    {
        return $this->hasMany(\Modules\Talentos\App\Models\TalentAssignment::class);
    }

    /**
     * Pedidos de alteração de dados sensíveis (e-mail, CPF) para análise da diretoria.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ProfileSensitiveDataRequest, $this>
     */
    public function profileSensitiveDataRequests()
    {
        return $this->hasMany(ProfileSensitiveDataRequest::class, 'user_id');
    }

    /**
     * Até 3 fotos alternáveis; uma marcada como ativa sincroniza com {@see $photo}.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<UserProfilePhoto, $this>
     */
    public function profilePhotos()
    {
        return $this->hasMany(UserProfilePhoto::class)->orderBy('sort_order')->orderBy('id');
    }

    public function activeProfilePhoto(): ?UserProfilePhoto
    {
        return $this->profilePhotos()->where('is_active', true)->first();
    }

    public function hasPendingProfileSensitiveRequest(string $field): bool
    {
        return $this->profileSensitiveDataRequests()
            ->where('field', $field)
            ->where('status', ProfileSensitiveDataRequest::STATUS_PENDING)
            ->exists();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
