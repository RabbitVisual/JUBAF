<?php

namespace Modules\Homepage\App\Support;

use App\Models\SystemConfig;
use Modules\Bible\App\Services\BibleApiService;

final class HomepageBibleDaily
{
    /**
     * @return array{position: string, payload: array<string, mixed>, title: string, subtitle: string, show_reference: bool, show_version: bool, link_enabled: bool}|null
     */
    public static function resolveForPublic(): ?array
    {
        if (! module_enabled('Bible')) {
            return null;
        }

        if (! (bool) SystemConfig::get('homepage_bible_daily_enabled', false)) {
            return null;
        }

        $versionId = (int) SystemConfig::get('homepage_bible_daily_version_id', 0);
        if ($versionId <= 0) {
            $versionId = null;
        }

        $salt = trim((string) SystemConfig::get('homepage_bible_daily_salt', ''));
        if ($salt === '') {
            $salt = substr(hash('sha256', (string) config('app.key')), 0, 32);
        }

        $override = trim((string) SystemConfig::get('homepage_bible_daily_override_reference', ''));

        $payload = app(BibleApiService::class)->getHomepageDailyVersePayload(
            $versionId,
            $override,
            $salt
        );

        if ($payload === null) {
            return null;
        }

        return [
            'position' => (string) SystemConfig::get('homepage_bible_daily_position', 'before_servicos'),
            'payload' => $payload,
            'title' => (string) SystemConfig::get('homepage_bible_daily_title', 'Versículo do dia'),
            'subtitle' => trim((string) SystemConfig::get('homepage_bible_daily_subtitle', '')),
            'show_reference' => (bool) SystemConfig::get('homepage_bible_daily_show_reference', true),
            'show_version' => (bool) SystemConfig::get('homepage_bible_daily_show_version_label', true),
            'link_enabled' => (bool) SystemConfig::get('homepage_bible_daily_link_enabled', true),
        ];
    }
}
