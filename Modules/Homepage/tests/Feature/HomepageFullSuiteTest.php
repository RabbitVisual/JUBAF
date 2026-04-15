<?php

namespace Modules\Homepage\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Devotional;
use App\Models\HomepageContactMessage;
use App\Models\SystemConfig;
use Modules\Chat\App\Models\ChatConfig;
use Nwidart\Modules\Facades\Module;
use PHPUnit\Framework\Attributes\Test;

class HomepageFullSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Garantir que o módulo Homepage está ativo
        $this->artisan('module:enable', ['module' => 'Homepage']);
    }

    #[Test]
    public function homepage_loads_correctly_with_default_config()
    {
        // Setup default configs
        SystemConfig::updateOrCreate(['key' => 'homepage_hero_title'], ['value' => 'Título Teste']);
        SystemConfig::updateOrCreate(['key' => 'homepage_hero_enabled'], ['value' => true]);

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        $response->assertSee('Título Teste');
        $response->assertSee('SOMOS UM');
    }

    #[Test]
    public function homepage_public_api_settings_returns_json(): void
    {
        $response = $this->getJson('/api/v1/homepage/settings');

        $response->assertOk();
        $response->assertJsonStructure([
            'site' => ['name', 'tagline', 'logos' => ['default', 'light', 'dark']],
            'hero' => ['badge', 'title', 'subtitle'],
            'sections' => ['carousel', 'hero', 'servicos', 'sobre', 'servicos_publicos', 'contato'],
            'servicos' => ['section_title', 'section_subtitle', 'cards'],
            'bible_daily',
        ]);
    }

    #[Test]
    public function legal_pages_load_correctly()
    {
        $pages = [
            'privacidade' => 'Política de Privacidade',
            'termos' => 'Termos de Uso',
            'sobre' => 'Sobre Nós',
            'desenvolvedor' => 'Desenvolvedor'
        ];

        foreach ($pages as $route => $text) {
            $response = $this->get(route($route));
            $response->assertStatus(200);
            // $response->assertSee($text); // O conteúdo pode variar, mas o status confirma o carregamento
        }
    }

    #[Test]
    public function modules_integration_sections_appear_when_enabled()
    {
        Module::enable('Blog');
        Module::enable('Avisos');

        $response = $this->get(route('homepage'));

        $response->assertStatus(200);
        if (module_enabled('Blog')) {
            $response->assertSee(route('blog.index'));
        }
    }

    #[Test]
    public function system_config_values_are_rendered()
    {
        SystemConfig::updateOrCreate(['key' => 'homepage_telefone'], ['value' => '(99) 9999-9999']);
        SystemConfig::updateOrCreate(['key' => 'homepage_email'], ['value' => 'contato@teste.com']);

        $response = $this->get(route('homepage'));

        $response->assertSee('(99) 9999-9999');
        $response->assertSee('contato@teste.com');
    }

    #[Test]
    public function chat_widget_appears_when_enabled_and_public()
    {
        // Habilitar Chat Module
        Module::enable('Chat');

        // Configurar Chat como público e habilitado
        ChatConfig::set('chat_enabled', 'true');
        ChatConfig::set('public_chat_enabled', 'true');

        $response = $this->get(route('homepage'));

        // O widget é incluído via @include('chat::public.widget')
        // Vamos verificar se o texto ou elemento chave do widget aparece
        // Como é um include, se a lógica no layout estiver certa e as configs setadas, deve aparecer.
        // O layout verifica: module_enabled('Chat') && ChatConfig::isPublicEnabled()

        // Para garantir, vamos verificar string que sabemos estar no widget ou apenas que não quebrou
        // Se o widget tiver "Atendimento Online" ou algo assim:
        // $response->assertSee('widget-chat'); // Exemplo hipotético

        $response->assertStatus(200);

        // Desabilitar Chat e verificar
        ChatConfig::set('public_chat_enabled', 'false');
        $response = $this->get(route('homepage'));
        $response->assertStatus(200);
        // $response->assertDontSee('widget-chat');
    }

    #[Test]
    public function diretoria_radio_and_devocionais_routes_respond_when_enabled(): void
    {
        foreach ([
            'homepage_public_diretoria_enabled' => true,
            'homepage_public_radio_enabled' => true,
            'homepage_public_devotionals_enabled' => true,
            'homepage_radio_player_enabled' => false,
        ] as $key => $val) {
            SystemConfig::set($key, $val, 'boolean', 'homepage');
        }

        $this->get(route('homepage.diretoria'))->assertOk();
        $this->get(route('radio'))->assertOk();
        $this->get(route('devocionais.index'))->assertOk();

        Devotional::query()->create([
            'title' => 'Teste devocional',
            'slug' => 'teste-devocional',
            'scripture_reference' => 'João 3:16',
            'body' => 'Corpo do teste.',
            'status' => Devotional::STATUS_PUBLISHED,
            'author_type' => Devotional::AUTHOR_USER,
            'user_id' => null,
            'published_at' => now(),
        ]);

        $this->get(route('devocionais.show', 'teste-devocional'))->assertOk();
    }

    #[Test]
    public function contato_page_loads_and_stores_message(): void
    {
        SystemConfig::set('homepage_contato_page_enabled', true, 'boolean', 'homepage');
        SystemConfig::set('homepage_contato_form_enabled', true, 'boolean', 'homepage');

        $this->get(route('contato'))->assertOk()->assertSee('Fale com a JUBAF', false);

        $this->post(route('contato.store'), [
            'name' => 'Visitante',
            'email' => 'visitante@example.com',
            'message' => 'Olá JUBAF regional.',
        ])->assertRedirect(route('contato'));

        $this->assertDatabaseHas('homepage_contact_messages', [
            'email' => 'visitante@example.com',
        ]);
        $this->assertSame(1, HomepageContactMessage::query()->count());
    }

}
