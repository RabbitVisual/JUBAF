<?php

namespace Modules\Homepage\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use App\Models\SystemConfig;
use App\Services\Admin\SystemConfigService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Bible\App\Services\BibleApiService;
use Modules\Homepage\App\Support\HomepageDefaults;

class HomepageAdminController extends Controller
{
    protected function homepageIndexRoute(): string
    {
        return request()->routeIs('diretoria.*') ? 'diretoria.homepage.index' : 'admin.homepage.index';
    }

    public function index()
    {
        app(SystemConfigService::class)->ensureHomepageBibleAndFooterConfigs();

        $carouselEnabled = (bool) SystemConfig::get('carousel_enabled', true);
        $carouselSlides = CarouselSlide::ordered()->get();

        $configs = [
            'hero_title' => SystemConfig::get('homepage_hero_title', 'Juventude Batista Feirense'),
            'hero_subtitle' => SystemConfig::get('homepage_hero_subtitle', 'Somos um só corpo em Cristo. Caminhamos juntos na fé, na amizade e no serviço — tema deste ano: SOMOS UM.'),
            'hero_badge' => SystemConfig::get('homepage_hero_badge', 'JUBAF · Tema 2026: SOMOS UM'),
            'hero_enabled' => (bool) SystemConfig::get('homepage_hero_enabled', true),
            'servicos_enabled' => (bool) SystemConfig::get('homepage_servicos_enabled', true),
            'sobre_enabled' => (bool) SystemConfig::get('homepage_sobre_enabled', true),
            'servicos_publicos_enabled' => (bool) SystemConfig::get('homepage_servicos_publicos_enabled', true),
            'contato_enabled' => (bool) SystemConfig::get('homepage_contato_enabled', true),
            'telefone' => SystemConfig::get('homepage_telefone', ''),
            'email' => SystemConfig::get('homepage_email', ''),
            'endereco' => SystemConfig::get('homepage_endereco', ''),
            'servicos_section_title' => SystemConfig::get('homepage_servicos_section_title', 'O que vivemos juntos'),
            'servicos_section_subtitle' => SystemConfig::get('homepage_servicos_section_subtitle', 'Encontros, discipulado, eventos e comunicação — a JUBAF em movimento.'),
            'sobre_badge' => SystemConfig::get('homepage_sobre_badge', 'Sobre nós'),
            'sobre_title' => SystemConfig::get('homepage_sobre_title', 'Juventude Batista Feirense'),
            'sobre_mission' => SystemConfig::get('homepage_sobre_mission', 'Proclamar Jesus, formar discípulos e servir nossa cidade com amor e verdade.'),
            'sobre_vision' => SystemConfig::get('homepage_sobre_vision', 'Ser referência de juventude batista engajada no Reino de Deus.'),
            'sobre_values' => SystemConfig::get('homepage_sobre_values', 'Fé bíblica, comunhão, missão e transparência.'),
            'sobre_p1' => SystemConfig::get('homepage_sobre_p1', 'A JUBAF reúne jovens das igrejas batistas em Feira de Santana e região para celebrar, aprender e enviar — em unidade com a ASBAF e com o corpo de Cristo.'),
            'sobre_p2' => SystemConfig::get('homepage_sobre_p2', 'Crescemos em grupos, eventos e projetos que fortalecem a identidade batista e o chamado missionário de cada jovem.'),
            'sobre_stat1_value' => SystemConfig::get('homepage_sobre_stat1_value', '70+'),
            'sobre_stat1_label' => SystemConfig::get('homepage_sobre_stat1_label', 'Igrejas na ASBAF'),
            'sobre_stat2_value' => SystemConfig::get('homepage_sobre_stat2_value', '1'),
            'sobre_stat2_label' => SystemConfig::get('homepage_sobre_stat2_label', 'Só corpo em Cristo'),
            'navbar_inicio_enabled' => (bool) SystemConfig::get('homepage_navbar_inicio_enabled', true),
            'navbar_servicos_enabled' => (bool) SystemConfig::get('homepage_navbar_servicos_enabled', true),
            'navbar_sobre_enabled' => (bool) SystemConfig::get('homepage_navbar_sobre_enabled', true),
            'navbar_consulta_enabled' => (bool) SystemConfig::get('homepage_navbar_consulta_enabled', true),
            'navbar_contato_enabled' => (bool) SystemConfig::get('homepage_navbar_contato_enabled', true),
            'navbar_diretoria_enabled' => (bool) SystemConfig::get('homepage_navbar_diretoria_enabled', true),
            'public_diretoria_enabled' => (bool) SystemConfig::get('homepage_public_diretoria_enabled', true),
            'diretoria_intro' => SystemConfig::get('homepage_diretoria_intro', ''),
            'public_radio_enabled' => (bool) SystemConfig::get('homepage_public_radio_enabled', true),
            'navbar_radio_enabled' => (bool) SystemConfig::get('homepage_navbar_radio_enabled', true),
            'radio_player_enabled' => (bool) SystemConfig::get('homepage_radio_player_enabled', true),
            'radio_embed_url' => SystemConfig::get('homepage_radio_embed_url', ''),
            'radio_official_url' => SystemConfig::get('homepage_radio_official_url', ''),
            'radio_page_title' => SystemConfig::get('homepage_radio_page_title', 'Rádio Rede 3.16'),
            'radio_page_lead' => SystemConfig::get('homepage_radio_page_lead', '24 horas compartilhando o amor de Deus'),
            'public_devotionals_enabled' => (bool) SystemConfig::get('homepage_public_devotionals_enabled', true),
            'navbar_devotionals_enabled' => (bool) SystemConfig::get('homepage_navbar_devotionals_enabled', true),
            'devotionals_page_title' => SystemConfig::get('homepage_devotionals_page_title', 'Devocionais'),
            'devotionals_page_lead' => SystemConfig::get('homepage_devotionals_page_lead', ''),
            'bible_daily_enabled' => (bool) SystemConfig::get('homepage_bible_daily_enabled', false),
            'bible_daily_version_id' => (int) SystemConfig::get('homepage_bible_daily_version_id', 0),
            'bible_daily_title' => SystemConfig::get('homepage_bible_daily_title', 'Versículo do dia'),
            'bible_daily_subtitle' => SystemConfig::get('homepage_bible_daily_subtitle', ''),
            'bible_daily_position' => SystemConfig::get('homepage_bible_daily_position', 'before_servicos'),
            'bible_daily_show_reference' => (bool) SystemConfig::get('homepage_bible_daily_show_reference', true),
            'bible_daily_show_version_label' => (bool) SystemConfig::get('homepage_bible_daily_show_version_label', true),
            'bible_daily_link_enabled' => (bool) SystemConfig::get('homepage_bible_daily_link_enabled', true),
            'bible_daily_override_reference' => SystemConfig::get('homepage_bible_daily_override_reference', ''),
            'bible_daily_salt' => SystemConfig::get('homepage_bible_daily_salt', ''),
            'bible_navbar_enabled' => (bool) SystemConfig::get('homepage_bible_navbar_enabled', false),
            'bible_navbar_label' => SystemConfig::get('homepage_bible_navbar_label', 'Bíblia'),
            'footer_descricao' => SystemConfig::get('homepage_footer_descricao', 'Juventude Batista Feirense — caminhando juntos no tema SOMOS UM.'),
            'footer_facebook_url' => SystemConfig::get('homepage_footer_facebook_url', ''),
            'footer_instagram_url' => SystemConfig::get('homepage_footer_instagram_url', ''),
            'footer_whatsapp' => SystemConfig::get('homepage_footer_whatsapp', ''),
            'footer_site_prefeitura' => SystemConfig::get('homepage_footer_site_prefeitura', ''),
            'footer_external_link_label' => SystemConfig::get('homepage_footer_external_link_label', 'Site institucional'),
            'footer_org_line' => SystemConfig::get('homepage_footer_org_line', 'JUBAF — Juventude Batista Feirense'),
            'footer_credit_visible' => (bool) SystemConfig::get('homepage_footer_credit_visible', false),
            'footer_credit_organization' => SystemConfig::get('homepage_footer_credit_organization', SystemConfig::get('homepage_footer_vertex_company', '')),
            'footer_credit_contact_name' => SystemConfig::get('homepage_footer_credit_contact_name', SystemConfig::get('homepage_footer_vertex_ceo', '')),
            'footer_credit_email' => SystemConfig::get('homepage_footer_credit_email', SystemConfig::get('homepage_footer_vertex_email', '')),
            'footer_credit_phone' => SystemConfig::get('homepage_footer_credit_phone', SystemConfig::get('homepage_footer_vertex_phone', '')),
            'contato_page_enabled' => (bool) SystemConfig::get('homepage_contato_page_enabled', true),
            'contato_form_enabled' => (bool) SystemConfig::get('homepage_contato_form_enabled', true),
            'contato_page_title' => SystemConfig::get('homepage_contato_page_title', 'Fale com a JUBAF'),
            'contato_page_lead' => SystemConfig::get('homepage_contato_page_lead', 'Feira de Santana e região — envie a sua mensagem à diretoria regional. Resposta por e-mail ou telefone conforme disponibilidade.'),
            'contato_home_cta' => SystemConfig::get('homepage_contato_home_cta', 'Formulário completo, dados institucionais e newsletter — abra a página de contato.'),
            'newsletter_public_enabled' => (bool) SystemConfig::get('homepage_newsletter_public_enabled', true),
            'newsletter_box_title' => SystemConfig::get('homepage_newsletter_box_title', 'Newsletter JUBAF'),
            'newsletter_box_lead' => SystemConfig::get('homepage_newsletter_box_lead', 'Receba novidades da juventude batista feirense na sua caixa de entrada.'),
        ];

        $servicosCardsRaw = SystemConfig::where('key', 'homepage_servicos_cards')->first();
        $servicosCardsJson = HomepageDefaults::servicosCardsJson();
        if ($servicosCardsRaw !== null) {
            $val = $servicosCardsRaw->value;
            if ($servicosCardsRaw->type === 'json') {
                $decoded = json_decode($val, true);
                $servicosCardsJson = is_array($decoded)
                    ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                    : $val;
            } else {
                $servicosCardsJson = is_string($val) && $val !== '' ? $val : $servicosCardsJson;
            }
        }

        $bibleVersions = module_enabled('Bible')
            ? app(BibleApiService::class)->getVersions()
            : collect();

        $view = request()->routeIs('diretoria.*') ? 'homepage::paineldiretoria.homepage.index' : 'homepage::admin.homepage.index';

        return view($view, compact('carouselEnabled', 'carouselSlides', 'configs', 'servicosCardsJson', 'bibleVersions'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:800',
            'hero_badge' => 'nullable|string|max:255',
            'hero_enabled' => 'nullable|boolean',
            'servicos_enabled' => 'nullable|boolean',
            'sobre_enabled' => 'nullable|boolean',
            'servicos_publicos_enabled' => 'nullable|boolean',
            'contato_enabled' => 'nullable|boolean',
            'telefone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'endereco' => 'nullable|string|max:255',
            'servicos_section_title' => 'nullable|string|max:255',
            'servicos_section_subtitle' => 'nullable|string|max:500',
            'sobre_badge' => 'nullable|string|max:120',
            'sobre_title' => 'nullable|string|max:255',
            'sobre_mission' => 'nullable|string|max:500',
            'sobre_vision' => 'nullable|string|max:500',
            'sobre_values' => 'nullable|string|max:500',
            'sobre_p1' => 'nullable|string|max:1000',
            'sobre_p2' => 'nullable|string|max:1000',
            'sobre_stat1_value' => 'nullable|string|max:40',
            'sobre_stat1_label' => 'nullable|string|max:120',
            'sobre_stat2_value' => 'nullable|string|max:40',
            'sobre_stat2_label' => 'nullable|string|max:120',
            'servicos_cards_json' => 'nullable|string|max:65535',
            'navbar_inicio_enabled' => 'nullable|boolean',
            'navbar_servicos_enabled' => 'nullable|boolean',
            'navbar_sobre_enabled' => 'nullable|boolean',
            'navbar_consulta_enabled' => 'nullable|boolean',
            'navbar_contato_enabled' => 'nullable|boolean',
            'navbar_diretoria_enabled' => 'nullable|boolean',
            'public_diretoria_enabled' => 'nullable|boolean',
            'diretoria_intro' => 'nullable|string|max:2000',
            'public_radio_enabled' => 'nullable|boolean',
            'navbar_radio_enabled' => 'nullable|boolean',
            'radio_player_enabled' => 'nullable|boolean',
            'radio_embed_url' => 'nullable|string|max:2048',
            'radio_official_url' => 'nullable|string|max:512',
            'radio_page_title' => 'nullable|string|max:255',
            'radio_page_lead' => 'nullable|string|max:500',
            'public_devotionals_enabled' => 'nullable|boolean',
            'navbar_devotionals_enabled' => 'nullable|boolean',
            'devotionals_page_title' => 'nullable|string|max:255',
            'devotionals_page_lead' => 'nullable|string|max:800',
            'bible_daily_enabled' => 'nullable|boolean',
            'bible_daily_version_id' => 'nullable|integer|min:0',
            'bible_daily_title' => 'nullable|string|max:255',
            'bible_daily_subtitle' => 'nullable|string|max:500',
            'bible_daily_position' => 'nullable|string|in:after_hero,before_servicos,before_contato',
            'bible_daily_show_reference' => 'nullable|boolean',
            'bible_daily_show_version_label' => 'nullable|boolean',
            'bible_daily_link_enabled' => 'nullable|boolean',
            'bible_daily_override_reference' => 'nullable|string|max:120',
            'bible_daily_salt' => 'nullable|string|max:120',
            'bible_navbar_enabled' => 'nullable|boolean',
            'bible_navbar_label' => 'nullable|string|max:80',
            'footer_descricao' => 'nullable|string|max:500',
            'footer_facebook_url' => 'nullable|string|max:512',
            'footer_instagram_url' => 'nullable|string|max:512',
            'footer_whatsapp' => 'nullable|string|max:20',
            'footer_site_prefeitura' => 'nullable|string|max:512',
            'footer_external_link_label' => 'nullable|string|max:120',
            'footer_org_line' => 'nullable|string|max:255',
            'footer_credit_visible' => 'nullable|boolean',
            'footer_credit_organization' => 'nullable|string|max:255',
            'footer_credit_contact_name' => 'nullable|string|max:255',
            'footer_credit_email' => 'nullable|email|max:255',
            'footer_credit_phone' => 'nullable|string|max:20',
            'contato_page_title' => 'nullable|string|max:255',
            'contato_page_lead' => 'nullable|string|max:800',
            'contato_home_cta' => 'nullable|string|max:500',
            'newsletter_box_title' => 'nullable|string|max:255',
            'newsletter_box_lead' => 'nullable|string|max:500',
        ]);

        $booleanFields = [
            'hero_enabled', 'servicos_enabled', 'sobre_enabled', 'servicos_publicos_enabled', 'contato_enabled',
            'navbar_inicio_enabled', 'navbar_servicos_enabled', 'navbar_sobre_enabled', 'navbar_consulta_enabled', 'navbar_contato_enabled',
            'navbar_diretoria_enabled', 'public_diretoria_enabled',
            'public_radio_enabled', 'navbar_radio_enabled', 'radio_player_enabled',
            'public_devotionals_enabled', 'navbar_devotionals_enabled',
            'bible_daily_enabled', 'bible_daily_show_reference', 'bible_daily_show_version_label', 'bible_daily_link_enabled',
            'bible_navbar_enabled', 'footer_credit_visible',
            'contato_page_enabled', 'contato_form_enabled', 'newsletter_public_enabled',
        ];

        if ($request->filled('servicos_cards_json')) {
            $decoded = json_decode($request->input('servicos_cards_json'), true);
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['servicos_cards_json' => 'JSON inválido. Use o formato de array de cartões.']);
            }

            SystemConfig::set(
                'homepage_servicos_cards',
                $decoded,
                'json',
                'homepage',
                'Cartões da secção de atividades na homepage'
            );
        }

        foreach ($validated as $key => $value) {
            if ($key === 'servicos_cards_json') {
                continue;
            }

            if (in_array($key, $booleanFields, true)) {
                $value = $request->has($key) ? (bool) $request->input($key) : false;
            }

            $type = in_array($key, $booleanFields, true) ? 'boolean' : 'string';
            if ($key === 'bible_daily_version_id') {
                $type = 'integer';
                $raw = $request->input('bible_daily_version_id');
                $value = ($raw === null || $raw === '') ? 0 : max(0, (int) $raw);
            }

            SystemConfig::set(
                'homepage_'.$key,
                $value,
                $type,
                'homepage',
                'Configuração da homepage'
            );
        }

        return redirect()->route($this->homepageIndexRoute())
            ->with('success', 'Configurações da homepage atualizadas com sucesso!');
    }

    public function toggleSection(Request $request)
    {
        $request->validate([
            'section' => 'required|string|in:hero,servicos,sobre,servicos_publicos,contato,carousel,navbar_inicio,navbar_servicos,navbar_sobre,navbar_consulta,navbar_contato,navbar_diretoria,public_diretoria,navbar_radio,navbar_devotionals,public_radio,public_devotionals',
            'enabled' => 'required|boolean',
        ]);

        $section = $request->input('section');
        $enabled = filter_var($request->input('enabled'), FILTER_VALIDATE_BOOLEAN);

        if ($section === 'carousel') {
            $configKey = 'carousel_enabled';
        } elseif (str_starts_with($section, 'navbar_')) {
            $configKey = 'homepage_'.$section.'_enabled';
        } else {
            $configKey = 'homepage_'.$section.'_enabled';
        }

        try {
            SystemConfig::set(
                $configKey,
                $enabled,
                'boolean',
                $section === 'carousel' ? 'carousel' : 'homepage',
                'Habilita ou desabilita a seção '.$section.' na homepage'
            );

            return response()->json([
                'success' => true,
                'enabled' => $enabled,
                'message' => $enabled ? 'Seção ativada com sucesso!' : 'Seção desativada com sucesso!',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos: '.implode(', ', array_map(fn ($errors) => implode(', ', $errors), $e->errors())),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar seção homepage', [
                'section' => $section,
                'enabled' => $enabled,
                'configKey' => $configKey,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar seção. Tente novamente.',
            ], 500);
        }
    }
}
