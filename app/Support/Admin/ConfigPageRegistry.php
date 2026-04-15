<?php

namespace App\Support\Admin;

/**
 * Páginas do painel global de configuração (shell + conteúdo).
 *
 * type: generic (loop SystemConfig por group), branding (partial existente), custom (view dedicada).
 */
final class ConfigPageRegistry
{
    public static function defaultSection(): string
    {
        return 'general';
    }

    /**
     * @return list<array{id:string,label:string,icon:string,type:string,group?:string,view?:string}>
     */
    public static function pages(): array
    {
        return [
            ['id' => 'general', 'label' => 'Geral', 'icon' => 'cog', 'type' => 'generic', 'group' => 'general'],
            ['id' => 'branding', 'label' => 'Marca', 'icon' => 'image', 'type' => 'branding'],
            ['id' => 'email', 'label' => 'E-mail', 'icon' => 'envelope', 'type' => 'generic', 'group' => 'email'],
            ['id' => 'security', 'label' => 'Segurança', 'icon' => 'shield-check', 'type' => 'generic', 'group' => 'security'],
            ['id' => 'backup', 'label' => 'Backup', 'icon' => 'cloud-arrow-up', 'type' => 'generic', 'group' => 'backup'],
            ['id' => 'modules', 'label' => 'Módulos', 'icon' => 'cubes', 'type' => 'generic', 'group' => 'modules'],
            ['id' => 'panels_access', 'label' => 'Painéis e acesso', 'icon' => 'user-shield', 'type' => 'generic', 'group' => 'panels_access'],
            ['id' => 'roles', 'label' => 'Papéis e agentes', 'icon' => 'users', 'type' => 'custom', 'view' => 'admin::config.pages.roles'],
            ['id' => 'recaptcha', 'label' => 'reCAPTCHA', 'icon' => 'google', 'type' => 'custom', 'view' => 'admin::config.pages.recaptcha'],
            ['id' => 'integrations', 'label' => 'Integrações', 'icon' => 'puzzle-piece', 'type' => 'generic', 'group' => 'integrations'],
            ['id' => 'gateway', 'label' => 'Gateway', 'icon' => 'credit-card', 'type' => 'custom', 'view' => 'admin::config.pages.gateway'],
            ['id' => 'platform_modules', 'label' => 'Notificações e módulos', 'icon' => 'bolt', 'type' => 'generic', 'group' => 'platform_modules'],
            ['id' => 'bible_homepage', 'label' => 'Bíblia na homepage', 'icon' => 'book-bible', 'type' => 'generic', 'group' => 'bible_homepage'],
            ['id' => 'homepage', 'label' => 'Homepage', 'icon' => 'home', 'type' => 'generic', 'group' => 'homepage'],
        ];
    }

    public static function find(string $section): ?array
    {
        foreach (self::pages() as $page) {
            if ($page['id'] === $section) {
                return $page;
            }
        }

        return null;
    }
}
