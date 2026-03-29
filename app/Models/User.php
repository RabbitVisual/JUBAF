<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Bible\App\Traits\HasReadingProgress;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasReadingProgress, Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['photo_url'];

    /**
     * Boot function from Laravel
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            // Ensure name is always synced from first_name + last_name if they are present
            if (! empty($user->first_name) && ! empty($user->last_name)) {
                $user->name = trim($user->first_name.' '.$user->last_name);
            }
            // Ensure first_name and last_name are synced from name if they are empty
            elseif (! empty($user->name) && (empty($user->first_name) || empty($user->last_name))) {
                $parts = explode(' ', $user->name, 2);
                $user->first_name = $parts[0] ?? '';
                $user->last_name = $parts[1] ?? '';
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
        'cpf',
        'date_of_birth',
        'gender',
        'marital_status',
        'email',
        'phone',
        'cellphone',
        'email_verified_at',
        'address',
        'address_number',
        'address_complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'church_id',
        'password',
        'role_id',
        'is_active',
        'photo',
        'notes',
        'two_factor_secret',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
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
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Verifica se o usuário tem 2FA (TOTP) ativo e confirmado.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return ! empty($this->two_factor_secret) && $this->two_factor_confirmed_at !== null;
    }

    /**
     * Relacionamento com Role
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    /**
     * Slugs de permissões RBAC (tabela permissions) associadas ao papel do utilizador.
     */
    public function permissionSlugs(): \Illuminate\Support\Collection
    {
        $this->loadMissing('role.permissions');

        if (! $this->role) {
            return collect();
        }

        return $this->role->permissions->pluck('slug');
    }

    /**
     * Verifica permissão institucional por slug (admin técnico tem sempre acesso).
     */
    public function canAccess(string $permissionSlug): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return $this->permissionSlugs()->contains($permissionSlug);
    }

    /**
     * @param  list<string>  $slugs
     */
    public function canAccessAny(array $slugs): bool
    {
        foreach ($slugs as $slug) {
            if ($this->canAccess($slug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Igreja local (cadastro ASBAF / UNIJOVEM no módulo Church).
     */
    public function church()
    {
        return $this->belongsTo(\Modules\Church\Models\Church::class);
    }

    /**
     * Vínculos familiares (parentesco) cadastrados para este usuário.
     */
    public function relationships()
    {
        return $this->hasMany(UserRelationship::class, 'user_id');
    }

    public function bibleFavorites()
    {
        return $this->belongsToMany(
            \Modules\Bible\App\Models\Verse::class,
            'bible_favorites',
            'user_id',
            'verse_id'
        )->withPivot('color')->withTimestamps();
    }

    /**
     * Verifica se o usuário possui um dos papéis indicados (slug em `roles`).
     * Aceita string ou array. Alias: "tesoureiro" cobre tesoureiro_1 e tesoureiro_2 (JUBAF).
     */
    public function hasRole(string|array $roles): bool
    {
        if (! $this->relationLoaded('role')) {
            $this->load('role');
        }

        if (! $this->role) {
            return false;
        }

        $slug = $this->role->slug;
        $needles = is_array($roles) ? $roles : [$roles];

        foreach ($needles as $role) {
            if (! is_string($role) || $role === '') {
                continue;
            }
            if ($slug === $role) {
                return true;
            }
            if ($role === 'tesoureiro' && str_starts_with($slug, 'tesoureiro')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o usuário é admin
     */
    public function isAdmin()
    {
        return $this->role && $this->role->slug === 'admin';
    }

    /**
     * Verifica se o usuário é da Diretoria Juba (Cargos Estatutários - Art. 7)
     */
    public function isDirector()
    {
        $directorSlugs = [
            'presidente', 'vice_presidente_1', 'vice_presidente_2',
            'secretario_1', 'secretario_2', 'tesoureiro_1', 'tesoureiro_2',
        ];

        return $this->role && in_array($this->role->slug, $directorSlugs);
    }

    /**
     * Verifica se o usuário é Secretário Geral (Oficial Executivo - Art. 25)
     */
    public function isSecretaryGeneral()
    {
        return $this->role && $this->role->slug === 'secretario_geral';
    }

    /**
     * Verifica se o usuário é Líder de Jovens/UNIJOVEM (Art. 16c)
     */
    public function isYouthLeader()
    {
        return $this->role && $this->role->slug === 'lider_jovens';
    }

    /**
     * Verifica se o usuário é Conselheiro (Art. 16b)
     */
    public function isCouncilMember()
    {
        return $this->role && $this->role->slug === 'conselheiro';
    }

    /**
     * Verifica se o usuário tem acesso ao painel administrativo
     */
    public function hasAdminAccess()
    {
        return $this->isAdmin()
            || $this->isDirector()
            || $this->isSecretaryGeneral()
            || $this->isYouthLeader()
            || $this->isCouncilMember();
    }

    /**
     * Relacionamento com registros de eventos
     */
    public function registrations()
    {
        return $this->hasMany(\Modules\Events\App\Models\EventRegistration::class);
    }

    /**
     * Relacionamento com entradas financeiras
     */
    public function financialEntries()
    {
        return $this->hasMany(\Modules\Treasury\App\Models\FinancialEntry::class);
    }

    /**
     * Relacionamento com múltiplas fotos de perfil
     */
    public function profilePhotos()
    {
        return $this->hasMany(\App\Models\UserPhoto::class);
    }

    /**
     * Retorna a foto ativa ou nulo
     */
    public function getActivePhoto()
    {
        return $this->profilePhotos()->where('is_active', true)->first();
    }

    /**
     * Get the photo URL.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo) {
            return null;
        }

        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        return asset('storage/'.$this->photo);
    }

    /**
     * Retorna a URL do avatar do usuário
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->photo_url) {
            return $this->photo_url;
        }

        $activePhoto = $this->getActivePhoto();
        if ($activePhoto) {
            return asset('storage/'.$activePhoto->path);
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Alias para exibição da foto de perfil
     */
    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->avatar_url;
    }

    /**
     * Percentual de completude do cadastro (0–100) para painel do membro e admin.
     * Critérios alinhados ao perfil JUBAF: identificação, contacto, endereço, igreja local e foto.
     */
    public function getProfileCompletionPercentage(): int
    {
        $checks = [
            fn (): bool => filled($this->first_name) && filled($this->last_name),
            fn (): bool => filled($this->cpf),
            fn (): bool => $this->date_of_birth !== null,
            fn (): bool => filled($this->gender),
            fn (): bool => filled($this->marital_status),
            fn (): bool => filled($this->cellphone) || filled($this->phone),
            fn (): bool => filled($this->city) && filled($this->state),
            fn (): bool => filled($this->address) && filled($this->zip_code),
            fn (): bool => $this->church_id !== null,
            fn (): bool => filled($this->photo),
        ];

        $total = count($checks);
        if ($total === 0) {
            return 0;
        }

        $filled = collect($checks)->filter(fn (callable $c): bool => $c())->count();

        return (int) round(min(100, ($filled / $total) * 100));
    }
}
