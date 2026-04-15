<?php

use App\Helpers\FormatHelper;
use Illuminate\Support\Facades\Auth;

if (!function_exists('formatar_quantidade')) {
    /**
     * Formata uma quantidade baseado na unidade de medida
     *
     * Esta função é uma wrapper global para FormatHelper::formatarQuantidade()
     * para facilitar o uso em templates Blade.
     *
     * @param float|int|string $quantidade A quantidade a ser formatada
     * @param string|null $unidadeMedida A unidade de medida (ex: 'unidade', 'kg', 'metro', 'litro')
     * @return string A quantidade formatada
     *
     * @example
     * formatar_quantidade(1, 'unidade') // Retorna "1"
     * formatar_quantidade(1.5, 'metro') // Retorna "1,5"
     * formatar_quantidade(2.00, 'kg') // Retorna "2"
     */
    function formatar_quantidade($quantidade, ?string $unidadeMedida = null): string
    {
        return FormatHelper::formatarQuantidade($quantidade, $unidadeMedida);
    }
}

if (!function_exists('user_can_access_admin_panel')) {
    /**
     * Painel /admin — apenas Super Administrador (Spatie: super-admin).
     */
    function user_can_access_admin_panel(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user || ! method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->hasAnyRole(\App\Support\JubafRoleRegistry::superAdminRoleNames());
    }
}

if (!function_exists('bible_admin_route')) {
    /**
     * Rota nomeada da área de administração da Bíblia (admin.bible.* ou diretoria.bible.*).
     *
     * @param  mixed  $parameters  array, modelo ou valor único para o URL
     */
    function bible_admin_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        $prefix = request()->attributes->get('bible_admin_route_prefix', 'admin.bible');

        return route($prefix.'.'.$name, $parameters, $absolute);
    }
}

if (!function_exists('bible_route_is')) {
    /**
     * Aceita padrões routeIs para admin.bible ou diretoria.bible.
     */
    function bible_route_is(string ...$patterns): bool
    {
        foreach ($patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
            $alt = str_replace('admin.bible', 'diretoria.bible', $pattern);
            if ($alt !== $pattern && request()->routeIs($alt)) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('user_is_diretoria_executive')) {
    function user_is_diretoria_executive(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user || ! method_exists($user, 'hasAnyRole')) {
            return false;
        }

        return $user->hasAnyRole(\App\Support\JubafRoleRegistry::directorateExecutiveRoleNames());
    }
}

if (!function_exists('user_can_access_diretoria_panel')) {
    /**
     * Painel /diretoria — cargos de diretoria (Estatuto) + legado co-admin.
     */
    function user_can_access_diretoria_panel(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user || ! method_exists($user, 'hasAnyRole')) {
            return false;
        }

        $roles = array_merge(
            \App\Support\JubafRoleRegistry::directorateRoleNames(),
            array_filter([\App\Support\JubafRoleRegistry::legacyCoAdminName()])
        );

        return $user->hasAnyRole($roles);
    }
}

if (!function_exists('user_can_access_pastor_panel')) {
    function user_can_access_pastor_panel(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user || ! method_exists($user, 'hasRole')) {
            return false;
        }

        return $user->hasRole('pastor');
    }
}

if (!function_exists('user_can_publish_avisos')) {
    /**
     * Quem pode criar ou editar avisos institucionais (admin ou painel da diretoria).
     */
    function user_can_publish_avisos(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user || ! method_exists($user, 'hasAnyRole')) {
            return false;
        }

        if ($user->hasAnyRole(\App\Support\JubafRoleRegistry::superAdminRoleNames())) {
            return true;
        }

        return $user->hasAnyRole([
            'presidente',
            'vice-presidente-1',
            'vice-presidente-2',
            'secretario-1',
            'secretario-2',
        ]);
    }
}

if (! function_exists('user_can_manage_blog')) {
    /**
     * Quem pode criar ou editar posts e conteúdo do blog (alinhado às rotas admin/diretoria).
     */
    function user_can_manage_blog(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user) {
            return false;
        }

        if (user_can_publish_avisos($user)) {
            return true;
        }

        if (method_exists($user, 'can') && $user->can('homepage.edit')) {
            return true;
        }

        return false;
    }
}

if (!function_exists('user_is_aviso_official_author')) {
    /**
     * Indica se o autor deve exibir o selo de comunicado oficial JUBAF (origem direção/super-admin).
     */
    function user_is_aviso_official_author(?\Illuminate\Contracts\Auth\Authenticatable $author): bool
    {
        if (! $author) {
            return false;
        }

        return user_can_publish_avisos($author);
    }
}

if (!function_exists('jubaf_chat_agent_role_names')) {
    /**
     * Papéis que podem atuar como agente no Chat (painel admin ou diretoria).
     *
     * @return array<int, string>
     */
    function jubaf_chat_agent_role_names(): array
    {
        return array_values(array_unique(array_merge(
            \App\Support\JubafRoleRegistry::superAdminRoleNames(),
            \App\Support\JubafRoleRegistry::directorateRoleNames(),
            array_filter([\App\Support\JubafRoleRegistry::legacyCoAdminName()]),
            ['admin', 'co-admin'],
        )));
    }
}

if (!function_exists('jubaf_role_label')) {
    function jubaf_role_label(string $roleName): string
    {
        return \App\Support\JubafRoleRegistry::label($roleName);
    }
}

if (!function_exists('jubaf_role_tier')) {
    function jubaf_role_tier(string $roleName): string
    {
        return \App\Support\JubafRoleRegistry::tier($roleName);
    }
}

if (!function_exists('jubaf_role_is_protected')) {
    function jubaf_role_is_protected(string $roleName): bool
    {
        if (\App\Support\JubafRoleRegistry::isSystemRole($roleName)) {
            return true;
        }

        return in_array($roleName, ['admin', 'co-admin'], true);
    }
}

if (!function_exists('get_profile_route')) {
    /**
     * Nome da rota de perfil conforme o painel do usuário.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $subject  Usuário (default: sessão atual)
     */
    function get_profile_route(?\Illuminate\Contracts\Auth\Authenticatable $subject = null): string
    {
        $user = $subject ?? Auth::user();
        if (! $user) {
            return 'login';
        }

        if (user_can_access_admin_panel($user)) {
            return 'admin.profile';
        }

        if (user_can_access_diretoria_panel($user)) {
            return 'diretoria.profile';
        }

        if (user_can_access_pastor_panel($user)) {
            return 'pastor.dashboard';
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('lider')) {
            return 'lideres.profile.index';
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('jovens')) {
            return 'jovens.profile.index';
        }

        return 'profile';
    }
}

if (!function_exists('get_dashboard_route')) {
    /**
     * Nome da rota de dashboard conforme o papel do usuário.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $subject  Usuário (default: sessão atual)
     */
    function get_dashboard_route(?\Illuminate\Contracts\Auth\Authenticatable $subject = null): string
    {
        $user = $subject ?? Auth::user();
        if (! $user) {
            return 'login';
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('lider')) {
            return 'lideres.dashboard';
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('jovens')) {
            return 'jovens.dashboard';
        }

        if (user_can_access_admin_panel($user)) {
            return 'admin.dashboard';
        }

        if (user_can_access_diretoria_panel($user)) {
            return 'diretoria.dashboard';
        }

        if (user_can_access_pastor_panel($user)) {
            return 'pastor.dashboard';
        }

        return 'dashboard';
    }
}

if (!function_exists('homepage_panel_route')) {
    /**
     * URL para rotas do painel que existem em admin.* e diretoria.* (contacts, newsletter).
     */
    function homepage_panel_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $prefix = request()->routeIs('diretoria.*') ? 'diretoria' : 'admin';

        return route($prefix.'.homepage.'.$name, $parameters, $absolute);
    }
}

if (!function_exists('module_enabled')) {
    /**
     * True if the Nwidart module exists on disk and is enabled.
     * Unlike module_enabled(), this returns false when the module was removed.
     */
    function module_enabled(string $name): bool
    {
        $module = \Nwidart\Modules\Facades\Module::find($name);

        return $module !== null && $module->isEnabled();
    }
}

if (! function_exists('user_needs_secretaria_church_scope')) {
    /**
     * Leitores operacionais (líder/jovem/pastor) sem diretoria nem edição de atas —
     * atas e documentos publicados são filtrados por igreja(s) do utilizador.
     */
    function user_needs_secretaria_church_scope(?\Illuminate\Contracts\Auth\Authenticatable $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (! $user instanceof \App\Models\User) {
            return false;
        }

        if ($user->hasRole('super-admin')) {
            return false;
        }

        if ($user->hasAnyRole(\App\Support\JubafRoleRegistry::directorateRoleNames())) {
            return false;
        }

        if ($user->can('secretaria.minutes.edit')) {
            return false;
        }

        return $user->hasAnyRole(['lider', 'jovens', 'pastor']);
    }
}

if (! function_exists('user_photo_url')) {
    /**
     * URL pública da foto de perfil com versão (invalida cache após atualização).
     * Usa a coluna users.photo (sincronizada com a foto ativa) ou, em último caso, user_profile_photos.
     */
    function user_photo_url(?object $user): ?string
    {
        if ($user === null) {
            return null;
        }

        $path = null;
        if ($user instanceof \App\Models\User) {
            $path = $user->photo;
            if ($path === null || $path === '') {
                static $hasProfilePhotosTable = null;
                if ($hasProfilePhotosTable === null) {
                    $hasProfilePhotosTable = \Illuminate\Support\Facades\Schema::hasTable('user_profile_photos');
                }
                if ($hasProfilePhotosTable) {
                    $path = \Illuminate\Support\Facades\DB::table('user_profile_photos')
                        ->where('user_id', $user->id)
                        ->where('is_active', true)
                        ->value('path');
                    if (empty($path)) {
                        $path = \Illuminate\Support\Facades\DB::table('user_profile_photos')
                            ->where('user_id', $user->id)
                            ->orderBy('sort_order')
                            ->orderBy('id')
                            ->value('path');
                    }
                }
            }
        } else {
            $path = $user->photo ?? null;
        }

        if (empty($path)) {
            return null;
        }

        $path = ltrim((string) $path, '/');
        $v = 0;
        if (isset($user->updated_at) && $user->updated_at) {
            $v = $user->updated_at->getTimestamp();
        } elseif (isset($user->id)) {
            $v = (int) $user->id;
        }

        return asset('storage/'.$path).'?v='.$v;
    }
}

if (! function_exists('user_cover_url')) {
    /**
     * URL pública da imagem de capa do perfil (se existir).
     */
    function user_cover_url(?object $user): ?string
    {
        if ($user === null || empty($user->cover_photo)) {
            return null;
        }

        $path = ltrim((string) $user->cover_photo, '/');
        $v = 0;
        if (isset($user->updated_at) && $user->updated_at) {
            $v = $user->updated_at->getTimestamp();
        } elseif (isset($user->id)) {
            $v = (int) $user->id;
        }

        return asset('storage/'.$path).'?v='.$v;
    }
}

if (! function_exists('user_cover_object_position')) {
    /**
     * object-position CSS para a imagem de capa (0–100% horizontal e vertical).
     */
    function user_cover_object_position(?object $user): string
    {
        if ($user === null) {
            return '50% 50%';
        }

        $x = max(0, min(100, (int) ($user->cover_position_x ?? 50)));
        $y = max(0, min(100, (int) ($user->cover_position_y ?? 50)));

        return $x.'% '.$y.'%';
    }
}

if (! function_exists('profile_photo_asset')) {
    /**
     * URL pública de uma linha em user_profile_photos (miniaturas, cache bust).
     */
    function profile_photo_asset(?\App\Models\UserProfilePhoto $photo): ?string
    {
        if ($photo === null || empty($photo->path)) {
            return null;
        }

        $path = ltrim((string) $photo->path, '/');
        $v = $photo->updated_at ? $photo->updated_at->getTimestamp() : (int) $photo->id;

        return asset('storage/'.$path).'?v='.$v;
    }
}

if (! function_exists('format_cpf_pt')) {
    /**
     * Formata CPF para exibição (999.999.999-99).
     */
    function format_cpf_pt(?string $cpf): string
    {
        if ($cpf === null || $cpf === '') {
            return '—';
        }
        $d = preg_replace('/\D/', '', $cpf) ?? '';
        if (strlen($d) !== 11) {
            return $cpf;
        }

        return substr($d, 0, 3).'.'.substr($d, 3, 3).'.'.substr($d, 6, 3).'-'.substr($d, 9, 2);
    }
}
