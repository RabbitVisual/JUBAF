<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use App\Models\SystemConfig;
use App\Support\JubafRoleRegistry;
use Illuminate\Support\Facades\Artisan;

class SystemConfigService
{
    /**
     * Get all configs grouped by group
     */
    public function getConfigsGrouped(): array
    {
        $configs = SystemConfig::all();
        $grouped = [];

        foreach ($configs as $config) {
            if (! isset($grouped[$config->group])) {
                $grouped[$config->group] = [];
            }

            $grouped[$config->group][] = $config;
        }

        ksort($grouped);

        foreach ($grouped as &$items) {
            usort($items, fn (SystemConfig $a, SystemConfig $b) => strcmp((string) $a->key, (string) $b->key));
        }
        unset($items);

        return $grouped;
    }

    /**
     * Get configs by group
     */
    public function getConfigsByGroup(string $group)
    {
        return SystemConfig::where('group', $group)->get();
    }

    /**
     * Update config value
     */
    public function updateConfig(string $key, $value, ?string $type = null, ?string $description = null): SystemConfig
    {
        $config = SystemConfig::firstOrNew(['key' => $key]);
        $oldValue = $config->value;

        if ($type) {
            $config->type = $type;
        }

        if ($description) {
            $config->description = $description;
        }

        $config->value = match ($config->type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            'integer' => (string) $value,
            default => (string) $value,
        };

        $config->save();

        AuditLog::log(
            'config.update',
            SystemConfig::class,
            $config->id,
            'admin',
            "Configuração {$key} atualizada",
            ['value' => $oldValue],
            ['value' => $config->value]
        );

        return $config;
    }

    /**
     * Bulk update configs
     */
    public function bulkUpdateConfigs(array $configs): void
    {
        foreach ($configs as $key => $value) {
            $this->updateConfig($key, $value);
        }
    }

    /**
     * Get default configs
     */
    public function getDefaultConfigs(): array
    {
        return [
            'system.name' => [
                'value' => 'JUBAF',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nome do sistema',
            ],
            'system.version' => [
                'value' => '1.0.0',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Versão do sistema',
            ],
            'branding.logo_default' => [
                'value' => 'images/logo/logo.png',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Logo padrão (arquivo em public ou upload em storage)',
            ],
            'branding.logo_light' => [
                'value' => 'images/logo/logo-claro.png',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Logo para fundo escuro (ex.: branco)',
            ],
            'branding.logo_dark' => [
                'value' => 'images/logo/logo-escuro.png',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Logo para fundo claro (ex.: preto)',
            ],
            'branding.site_tagline' => [
                'value' => 'Juventude Batista Feirense — SOMOS UM',
                'type' => 'string',
                'group' => 'branding',
                'description' => 'Slogan / descrição curta (meta e textos auxiliares)',
            ],
            'email.from_address' => [
                'value' => 'noreply@jubaf.local',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Endereço de email remetente',
            ],
            'email.from_name' => [
                'value' => 'JUBAF',
                'type' => 'string',
                'group' => 'email',
                'description' => 'Nome do remetente',
            ],
            'backup.enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'backup',
                'description' => 'Habilitar backups automáticos',
            ],
            'backup.frequency' => [
                'value' => 'daily',
                'type' => 'string',
                'group' => 'backup',
                'description' => 'Frequência dos backups (daily, weekly, monthly)',
            ],
            'security.login_attempts' => [
                'value' => '5',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Número máximo de tentativas de login',
            ],
            'security.session_timeout' => [
                'value' => '120',
                'type' => 'integer',
                'group' => 'security',
                'description' => 'Timeout de sessão em minutos',
            ],
            // Configurações reCAPTCHA v3
            'recaptcha.enabled' => [
                'value' => config('vertex.recaptcha.enabled', '0'),
                'type' => 'boolean',
                'group' => 'recaptcha',
                'description' => 'Habilitar Google reCAPTCHA v3',
            ],
            'recaptcha.site_key' => [
                'value' => config('vertex.recaptcha.site_key', ''),
                'type' => 'string',
                'group' => 'recaptcha',
                'description' => 'Site Key do Google reCAPTCHA v3',
            ],
            'recaptcha.secret_key' => [
                'value' => config('vertex.recaptcha.secret_key', ''),
                'type' => 'password',
                'group' => 'recaptcha',
                'description' => 'Secret Key do Google reCAPTCHA v3',
            ],
            'recaptcha.min_score' => [
                'value' => config('vertex.recaptcha.min_score', '0.5'),
                'type' => 'string',
                'group' => 'recaptcha',
                'description' => 'Score mínimo aceito (0.0 a 1.0). Recomendado: 0.5',
            ],
            'gateway.default_driver' => [
                'value' => (string) config('gateway.default_driver', 'mercadopago'),
                'type' => 'string',
                'group' => 'gateway',
                'description' => '[Gateway] Provedor de pagamento em linha por defeito',
            ],
            'gateway.default_currency' => [
                'value' => (string) config('gateway.default_currency', 'BRL'),
                'type' => 'string',
                'group' => 'gateway',
                'description' => '[Gateway] Moeda por defeito (ISO 4217, ex.: BRL)',
            ],
            // Configurações Google Maps
            'google_maps.api_key' => [
                'value' => config('vertex.google_maps.api_key', ''),
                'type' => 'password',
                'group' => 'integrations',
                'description' => 'Chave de API do Google Maps (Javascript API)',
            ],
            // =========================
            // Email / SMTP (Google SMTP)
            // =========================
            'mail.mailer' => [
                'value' => config('mail.default', 'log'),
                'type' => 'string',
                'group' => 'email',
                'description' => 'Driver do mailer (ex: smtp, log, array)',
            ],
            'mail.host' => [
                'value' => (string) config('mail.mailers.smtp.host', ''),
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP host',
            ],
            'mail.port' => [
                'value' => (int) config('mail.mailers.smtp.port', 2525),
                'type' => 'integer',
                'group' => 'email',
                'description' => 'SMTP port',
            ],
            'mail.username' => [
                'value' => (string) config('mail.mailers.smtp.username', ''),
                'type' => 'string',
                'group' => 'email',
                'description' => 'SMTP username',
            ],
            'mail.password' => [
                'value' => (string) config('mail.mailers.smtp.password', ''),
                'type' => 'password',
                'group' => 'email',
                'description' => 'SMTP password / app password',
            ],
            'mail.encryption' => [
                'value' => (string) config('mail.mailers.smtp.encryption', 'ssl'),
                'type' => 'string',
                'group' => 'email',
                'description' => 'encryption (ssl|tls)',
            ],
            'mail.from_address' => [
                'value' => (string) config('mail.from.address', 'hello@example.com'),
                'type' => 'string',
                'group' => 'email',
                'description' => 'From address',
            ],
            'mail.from_name' => [
                'value' => (string) config('mail.from.name', 'Example'),
                'type' => 'string',
                'group' => 'email',
                'description' => 'From name',
            ],

            // =========================
            // Broadcasting / Pusher
            // =========================
            'broadcast.driver' => [
                'value' => (string) config('broadcasting.default', 'log'),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Broadcast driver (ex: log, pusher, redis, ably)',
            ],
            'pusher.app_id' => [
                'value' => (string) config('broadcasting.connections.pusher.app_id', ''),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Pusher app_id',
            ],
            'pusher.app_key' => [
                'value' => (string) config('broadcasting.connections.pusher.key', ''),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Pusher app key',
            ],
            'pusher.app_secret' => [
                'value' => (string) config('broadcasting.connections.pusher.secret', ''),
                'type' => 'password',
                'group' => 'integrations',
                'description' => 'Pusher app secret',
            ],
            'pusher.cluster' => [
                'value' => (string) config('broadcasting.connections.pusher.options.cluster', 'mt1'),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Pusher cluster',
            ],
            'pusher.host' => [
                'value' => (string) config('broadcasting.connections.pusher.options.host', ''),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Pusher host (opcional)',
            ],
            'pusher.port' => [
                'value' => (int) config('broadcasting.connections.pusher.options.port', 443),
                'type' => 'integer',
                'group' => 'integrations',
                'description' => 'Pusher port',
            ],
            'pusher.scheme' => [
                'value' => (string) config('broadcasting.connections.pusher.options.scheme', 'https'),
                'type' => 'string',
                'group' => 'integrations',
                'description' => 'Pusher scheme (http|https)',
            ],

            // Homepage — Bíblia (mesmas chaves editadas em /admin/homepage)
            'homepage_bible_daily_enabled' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'bible_homepage',
                'description' => 'Mostrar bloco “Versículo do dia” na homepage',
            ],
            'homepage_bible_daily_version_id' => [
                'value' => '0',
                'type' => 'integer',
                'group' => 'bible_homepage',
                'description' => 'ID da versão bíblica (0 = versão padrão do módulo)',
            ],
            'homepage_bible_daily_title' => [
                'value' => 'Versículo do dia',
                'type' => 'string',
                'group' => 'bible_homepage',
                'description' => 'Título da secção na homepage',
            ],
            'homepage_bible_daily_subtitle' => [
                'value' => '',
                'type' => 'string',
                'group' => 'bible_homepage',
                'description' => 'Subtítulo opcional abaixo do título',
            ],
            'homepage_bible_daily_position' => [
                'value' => 'before_servicos',
                'type' => 'string',
                'group' => 'bible_homepage',
                'description' => 'Posição: after_hero | before_servicos | before_contato',
            ],
            'homepage_bible_daily_show_reference' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'bible_homepage',
                'description' => 'Exibir referência (livro capítulo:versículo)',
            ],
            'homepage_bible_daily_show_version_label' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'bible_homepage',
                'description' => 'Exibir nome/sigla da versão',
            ],
            'homepage_bible_daily_link_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'bible_homepage',
                'description' => 'Botão “Abrir na Bíblia”',
            ],
            'homepage_bible_daily_override_reference' => [
                'value' => '',
                'type' => 'string',
                'group' => 'bible_homepage',
                'description' => 'Referência fixa (ex. João 3:16). Vazio = versículo do dia automático',
            ],
            'homepage_bible_daily_salt' => [
                'value' => '',
                'type' => 'string',
                'group' => 'bible_homepage',
                'description' => 'Salt opcional para variar o versículo diário entre instalações (deixe vazio para usar chave da app)',
            ],
            'homepage_bible_navbar_enabled' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'bible_homepage',
                'description' => 'Link para a Bíblia na navbar da homepage',
            ],
            'homepage_bible_navbar_label' => [
                'value' => 'Bíblia',
                'type' => 'string',
                'group' => 'bible_homepage',
                'description' => 'Texto do link da Bíblia na navbar',
            ],
            'homepage_footer_credit_visible' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'homepage',
                'description' => 'Mostrar linha de créditos técnicos no rodapé da homepage',
            ],
            'homepage_footer_credit_organization' => [
                'value' => '',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Organização / apoio técnico (rodapé)',
            ],
            'homepage_footer_credit_contact_name' => [
                'value' => '',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Nome do contacto técnico',
            ],
            'homepage_footer_credit_email' => [
                'value' => '',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'E-mail do contacto técnico',
            ],
            'homepage_footer_credit_phone' => [
                'value' => '',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Telefone do contacto técnico',
            ],
            'homepage_contato_page_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'homepage',
                'description' => 'Página pública /contato ativa',
            ],
            'homepage_contato_form_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'homepage',
                'description' => 'Formulário de mensagem na página de contato',
            ],
            'homepage_contato_page_title' => [
                'value' => 'Fale com a JUBAF',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Título principal da página de contato',
            ],
            'homepage_contato_page_lead' => [
                'value' => 'Feira de Santana e região — envie a sua mensagem à diretoria regional. Resposta por e-mail ou telefone conforme disponibilidade.',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Texto introdutório da página de contato',
            ],
            'homepage_contato_home_cta' => [
                'value' => 'Formulário completo, dados institucionais e newsletter — abra a página de contato.',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Texto do convite na secção Contato da homepage',
            ],
            'homepage_newsletter_public_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'homepage',
                'description' => 'Mostrar bloco de newsletter na página /contato',
            ],
            'homepage_newsletter_box_title' => [
                'value' => 'Newsletter JUBAF',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Título do bloco de newsletter',
            ],
            'homepage_newsletter_box_lead' => [
                'value' => 'Receba novidades da juventude batista feirense na sua caixa de entrada.',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'Subtítulo do bloco de newsletter',
            ],
            'homepage_institutional_cnpj' => [
                'value' => '',
                'type' => 'string',
                'group' => 'homepage',
                'description' => 'CNPJ institucional (exibido no rodapé público)',
            ],
            'homepage_portal_igrejas_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'homepage',
                'description' => 'Mostrar bloco de igrejas associadas (dados agregados) na homepage',
            ],
            'homepage_portal_eventos_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'homepage',
                'description' => 'Mostrar próximos eventos públicos na homepage',
            ],
            'integrations_notify_on_devotional_published' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'integrations',
                'description' => 'Criar notificação interna ao publicar devocional (módulo Notificações)',
            ],
            'integrations_chat_widget_homepage_only' => [
                'value' => '0',
                'type' => 'boolean',
                'group' => 'integrations',
                'description' => 'Restringir widget de chat à página inicial (quando suportado)',
            ],

            // Módulos / site público
            'carousel_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'modules',
                'description' => '[Homepage] Carrossel de imagens na página inicial pública.',
            ],

            // Painéis Admin, Diretoria e RBAC (Permissão)
            'admin.impersonation_enabled' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'panels_access',
                'description' => '[Admin — segurança] Permitir personificação de utilizadores (rotas protegidas; só super-admin). Desligue se a política interna proibir.',
            ],
            'permisao.show_rbac_banner' => [
                'value' => '1',
                'type' => 'boolean',
                'group' => 'panels_access',
                'description' => '[Permissões / RBAC] Mostrar a faixa «Utilizadores → Funções → Permissões» nos ecrãs de gestão da diretoria.',
            ],
            'chat.agent_extra_roles' => [
                'value' => '',
                'type' => 'string',
                'group' => 'roles',
                'description' => '[Chat] Papéis extra que podem ser agentes de suporte (escolha na página Papéis e agentes).',
            ],
            'avisos.publish_extra_roles' => [
                'value' => '',
                'type' => 'string',
                'group' => 'roles',
                'description' => '[Avisos] Papéis extra autorizados a publicar avisos institucionais.',
            ],

            // Notificações, Evolution API, Financeiro (quotas), Igrejas — espelhado no .env
            'notificacoes.email_enabled' => [
                'value' => ((bool) config('notificacoes.email_enabled', false)) ? '1' : '0',
                'type' => 'boolean',
                'group' => 'platform_modules',
                'description' => 'Notificações — enviar e-mails (centro de notificações + e-mail)',
            ],
            'notificacoes.polling_interval' => [
                'value' => (string) ((int) config('notificacoes.polling_interval', 30000)),
                'type' => 'integer',
                'group' => 'platform_modules',
                'description' => 'Notificações — intervalo de atualização (ms) quando não usar WebSockets',
            ],
            'notificacoes.broadcasting_enabled' => [
                'value' => ((bool) config('notificacoes.broadcasting_enabled', true)) ? '1' : '0',
                'type' => 'boolean',
                'group' => 'platform_modules',
                'description' => 'Notificações — atualização em tempo real (broadcasting)',
            ],
            'notificacoes.whatsapp_base_url' => [
                'value' => (string) config('notificacoes.whatsapp.base_url', ''),
                'type' => 'string',
                'group' => 'platform_modules',
                'description' => 'WhatsApp (legado) — URL base da API, se usar integração alternativa',
            ],
            'notificacoes.whatsapp_token' => [
                'value' => (string) config('notificacoes.whatsapp.token', ''),
                'type' => 'password',
                'group' => 'platform_modules',
                'description' => 'WhatsApp (legado) — token / bearer, se aplicável',
            ],
            'evolution.api_url' => [
                'value' => (string) config('notificacoes.evolution.url', ''),
                'type' => 'string',
                'group' => 'platform_modules',
                'description' => 'Evolution API — URL base (ex.: https://api.seudominio.com)',
            ],
            'evolution.api_key' => [
                'value' => (string) config('notificacoes.evolution.key', ''),
                'type' => 'password',
                'group' => 'platform_modules',
                'description' => 'Evolution API — chave de autenticação',
            ],
            'evolution.instance' => [
                'value' => (string) config('notificacoes.evolution.instance', 'default'),
                'type' => 'string',
                'group' => 'platform_modules',
                'description' => 'Evolution API — nome da instância WhatsApp',
            ],
            'jubaf.assoc_quota_amount' => [
                'value' => (string) config('financeiro.quota.default_amount', 100),
                'type' => 'string',
                'group' => 'platform_modules',
                'description' => 'Financeiro — valor de referência da cota associativa (€ / unidade)',
            ],
            'jubaf.quota_fin_category_code' => [
                'value' => (string) config('financeiro.quota.income_category_code', ''),
                'type' => 'string',
                'group' => 'platform_modules',
                'description' => 'Financeiro — código da categoria de receita (plano de contas) para cotas',
            ],
            'jubaf.assoc_monthly_quota_amount' => [
                'value' => (string) config('financeiro.quota.monthly_invoice_amount', config('financeiro.quota.default_amount', 100)),
                'type' => 'string',
                'group' => 'platform_modules',
                'description' => 'Financeiro — valor por mês em facturas de cotas (mensais)',
            ],
            'jubaf.fin_extraordinary_groups' => [
                'value' => implode(',', config('financeiro.extraordinary_expense_group_keys', ['despesas_administrativas'])),
                'type' => 'text',
                'group' => 'platform_modules',
                'description' => 'Financeiro — chaves de grupos de despesas extraordinárias (separadas por vírgula)',
            ],
            'igrejas.notify_request_submit' => [
                'value' => ((bool) config('igrejas.integrations.notify_directorate_on_request_submit', true)) ? '1' : '0',
                'type' => 'boolean',
                'group' => 'platform_modules',
                'description' => 'Igrejas — notificar diretoria ao submeter pedido (cadastro / alteração)',
            ],
            'igrejas.notify_request_resolve' => [
                'value' => ((bool) config('igrejas.integrations.notify_submitter_on_request_resolve', true)) ? '1' : '0',
                'type' => 'boolean',
                'group' => 'platform_modules',
                'description' => 'Igrejas — notificar requerente quando o pedido for resolvido',
            ],
            'igrejas.aviso_draft_church' => [
                'value' => ((bool) config('igrejas.integrations.aviso_draft_on_church_activated', false)) ? '1' : '0',
                'type' => 'boolean',
                'group' => 'platform_modules',
                'description' => 'Igrejas — criar rascunho de aviso ao activar igreja',
            ],
            'igrejas.cal_warn_overlap' => [
                'value' => ((bool) config('igrejas.integrations.calendario_warn_local_overlap', true)) ? '1' : '0',
                'type' => 'boolean',
                'group' => 'platform_modules',
                'description' => 'Igrejas — avisar sobre sobreposição de eventos no calendário local',
            ],
        ];
    }

    /**
     * Initialize default configs
     */
    public function initializeDefaultConfigs(): void
    {
        $defaults = $this->getDefaultConfigs();

        foreach ($defaults as $key => $config) {
            SystemConfig::firstOrCreate(
                ['key' => $key],
                [
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'group' => $config['group'],
                    'description' => $config['description'],
                ]
            );
        }
    }

    /**
     * Ensure branding / logo paths exist in system_configs.
     */
    public function ensureBrandingConfigs(): void
    {
        $defaults = $this->getDefaultConfigs();
        $keys = [
            'branding.logo_default',
            'branding.logo_light',
            'branding.logo_dark',
            'branding.site_tagline',
        ];

        foreach ($keys as $key) {
            if (! SystemConfig::where('key', $key)->exists()) {
                $meta = $defaults[$key] ?? null;
                if ($meta === null) {
                    continue;
                }
                SystemConfig::create([
                    'key' => $key,
                    'value' => $meta['value'],
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'description' => $meta['description'],
                ]);
            }
        }
    }

    /**
     * Ensure reCAPTCHA configs exist (sync with .env)
     */
    public function ensureRecaptchaConfigs(): void
    {
        $recaptchaConfigs = [
            'recaptcha.enabled' => config('vertex.recaptcha.enabled') ? '1' : '0',
            'recaptcha.site_key' => config('vertex.recaptcha.site_key', ''),
            'recaptcha.secret_key' => config('vertex.recaptcha.secret_key', ''),
            'recaptcha.min_score' => config('vertex.recaptcha.min_score', '0.5'),
        ];

        foreach ($recaptchaConfigs as $key => $envValue) {
            $config = SystemConfig::where('key', $key)->first();
            $defaultConfig = $this->getDefaultConfigs()[$key] ?? null;

            if (! $config) {
                SystemConfig::create([
                    'key' => $key,
                    'value' => $envValue,
                    'type' => $defaultConfig['type'] ?? 'string',
                    'group' => 'recaptcha',
                    'description' => $defaultConfig['description'] ?? '',
                ]);
            } elseif ($config->value !== $envValue && ! empty($envValue)) {
                // Sincronizar com .env se o valor do .env não estiver vazio
                $config->value = $envValue;
                $config->save();
            }
        }
    }

    /**
     * Ensure Google Maps configs exist (sync with .env)
     */
    public function ensureGoogleMapsConfigs(): void
    {
        $mapsConfigs = [
            'google_maps.api_key' => config('vertex.google_maps.api_key', ''),
        ];

        foreach ($mapsConfigs as $key => $envValue) {
            $config = SystemConfig::where('key', $key)->first();
            $defaultConfig = $this->getDefaultConfigs()[$key] ?? null;

            if (! $config) {
                SystemConfig::create([
                    'key' => $key,
                    'value' => $envValue,
                    'type' => $defaultConfig['type'] ?? 'string',
                    'group' => 'integrations',
                    'description' => $defaultConfig['description'] ?? '',
                ]);
            } elseif ($config->value !== $envValue && ! empty($envValue)) {
                // Sincronizar com .env se o valor do .env não estiver vazio
                $config->value = $envValue;
                $config->save();
            }
        }
    }

    /**
     * Gateway — driver e moeda por defeito (cria linhas em falta; não sobrescreve valores já gravados).
     */
    public function ensureGatewayConfigs(): void
    {
        $defaults = $this->getDefaultConfigs();
        foreach (['gateway.default_driver', 'gateway.default_currency'] as $key) {
            if (SystemConfig::where('key', $key)->exists()) {
                continue;
            }
            $meta = $defaults[$key] ?? null;
            if ($meta === null) {
                continue;
            }
            SystemConfig::create([
                'key' => $key,
                'value' => $meta['value'],
                'type' => $meta['type'],
                'group' => 'gateway',
                'description' => $meta['description'],
            ]);
        }
    }

    /**
     * Ensure Mail configs exist (sync with .env)
     */
    public function ensureMailConfigs(): void
    {
        $mailConfigs = [
            'mail.mailer' => config('mail.default', 'log'),
            'mail.host' => config('mail.mailers.smtp.host', ''),
            'mail.port' => config('mail.mailers.smtp.port', 2525),
            'mail.username' => config('mail.mailers.smtp.username', ''),
            'mail.password' => config('mail.mailers.smtp.password', ''),
            'mail.encryption' => config('mail.mailers.smtp.encryption', 'ssl'),
            'mail.from_address' => config('mail.from.address', 'hello@example.com'),
            'mail.from_name' => config('mail.from.name', 'Example'),
        ];

        foreach ($mailConfigs as $key => $envValue) {
            $config = SystemConfig::where('key', $key)->first();
            $defaultConfig = $this->getDefaultConfigs()[$key] ?? null;

            if (! $config) {
                SystemConfig::create([
                    'key' => $key,
                    'value' => (string) $envValue,
                    'type' => $defaultConfig['type'] ?? 'string',
                    'group' => $defaultConfig['group'] ?? 'email',
                    'description' => $defaultConfig['description'] ?? '',
                ]);
            } elseif ($config->value !== (string) $envValue && $envValue !== null && $envValue !== '') {
                // Sincronizar com .env se o valor do .env não estiver vazio
                $config->value = (string) $envValue;
                $config->save();
            }
        }
    }

    /**
     * Ensure Pusher/Broadcast configs exist (sync with .env)
     */
    public function ensurePusherConfigs(): void
    {
        $pusherConfigs = [
            'broadcast.driver' => config('broadcasting.default', 'log'),
            'pusher.app_id' => config('broadcasting.connections.pusher.app_id', ''),
            'pusher.app_key' => config('broadcasting.connections.pusher.key', ''),
            'pusher.app_secret' => config('broadcasting.connections.pusher.secret', ''),
            'pusher.cluster' => config('broadcasting.connections.pusher.options.cluster', 'mt1'),
            'pusher.host' => config('broadcasting.connections.pusher.options.host', ''),
            'pusher.port' => config('broadcasting.connections.pusher.options.port', 443),
            'pusher.scheme' => config('broadcasting.connections.pusher.options.scheme', 'https'),
        ];

        foreach ($pusherConfigs as $key => $envValue) {
            $config = SystemConfig::where('key', $key)->first();
            $defaultConfig = $this->getDefaultConfigs()[$key] ?? null;

            if (! $config) {
                SystemConfig::create([
                    'key' => $key,
                    'value' => (string) $envValue,
                    'type' => $defaultConfig['type'] ?? 'string',
                    'group' => $defaultConfig['group'] ?? 'integrations',
                    'description' => $defaultConfig['description'] ?? '',
                ]);
            } elseif ($config->value !== (string) $envValue && $envValue !== null && $envValue !== '') {
                // Sincronizar com .env se o valor do .env não estiver vazio
                $config->value = (string) $envValue;
                $config->save();
            }
        }
    }

    /**
     * Garante chaves dos módulos Notificações, Evolution, Financeiro e Igrejas (painel + .env).
     */
    public function ensureModulePlatformConfigs(): void
    {
        $defaults = $this->getDefaultConfigs();
        foreach ($defaults as $key => $meta) {
            if (($meta['group'] ?? '') !== 'platform_modules') {
                continue;
            }
            if (! SystemConfig::where('key', $key)->exists()) {
                SystemConfig::create([
                    'key' => $key,
                    'value' => $meta['value'],
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'description' => $meta['description'],
                ]);
            }
        }
    }

    /**
     * Painéis (Admin / Diretoria / RBAC), carrossel na homepage e chaves relacionadas.
     */
    public function ensurePanelsAccessAndModulesDefaults(): void
    {
        $defaults = $this->getDefaultConfigs();

        foreach ($defaults as $key => $meta) {
            if (($meta['group'] ?? '') !== 'panels_access') {
                continue;
            }
            if (! SystemConfig::where('key', $key)->exists()) {
                SystemConfig::create([
                    'key' => $key,
                    'value' => $meta['value'],
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'description' => $meta['description'],
                ]);
            }
        }

        $carouselMeta = $defaults['carousel_enabled'] ?? null;
        $carousel = SystemConfig::where('key', 'carousel_enabled')->first();
        if ($carousel) {
            $carousel->group = 'modules';
            if ($carouselMeta && (trim((string) $carousel->description) === '' || $carousel->description === 'Habilita carrossel na homepage')) {
                $carousel->description = $carouselMeta['description'];
            }
            $carousel->save();
        } elseif ($carouselMeta) {
            SystemConfig::create([
                'key' => 'carousel_enabled',
                'value' => $carouselMeta['value'],
                'type' => $carouselMeta['type'],
                'group' => 'modules',
                'description' => $carouselMeta['description'],
            ]);
        }
    }

    /**
     * Papéis — rótulos/ajuda editáveis, agentes de chat e publicadores de avisos; remove chave legada do painel.
     */
    public function ensureRolesAndLabelsConfigs(): void
    {
        SystemConfig::where('key', 'access.legacy_co_admin_diretoria')->delete();

        $defaults = $this->getDefaultConfigs();

        foreach (['chat.agent_extra_roles', 'avisos.publish_extra_roles'] as $key) {
            $row = SystemConfig::where('key', $key)->first();
            if ($row && $row->group !== 'roles') {
                $row->group = 'roles';
                $row->save();
            }
            if (! SystemConfig::where('key', $key)->exists()) {
                $meta = $defaults[$key] ?? null;
                if ($meta !== null) {
                    SystemConfig::create([
                        'key' => $key,
                        'value' => $meta['value'],
                        'type' => $meta['type'],
                        'group' => 'roles',
                        'description' => $meta['description'],
                    ]);
                }
            }
        }

        foreach (JubafRoleRegistry::roleSlugsForPanelLabels() as $slug) {
            $labelKey = 'role.display.'.$slug;
            if (! SystemConfig::where('key', $labelKey)->exists()) {
                SystemConfig::create([
                    'key' => $labelKey,
                    'value' => (string) config('jubaf_roles.labels.'.$slug, ''),
                    'type' => 'string',
                    'group' => 'roles',
                    'description' => 'Nome exibido no painel para o papel «'.$slug.'»',
                ]);
            }

            $helpKey = 'role.help.'.$slug;
            if (! SystemConfig::where('key', $helpKey)->exists()) {
                $help = config('jubaf_roles.descriptions.'.$slug);
                SystemConfig::create([
                    'key' => $helpKey,
                    'value' => $help ? (string) $help : '',
                    'type' => 'text',
                    'group' => 'roles',
                    'description' => 'Texto de ajuda (opcional) para «'.$slug.'»',
                ]);
            }
        }
    }

    /**
     * Garante chaves da Bíblia na homepage, rodapé (créditos) e flags de integração.
     * Migra valores legados homepage_footer_vertex_* para homepage_footer_credit_*.
     */
    public function ensureHomepageBibleAndFooterConfigs(): void
    {
        $defaults = $this->getDefaultConfigs();
        $keys = [
            'homepage_bible_daily_enabled',
            'homepage_bible_daily_version_id',
            'homepage_bible_daily_title',
            'homepage_bible_daily_subtitle',
            'homepage_bible_daily_position',
            'homepage_bible_daily_show_reference',
            'homepage_bible_daily_show_version_label',
            'homepage_bible_daily_link_enabled',
            'homepage_bible_daily_override_reference',
            'homepage_bible_daily_salt',
            'homepage_bible_navbar_enabled',
            'homepage_bible_navbar_label',
            'homepage_footer_credit_visible',
            'homepage_footer_credit_organization',
            'homepage_footer_credit_contact_name',
            'homepage_footer_credit_email',
            'homepage_footer_credit_phone',
            'homepage_contato_page_enabled',
            'homepage_contato_form_enabled',
            'homepage_contato_page_title',
            'homepage_contato_page_lead',
            'homepage_contato_home_cta',
            'homepage_newsletter_public_enabled',
            'homepage_newsletter_box_title',
            'homepage_newsletter_box_lead',
            'integrations_notify_on_devotional_published',
            'integrations_chat_widget_homepage_only',
        ];

        foreach ($keys as $key) {
            if (! SystemConfig::where('key', $key)->exists()) {
                $meta = $defaults[$key] ?? null;
                if ($meta === null) {
                    continue;
                }
                SystemConfig::create([
                    'key' => $key,
                    'value' => $meta['value'],
                    'type' => $meta['type'],
                    'group' => $meta['group'],
                    'description' => $meta['description'],
                ]);
            }
        }

        $this->migrateLegacyVertexFooterCredits();
    }

    private function migrateLegacyVertexFooterCredits(): void
    {
        $map = [
            'homepage_footer_vertex_company' => 'homepage_footer_credit_organization',
            'homepage_footer_vertex_ceo' => 'homepage_footer_credit_contact_name',
            'homepage_footer_vertex_email' => 'homepage_footer_credit_email',
            'homepage_footer_vertex_phone' => 'homepage_footer_credit_phone',
        ];

        foreach ($map as $oldKey => $newKey) {
            $old = SystemConfig::where('key', $oldKey)->first();
            if (! $old || trim((string) $old->value) === '') {
                continue;
            }

            $new = SystemConfig::where('key', $newKey)->first();
            if (! $new || trim((string) $new->value) === '') {
                SystemConfig::set($newKey, $old->value, 'string', 'homepage', 'Créditos no rodapé (migrado)');
            }
        }

        $hadLegacy = SystemConfig::where('key', 'homepage_footer_vertex_company')
            ->whereNotNull('value')
            ->where('value', '!=', '')
            ->exists();

        if ($hadLegacy) {
            SystemConfig::set('homepage_footer_credit_visible', true, 'boolean', 'homepage', 'Exibir créditos no rodapé');
        }
    }

    /**
     * Update config and sync with .env file (supports deferred sync for batch operations)
     */
    public function updateConfigWithEnvSync(string $key, $value, ?string $type = null, ?string $description = null, bool $deferSync = false): SystemConfig
    {
        $config = $this->updateConfig($key, $value, $type, $description);

        if (! $deferSync) {
            $this->syncOneToEnv($key, $config->value);
        }

        return $config;
    }

    /**
     * Update multiple configs and sync with .env in a single write operation
     */
    public function batchUpdateConfigsWithEnvSync(array $configsToUpdate): void
    {
        $envUpdates = [];

        foreach ($configsToUpdate as $key => $value) {
            $config = SystemConfig::where('key', $key)->first();

            if ($config) {
                // Se for boolean, garantir formato 0 ou 1
                $processedValue = $value;
                if ($config->type === 'boolean') {
                    $processedValue = $value ? '1' : '0';
                }

                $this->updateConfig($key, $value, $config->type, $config->description);

                $envKey = $this->envKeyForConfigKey($key);
                if ($envKey !== null) {
                    $envUpdates[$envKey] = $this->formatValueForEnvFile($envKey, $config->type, (string) $processedValue);
                }
            } else {
                // Caso a config não exista na DB, apenas atualiza
                $this->updateConfig($key, $value);
            }
        }

        if (! empty($envUpdates)) {
            $this->syncBatchToEnv($envUpdates);
        }
    }

    /**
     * Sync a single config to .env
     */
    protected function syncOneToEnv(string $key, string $value): void
    {
        $envKey = $this->envKeyForConfigKey($key);
        if ($envKey === null) {
            return;
        }

        $config = SystemConfig::where('key', $key)->first();
        $type = $config?->type ?? 'string';

        $this->syncBatchToEnv([$envKey => $this->formatValueForEnvFile($envKey, $type, $value)]);
    }

    /**
     * Sync multiple config values to .env file in one go
     */
    protected function syncBatchToEnv(array $updates): void
    {
        $envPath = base_path('.env');

        if (! file_exists($envPath)) {
            return;
        }

        try {
            $envContent = file_get_contents($envPath);
            $modified = false;

            foreach ($updates as $envKey => $value) {
                // Escapar caracteres especiais para regex
                $escapedKey = preg_quote($envKey, '/');
                $pattern = "/^{$escapedKey}=.*/m";

                // Se o valor contém espaços ou caracteres especiais, usar aspas
                $formattedValue = $value;
                if (preg_match('/[\s#=]/', (string) $value)) {
                    $formattedValue = '"'.addslashes((string) $value).'"';
                }

                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, "{$envKey}={$formattedValue}", $envContent);
                } else {
                    // Adicionar no final do arquivo
                    $envContent .= "\n{$envKey}={$formattedValue}";
                }
                $modified = true;
            }

            if ($modified) {
                file_put_contents($envPath, $envContent);
                try {
                    Artisan::call('config:clear');
                } catch (\Throwable $e) {
                    \Log::warning('config:clear após sincronizar .env: '.$e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Erro ao sincronizar lote de configurações com .env: '.$e->getMessage());
        }
    }

    /**
     * Nome da variável no .env para uma chave de system_configs, ou null se não sincronizar.
     */
    protected function envKeyForConfigKey(string $key): ?string
    {
        $direct = [
            'system.name' => 'APP_NAME',
            'security.session_timeout' => 'SESSION_LIFETIME',
        ];
        if (isset($direct[$key])) {
            return $direct[$key];
        }

        $prefixes = [
            'mail.',
            'recaptcha.',
            'google_maps.',
            'gateway.',
            'broadcast.',
            'pusher.',
            'notificacoes.',
            'evolution.',
            'jubaf.',
            'igrejas.',
        ];

        foreach ($prefixes as $prefix) {
            if (str_starts_with($key, $prefix)) {
                return strtoupper(str_replace('.', '_', $key));
            }
        }

        return null;
    }

    /**
     * Formato gravado no .env (booleanos como true/false para leitura consistente pelo Laravel).
     */
    protected function formatValueForEnvFile(string $envKey, string $configType, string $value): string
    {
        if ($configType === 'boolean') {
            return $value === '1' ? 'true' : 'false';
        }

        return $value;
    }
}
