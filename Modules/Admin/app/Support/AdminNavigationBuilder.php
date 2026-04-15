<?php

namespace Modules\Admin\App\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Admin\App\Events\AdminNavigationBuilding;

final class AdminNavigationBuilder
{
    /**
     * @return list<array<string, mixed>>
     */
    public function build(): array
    {
        $bag = new AdminNavigationBag($this->defaultSections());
        event(new AdminNavigationBuilding($bag));

        return $this->filterSections($bag->sections);
    }

    /**
     * @param  list<array<string, mixed>>  $sections
     * @return list<array<string, mixed>>
     */
    private function filterSections(array $sections): array
    {
        $out = [];
        foreach ($sections as $section) {
            if (! $this->passesContext($section)) {
                continue;
            }
            $items = $this->filterItems($section['items'] ?? []);
            if ($items === []) {
                continue;
            }
            $section['items'] = $items;
            $out[] = $section;
        }

        return $out;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<array<string, mixed>>
     */
    private function filterItems(array $items): array
    {
        $out = [];
        foreach ($items as $item) {
            if (! $this->passesItem($item)) {
                continue;
            }
            if (($item['type'] ?? '') === 'accordion') {
                $item['children'] = $this->filterItems($item['children'] ?? []);
                if ($item['children'] === []) {
                    continue;
                }
            }
            if (($item['type'] ?? '') === 'group') {
                $item['items'] = $this->filterItems($item['items'] ?? []);
                if ($item['items'] === []) {
                    continue;
                }
            }
            $out[] = $item;
        }

        return $out;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function passesItem(array $item): bool
    {
        if (! $this->passesContext($item)) {
            return false;
        }

        $type = $item['type'] ?? 'link';

        if ($type === 'link' || $type === 'accordion') {
            $route = $item['route'] ?? null;
            if (is_string($route) && $route !== '' && ! Route::has($route)) {
                return false;
            }
        }

        if ($type === 'group') {
            return true;
        }

        if ($type === 'bible') {
            return Route::has('admin.bible.index');
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function passesContext(array $row): bool
    {
        if (! empty($row['module']) && ! module_enabled($row['module'])) {
            return false;
        }

        if (! empty($row['requires_route']) && is_string($row['requires_route']) && ! Route::has($row['requires_route'])) {
            return false;
        }

        $user = Auth::user();
        if (! empty($row['roles']) && is_array($row['roles'])) {
            if (! $user || ! method_exists($user, 'hasAnyRole') || ! $user->hasAnyRole($row['roles'])) {
                return false;
            }
        }

        if (! empty($row['permission']) && is_string($row['permission'])) {
            if (! $user || ! $user->can($row['permission'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function defaultSections(): array
    {
        return [
            [
                'id' => 'main',
                'heading' => null,
                'items' => [
                    [
                        'type' => 'link',
                        'route' => 'admin.dashboard',
                        'label' => 'Dashboard',
                        'tone' => 'emerald',
                        'icon' => ['name' => 'chart-pie', 'style' => 'duotone'],
                        'active' => ['admin.dashboard*'],
                    ],
                    [
                        'type' => 'group',
                        'module' => 'Homepage',
                        'requires_route' => 'admin.homepage.index',
                        'items' => [
                            [
                                'type' => 'link',
                                'route' => 'admin.homepage.index',
                                'label' => 'Homepage',
                                'tone' => 'emerald',
                                'module_icon' => 'Homepage',
                                'active' => ['admin.homepage.index'],
                            ],
                            [
                                'type' => 'link',
                                'route' => 'admin.homepage.contacts.index',
                                'label' => 'Mensagens (contato)',
                                'tone' => 'emerald_sub',
                                'icon' => ['name' => 'inbox', 'style' => 'duotone'],
                                'active' => ['admin.homepage.contacts.*'],
                                'indent' => true,
                            ],
                            [
                                'type' => 'link',
                                'route' => 'admin.homepage.newsletter.index',
                                'label' => 'Newsletter',
                                'tone' => 'emerald_sub',
                                'icon' => ['name' => 'envelope', 'style' => 'duotone'],
                                'active' => ['admin.homepage.newsletter.*'],
                                'indent' => true,
                            ],
                        ],
                    ],
                    [
                        'type' => 'link',
                        'module' => 'Homepage',
                        'requires_route' => 'admin.board-members.index',
                        'route' => 'admin.board-members.index',
                        'label' => 'Diretoria',
                        'tone' => 'indigo',
                        'icon' => ['name' => 'users', 'style' => 'duotone'],
                        'active' => ['admin.board-members.*'],
                    ],
                    [
                        'type' => 'link',
                        'module' => 'Homepage',
                        'requires_route' => 'admin.devotionals.index',
                        'route' => 'admin.devotionals.index',
                        'label' => 'Devocionais',
                        'tone' => 'amber',
                        'icon' => ['name' => 'book-open', 'style' => 'duotone'],
                        'active' => ['admin.devotionals.*'],
                    ],
                    [
                        'type' => 'link',
                        'module' => 'Blog',
                        'requires_route' => 'admin.blog.index',
                        'route' => 'admin.blog.index',
                        'label' => 'Blog',
                        'tone' => 'emerald',
                        'module_icon' => 'Blog',
                        'active' => ['admin.blog.*'],
                    ],
                    [
                        'type' => 'link',
                        'module' => 'Avisos',
                        'requires_route' => 'admin.avisos.index',
                        'route' => 'admin.avisos.index',
                        'label' => 'Avisos e Banners',
                        'tone' => 'emerald',
                        'module_icon' => 'Avisos',
                        'active' => ['admin.avisos.*'],
                    ],
                    [
                        'type' => 'bible',
                        'module' => 'Bible',
                        'roles' => ['super-admin'],
                        'route' => 'admin.bible.index',
                        'label' => 'Bíblia digital',
                        'tone' => 'amber_bible',
                        'module_icon' => 'Bible',
                        'active' => ['admin.bible.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.carousel.index',
                        'route' => 'admin.carousel.index',
                        'label' => 'Carousel',
                        'tone' => 'emerald',
                        'icon' => ['name' => 'images', 'style' => 'duotone'],
                        'active' => ['admin.carousel.*'],
                    ],
                ],
            ],
            [
                'id' => 'comms',
                'heading' => ['label' => 'Comunicação', 'icon' => ['name' => 'comments', 'style' => 'duotone']],
                'items' => [
                    [
                        'type' => 'link',
                        'module' => 'Chat',
                        'requires_route' => 'admin.chat.index',
                        'route' => 'admin.chat.index',
                        'label' => 'Atendimento Online',
                        'tone' => 'blue',
                        'module_icon' => 'Chat',
                        'active' => ['admin.chat.*'],
                    ],
                    [
                        'type' => 'accordion',
                        'module' => 'Notificacoes',
                        'requires_route' => 'admin.notificacoes.index',
                        'route' => 'admin.notificacoes.index',
                        'label' => 'Notificações',
                        'tone' => 'orange',
                        'module_icon' => 'Notificacoes',
                        'active' => ['admin.notificacoes.*'],
                        'children' => [
                            [
                                'type' => 'link',
                                'route' => 'admin.notificacoes.index',
                                'label' => 'Listagem',
                                'tone' => 'orange_sub',
                                'active' => ['admin.notificacoes.index', 'admin.notificacoes.show'],
                            ],
                            [
                                'type' => 'link',
                                'route' => 'admin.notificacoes.create',
                                'label' => 'Nova Notificação',
                                'tone' => 'orange_sub',
                                'active' => ['admin.notificacoes.create'],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'id' => 'system',
                'heading' => ['label' => 'Sistema', 'icon' => ['name' => 'gear', 'style' => 'duotone']],
                'roles' => ['super-admin'],
                'items' => [
                    [
                        'type' => 'link',
                        'module' => 'Igrejas',
                        'requires_route' => 'admin.igrejas.index',
                        'route' => 'admin.igrejas.index',
                        'label' => 'Igrejas',
                        'tone' => 'indigo',
                        'module_icon' => 'Igrejas',
                        'active' => ['admin.igrejas.*'],
                    ],
                    [
                        'type' => 'link',
                        'module' => 'Secretaria',
                        'requires_route' => 'admin.secretaria.dashboard',
                        'route' => 'admin.secretaria.dashboard',
                        'label' => 'Secretaria',
                        'tone' => 'violet',
                        'module_icon' => 'Secretaria',
                        'active' => ['admin.secretaria.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.audit.index',
                        'route' => 'admin.audit.index',
                        'label' => 'Logs de Auditoria',
                        'tone' => 'red',
                        'icon' => ['name' => 'file-shield', 'style' => 'duotone'],
                        'active' => ['admin.audit.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.users.index',
                        'route' => 'admin.users.index',
                        'label' => 'Usuários',
                        'tone' => 'emerald',
                        'icon' => ['name' => 'users', 'style' => 'duotone'],
                        'active' => ['admin.users.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.modules.index',
                        'route' => 'admin.modules.index',
                        'label' => 'Módulos',
                        'tone' => 'violet',
                        'icon' => ['name' => 'cubes', 'style' => 'duotone'],
                        'active' => ['admin.modules.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.config.index',
                        'route' => 'admin.config.index',
                        'label' => 'Configurações',
                        'tone' => 'slate',
                        'icon' => ['name' => 'gear', 'style' => 'duotone'],
                        'active' => ['admin.config.*'],
                    ],
                    [
                        'type' => 'accordion',
                        'requires_route' => 'admin.roles.index',
                        'route' => 'admin.roles.index',
                        'label' => 'Controle de Acesso',
                        'tone' => 'slate',
                        'icon' => ['name' => 'shield-halved', 'style' => 'duotone'],
                        'active' => ['admin.roles.*', 'admin.permissions.*', 'admin.seguranca.*'],
                        'children' => array_values(array_filter([
                            Route::has('admin.seguranca.hub') ? [
                                'type' => 'link',
                                'route' => 'admin.seguranca.hub',
                                'label' => 'Hub segurança',
                                'tone' => 'slate_sub',
                                'active' => ['admin.seguranca.*'],
                            ] : null,
                            [
                                'type' => 'link',
                                'route' => 'admin.roles.index',
                                'label' => 'Funções (Roles)',
                                'tone' => 'slate_sub',
                                'active' => ['admin.roles.*'],
                            ],
                            [
                                'type' => 'link',
                                'route' => 'admin.permissions.index',
                                'label' => 'Permissões',
                                'tone' => 'slate_sub',
                                'active' => ['admin.permissions.*'],
                            ],
                        ])),
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.api.index',
                        'route' => 'admin.api.index',
                        'label' => 'API',
                        'tone' => 'indigo',
                        'icon' => ['name' => 'code', 'style' => 'duotone'],
                        'active' => ['admin.api.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.backup.index',
                        'route' => 'admin.backup.index',
                        'label' => 'Backup',
                        'tone' => 'cyan',
                        'icon' => ['name' => 'database', 'style' => 'duotone'],
                        'active' => ['admin.backup.*'],
                    ],
                    [
                        'type' => 'link',
                        'requires_route' => 'admin.updates.index',
                        'route' => 'admin.updates.index',
                        'label' => 'Atualizações',
                        'tone' => 'indigo',
                        'icon' => ['name' => 'arrow-up-from-bracket', 'style' => 'duotone'],
                        'active' => ['admin.updates.*'],
                    ],
                ],
            ],
        ];
    }
}
