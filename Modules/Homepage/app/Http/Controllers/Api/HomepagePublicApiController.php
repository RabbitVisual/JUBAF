<?php

namespace Modules\Homepage\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SystemConfig;
use App\Support\SiteBranding;
use Illuminate\Http\JsonResponse;
use Modules\Calendario\App\Models\CalendarEvent;
use Modules\Homepage\App\Support\HomepageBibleDaily;
use Modules\Homepage\App\Support\HomepageDefaults;

class HomepagePublicApiController extends Controller
{
    public function settings(): JsonResponse
    {
        $cards = SystemConfig::get('homepage_servicos_cards');
        if (! is_array($cards)) {
            $cards = HomepageDefaults::servicosCards();
        }

        return response()->json([
            'site' => [
                'name' => SiteBranding::siteName(),
                'tagline' => SiteBranding::siteTagline(),
                'logos' => [
                    'default' => SiteBranding::logoDefaultUrl(),
                    'light' => SiteBranding::logoLightUrl(),
                    'dark' => SiteBranding::logoDarkUrl(),
                ],
            ],
            'hero' => [
                'badge' => SystemConfig::get('homepage_hero_badge', 'JUBAF · Tema 2026: SOMOS UM'),
                'title' => SystemConfig::get('homepage_hero_title', 'Juventude Batista Feirense'),
                'subtitle' => SystemConfig::get('homepage_hero_subtitle', 'Somos um só corpo em Cristo. Caminhamos juntos na fé, na amizade e no serviço — tema deste ano: SOMOS UM.'),
            ],
            'sections' => [
                'carousel' => (bool) SystemConfig::get('carousel_enabled', true),
                'hero' => (bool) SystemConfig::get('homepage_hero_enabled', true),
                'servicos' => (bool) SystemConfig::get('homepage_servicos_enabled', true),
                'sobre' => (bool) SystemConfig::get('homepage_sobre_enabled', true),
                'servicos_publicos' => (bool) SystemConfig::get('homepage_servicos_publicos_enabled', true),
                'contato' => (bool) SystemConfig::get('homepage_contato_enabled', true),
            ],
            'servicos' => [
                'section_title' => SystemConfig::get('homepage_servicos_section_title', 'O que vivemos juntos'),
                'section_subtitle' => SystemConfig::get('homepage_servicos_section_subtitle', 'Encontros, discipulado, eventos e comunicação — a JUBAF em movimento.'),
                'cards' => $cards,
            ],
            'bible_daily' => HomepageBibleDaily::resolveForPublic(),
            'upcoming_events' => $this->upcomingFeaturedEvents(),
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function upcomingFeaturedEvents(): array
    {
        if (! function_exists('module_enabled') || ! module_enabled('Calendario')) {
            return [];
        }

        return CalendarEvent::query()
            ->published()
            ->where('visibility', CalendarEvent::VIS_PUBLIC)
            ->where('is_featured', true)
            ->where('starts_at', '>=', now()->startOfDay())
            ->orderBy('starts_at')
            ->limit(8)
            ->get()
            ->map(fn (CalendarEvent $e) => [
                'title' => $e->title,
                'slug' => $e->slug,
                'starts_at' => $e->starts_at?->toIso8601String(),
                'location' => $e->location,
            ])
            ->all();
    }
}
