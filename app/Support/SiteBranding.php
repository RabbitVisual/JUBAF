<?php

namespace App\Support;

use App\Models\SystemConfig;

class SiteBranding
{
    public const KEY_LOGO_DEFAULT = 'branding.logo_default';

    public const KEY_LOGO_LIGHT = 'branding.logo_light';

    public const KEY_LOGO_DARK = 'branding.logo_dark';

    public const KEY_SITE_TAGLINE = 'branding.site_tagline';

    public const FALLBACK_DEFAULT = 'images/logo/logo.png';

    public const FALLBACK_LIGHT = 'images/logo/logo-claro.png';

    public const FALLBACK_DARK = 'images/logo/logo-escuro.png';

    public static function siteName(): string
    {
        return (string) (SystemConfig::get('system.name') ?: config('app.name', 'JUBAF'));
    }

    public static function siteTagline(): string
    {
        $raw = (string) SystemConfig::get(self::KEY_SITE_TAGLINE,
            'Juventude Batista Feirense — SOMOS UM');

        return self::normalizeTagline($raw);
    }

    /**
     * Remove prefixo legado "Tema:" de valores guardados na BD antes da atualização de copy.
     */
    private static function normalizeTagline(string $tagline): string
    {
        $tagline = preg_replace('/\s*—\s*Tema:\s*/u', ' — ', $tagline) ?? $tagline;
        $tagline = str_ireplace('Tema: ', '', $tagline);

        return trim($tagline);
    }

    public static function logoDefaultUrl(): string
    {
        return self::resolveAssetUrl(
            SystemConfig::get(self::KEY_LOGO_DEFAULT, self::FALLBACK_DEFAULT)
        );
    }

    public static function logoLightUrl(): string
    {
        return self::resolveAssetUrl(
            SystemConfig::get(self::KEY_LOGO_LIGHT, self::FALLBACK_LIGHT)
        );
    }

    public static function logoDarkUrl(): string
    {
        return self::resolveAssetUrl(
            SystemConfig::get(self::KEY_LOGO_DARK, self::FALLBACK_DARK)
        );
    }

    /**
     * Resolve stored path to a full URL. Accepts public paths (images/...), storage-relative (branding/...), or absolute URLs.
     */
    public static function resolveAssetUrl(?string $path): string
    {
        if ($path === null || $path === '') {
            return '';
        }

        $path = trim($path);

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }

        if (str_starts_with($path, 'branding/')) {
            return asset('storage/'.$path);
        }

        return asset($path);
    }

    public static function defaultLogoPaths(): array
    {
        return [
            self::KEY_LOGO_DEFAULT => self::FALLBACK_DEFAULT,
            self::KEY_LOGO_LIGHT => self::FALLBACK_LIGHT,
            self::KEY_LOGO_DARK => self::FALLBACK_DARK,
        ];
    }
}
