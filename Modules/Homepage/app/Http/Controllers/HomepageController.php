<?php

namespace Modules\Homepage\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\HomepageContactSubmittedMail;
use App\Models\BoardMember;
use App\Models\CarouselSlide;
use App\Models\Devotional;
use App\Models\HomepageContactMessage;
use App\Models\HomepageNewsletterSubscriber;
use App\Models\SystemConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Bible\App\Services\BibleApiService;
use Modules\Homepage\App\Support\HomepageDefaults;
use Modules\Homepage\App\Support\HomepageBibleDaily;

class HomepageController extends Controller
{
    /**
     * Exibe a página inicial
     */
    public function index()
    {
        $carouselEnabled = SystemConfig::get('carousel_enabled', true);
        $carouselSlides = $carouselEnabled
            ? CarouselSlide::active()->ordered()->get()
            : collect();

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
            'footer_descricao' => SystemConfig::get('homepage_footer_descricao', 'Juventude Batista Feirense — caminhando juntos no tema SOMOS UM.'),
            'footer_facebook_url' => SystemConfig::get('homepage_footer_facebook_url', ''),
            'footer_instagram_url' => SystemConfig::get('homepage_footer_instagram_url', ''),
            'footer_whatsapp' => SystemConfig::get('homepage_footer_whatsapp', ''),
            'footer_site_prefeitura' => SystemConfig::get('homepage_footer_site_prefeitura', ''),
            'footer_external_link_label' => SystemConfig::get('homepage_footer_external_link_label', 'Site institucional'),
            'footer_org_line' => SystemConfig::get('homepage_footer_org_line', 'JUBAF — Juventude Batista Feirense'),
            'contato_page_title' => SystemConfig::get('homepage_contato_page_title', 'Fale com a JUBAF'),
            'contato_page_lead' => SystemConfig::get('homepage_contato_page_lead', 'Feira de Santana e região — envie a sua mensagem à diretoria regional. Resposta por e-mail ou telefone conforme disponibilidade.'),
            'contato_home_cta' => SystemConfig::get('homepage_contato_home_cta', 'Formulário completo, dados institucionais e newsletter — abra a página de contato.'),
            'newsletter_public_enabled' => (bool) SystemConfig::get('homepage_newsletter_public_enabled', true),
            'newsletter_box_title' => SystemConfig::get('homepage_newsletter_box_title', 'Newsletter JUBAF'),
            'newsletter_box_lead' => SystemConfig::get('homepage_newsletter_box_lead', 'Receba novidades da juventude batista feirense na sua caixa de entrada.'),
        ];

        $servicosCards = $this->resolveServicosCards();

        $bibleDailyBlock = HomepageBibleDaily::resolveForPublic();

        return view('homepage::index', compact('carouselSlides', 'carouselEnabled', 'configs', 'servicosCards', 'bibleDailyBlock'));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function resolveServicosCards(): array
    {
        $raw = SystemConfig::get('homepage_servicos_cards');
        if ($raw === null || $raw === '') {
            return HomepageDefaults::servicosCards();
        }

        $arr = is_array($raw) ? $raw : json_decode((string) $raw, true);
        if (! is_array($arr) || $arr === []) {
            return HomepageDefaults::servicosCards();
        }

        return $arr;
    }

    public function privacidade()
    {
        return view('homepage::privacidade');
    }

    public function termos()
    {
        return view('homepage::termos');
    }

    public function sobre()
    {
        return view('homepage::sobre');
    }

    public function desenvolvedor()
    {
        return view('homepage::desenvolvedor');
    }

    public function diretoria()
    {
        if (! (bool) SystemConfig::get('homepage_public_diretoria_enabled', true)) {
            abort(404);
        }

        $members = BoardMember::query()->activeOrdered()->get();
        $pageIntro = (string) SystemConfig::get('homepage_diretoria_intro', '');

        return view('homepage::public.diretoria', compact('members', 'pageIntro'));
    }

    public function radio()
    {
        if (! (bool) SystemConfig::get('homepage_public_radio_enabled', true)) {
            abort(404);
        }

        $showRadio = (bool) SystemConfig::get('homepage_radio_player_enabled', true);
        $embedUrl = trim((string) SystemConfig::get('homepage_radio_embed_url', ''));
        $embedUrlForPage = $embedUrl;
        $radioTitle = SystemConfig::get('homepage_radio_page_title', 'Rádio Rede 3.16');
        $radioLead = SystemConfig::get('homepage_radio_page_lead', '24 horas compartilhando o amor de Deus');
        $officialUrl = trim((string) SystemConfig::get('homepage_radio_official_url', ''));

        $randomVerse = null;
        if (module_enabled('Bible')) {
            try {
                $verse = app(BibleApiService::class)->getRandomVerse(null);
                if ($verse) {
                    $randomVerse = [
                        'text' => $verse->text,
                        'reference' => $verse->full_reference,
                    ];
                }
            } catch (\Throwable) {
                $randomVerse = null;
            }
        }

        return view('homepage::public.radio', compact(
            'showRadio',
            'embedUrl',
            'embedUrlForPage',
            'randomVerse',
            'radioTitle',
            'radioLead',
            'officialUrl'
        ));
    }

    public function devotionalsIndex()
    {
        if (! (bool) SystemConfig::get('homepage_public_devotionals_enabled', true)) {
            abort(404);
        }

        $s = [
            'homepage_devotionals_page_title' => SystemConfig::get('homepage_devotionals_page_title', 'Devocionais'),
            'homepage_devotionals_page_lead' => SystemConfig::get('homepage_devotionals_page_lead', ''),
        ];
        $rows = Devotional::query()->publishedOrdered()->paginate(12);
        $metaTitle = $s['homepage_devotionals_page_title'].' — '.\App\Support\SiteBranding::siteName();

        return view('homepage::public.devotionals-index', compact('rows', 's', 'metaTitle'));
    }

    public function devotionalShow(Devotional $devotional)
    {
        if (! (bool) SystemConfig::get('homepage_public_devotionals_enabled', true)) {
            abort(404);
        }

        if ($devotional->status !== Devotional::STATUS_PUBLISHED || $devotional->published_at === null) {
            abort(404);
        }

        $s = [
            'homepage_devotionals_page_title' => SystemConfig::get('homepage_devotionals_page_title', 'Devocionais'),
            'homepage_devotionals_page_lead' => SystemConfig::get('homepage_devotionals_page_lead', ''),
        ];
        $metaTitle = $devotional->title.' — '.$s['homepage_devotionals_page_title'];

        return view('homepage::public.devotional-show', compact('devotional', 's', 'metaTitle'));
    }

    public function contato()
    {
        if (! (bool) SystemConfig::get('homepage_contato_page_enabled', true)) {
            abort(404);
        }

        $configs = [
            'telefone' => SystemConfig::get('homepage_telefone', ''),
            'email' => SystemConfig::get('homepage_email', ''),
            'endereco' => SystemConfig::get('homepage_endereco', ''),
            'contato_page_title' => SystemConfig::get('homepage_contato_page_title', 'Fale com a JUBAF'),
            'contato_page_lead' => SystemConfig::get('homepage_contato_page_lead', 'Feira de Santana e região — envie a sua mensagem à diretoria regional. Resposta por e-mail ou telefone conforme disponibilidade.'),
            'contato_form_enabled' => (bool) SystemConfig::get('homepage_contato_form_enabled', true),
            'newsletter_public_enabled' => (bool) SystemConfig::get('homepage_newsletter_public_enabled', true),
            'newsletter_box_title' => SystemConfig::get('homepage_newsletter_box_title', 'Newsletter JUBAF'),
            'newsletter_box_lead' => SystemConfig::get('homepage_newsletter_box_lead', 'Receba novidades da juventude batista feirense na sua caixa de entrada.'),
        ];

        return view('homepage::contato', compact('configs'));
    }

    public function contatoStore(Request $request)
    {
        if (! (bool) SystemConfig::get('homepage_contato_page_enabled', true)
            || ! (bool) SystemConfig::get('homepage_contato_form_enabled', true)) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:40',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $msg = HomepageContactMessage::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 2000),
        ]);

        $notifyTo = trim((string) SystemConfig::get('homepage_email', ''));
        if ($notifyTo !== '' && filter_var($notifyTo, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($notifyTo)->send(new HomepageContactSubmittedMail($msg));
            } catch (\Throwable $e) {
                Log::warning('homepage.contact.notify_failed', ['error' => $e->getMessage()]);
            }
        }

        return redirect()
            ->route('contato')
            ->with('success', 'Mensagem enviada. A diretoria regional da JUBAF irá responder quando possível.');
    }

    public function newsletterSubscribe(Request $request)
    {
        if (! (bool) SystemConfig::get('homepage_newsletter_public_enabled', true)) {
            abort(404);
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:120',
        ]);

        $email = mb_strtolower(trim($validated['email']));

        $existing = HomepageNewsletterSubscriber::withTrashed()->where('email', $email)->first();
        if ($existing !== null) {
            if ($existing->trashed()) {
                $existing->restore();
            }
            if ($existing->is_active && $existing->is_confirmed) {
                return redirect()
                    ->route('contato')
                    ->with('newsletter_status', 'Este e-mail já está inscrito na newsletter.');
            }
            $existing->update([
                'name' => $validated['name'] ?? $existing->name,
                'is_active' => true,
                'is_confirmed' => true,
                'subscribed_at' => now(),
            ]);

            return redirect()
                ->route('contato')
                ->with('newsletter_status', 'Inscrição atualizada. Obrigado por acompanhar a JUBAF!');
        }

        HomepageNewsletterSubscriber::query()->create([
            'email' => $email,
            'name' => $validated['name'] ?? null,
            'is_active' => true,
            'is_confirmed' => true,
            'subscribed_at' => now(),
        ]);

        return redirect()
            ->route('contato')
            ->with('newsletter_status', 'Inscrição confirmada. Obrigado por acompanhar a JUBAF!');
    }
}
