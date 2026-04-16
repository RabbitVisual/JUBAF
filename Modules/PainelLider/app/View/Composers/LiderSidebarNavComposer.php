<?php

namespace Modules\PainelLider\App\View\Composers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class LiderSidebarNavComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        $nav = $this->buildNavCategories($user);
        foreach ($nav as $i => $row) {
            $nav[$i]['rail'] = $this->railMetaForSection($row['id']);
            $nav[$i]['section_active'] = $this->sectionMatchesRoute($row['id']);
        }

        $view->with([
            'liderNav' => $nav,
            'liderNavDefaultSection' => $this->defaultActiveSectionId($nav),
            'liderQuickLinks' => $this->buildQuickLinks($user),
            'liderPageEyebrow' => $this->resolvePageEyebrow(),
            'liderPageTitle' => $this->resolvePageTitle(),
        ]);
    }

    /**
     * @param  list<array{id: string, label: string, entries: list<array<string, mixed>>, rail?: array<string, mixed>, section_active?: bool}>  $nav
     */
    private function defaultActiveSectionId(array $nav): string
    {
        foreach ($nav as $row) {
            if (! empty($row['section_active'])) {
                return $row['id'];
            }
        }

        return $nav[0]['id'] ?? 'principal';
    }

    private function sectionMatchesRoute(string $id): bool
    {
        return match ($id) {
            'principal' => request()->routeIs('lideres.dashboard*'),
            'igreja' => request()->routeIs('lideres.congregacao*', 'lideres.igrejas.requests*'),
            'biblia' => request()->routeIs('lideres.bible*'),
            'comunicacao' => request()->routeIs('lideres.avisos*', 'lideres.blog*', 'lideres.notificacoes*', 'lideres.chat*'),
            'organizacao' => request()->routeIs('lideres.calendario*', 'lideres.financeiro*', 'lideres.secretaria*'),
            'talento' => request()->routeIs('lideres.talentos*', 'lideres.juventude*'),
            'conta' => request()->routeIs('lideres.profile*'),
            default => false,
        };
    }

    /**
     * @return array{icon: array{kind: string, name?: string, module?: string}, tooltip: string}
     */
    private function railMetaForSection(string $id): array
    {
        return match ($id) {
            'principal' => ['icon' => ['kind' => 'icon', 'name' => 'grid-2-plus'], 'tooltip' => 'Principal'],
            'igreja' => ['icon' => ['kind' => 'icon', 'name' => 'church'], 'tooltip' => 'Igreja e juventude'],
            'biblia' => ['icon' => ['kind' => 'icon', 'name' => 'book-bible'], 'tooltip' => 'Bíblia e estudo'],
            'comunicacao' => ['icon' => ['kind' => 'icon', 'name' => 'messages'], 'tooltip' => 'Comunicação'],
            'organizacao' => ['icon' => ['kind' => 'icon', 'name' => 'calendar-days'], 'tooltip' => 'Organização'],
            'talento' => ['icon' => ['kind' => 'icon', 'name' => 'chart-simple'], 'tooltip' => 'Talento e impacto'],
            'conta' => ['icon' => ['kind' => 'icon', 'name' => 'user-vneck'], 'tooltip' => 'Conta'],
            default => ['icon' => ['kind' => 'icon', 'name' => 'compass'], 'tooltip' => 'Menu'],
        };
    }

    /**
     * @return list<array{id: string, label: string, entries: list<array<string, mixed>>}>
     */
    private function buildNavCategories(?Authenticatable $user): array
    {
        $u = $user instanceof User ? $user : null;

        $categories = [];

        $categories[] = [
            'id' => 'principal',
            'label' => 'Principal',
            'entries' => $this->filterEntries(array_values(array_filter([
                $this->linkEntry(
                    label: 'Início',
                    route: 'lideres.dashboard',
                    active: request()->routeIs('lideres.dashboard', 'lideres.dashboard.index'),
                    icon: ['kind' => 'icon', 'name' => 'grid-2-plus'],
                    visible: $this->routeHas('lideres.dashboard'),
                ),
            ]))),
        ];

        if ($this->mod('Igrejas')) {
            $igrejaChildren = array_values(array_filter([
                $this->childLink('Congregação', 'lideres.congregacao.index', request()->routeIs('lideres.congregacao.index')),
                $this->childLink('Cadastrar jovem', 'lideres.congregacao.jovens.create', request()->routeIs(
                    'lideres.congregacao.jovens.create',
                    'lideres.congregacao.jovens.store',
                    'lideres.congregacao.jovens.edit',
                    'lideres.congregacao.jovens.update',
                    'lideres.congregacao.jovens.send-password-reset',
                ), $u?->can('igrejasProvisionYouth') === true),
                $this->childLink('Exportar jovens (CSV)', 'lideres.congregacao.jovens.export', request()->routeIs('lideres.congregacao.jovens.export'), $u?->can('igrejasProvisionYouth') === true),
                $this->childLink('Pedidos à diretoria', 'lideres.igrejas.requests.index', request()->routeIs('lideres.igrejas.requests.index', 'lideres.igrejas.requests.show'), $u?->can('igrejas.requests.submit') === true),
                $this->childLink('Novo pedido', 'lideres.igrejas.requests.create', request()->routeIs('lideres.igrejas.requests.create', 'lideres.igrejas.requests.store', 'lideres.igrejas.requests.edit', 'lideres.igrejas.requests.update', 'lideres.igrejas.requests.submit'), $u?->can('igrejas.requests.submit') === true),
            ], fn ($c) => $c !== null));

            $igrejaGroupVisible = count($igrejaChildren) > 0;
            if ($igrejaGroupVisible) {
                $anyIgrejaActive = request()->routeIs(
                    'lideres.congregacao.*',
                    'lideres.igrejas.requests.*',
                );
                $categories[] = [
                    'id' => 'igreja',
                    'label' => 'Igreja e juventude',
                    'entries' => [
                        [
                            'type' => 'group',
                            'alpineKey' => 'liderNavIgreja',
                            'label' => 'Minha unidade',
                            'defaultOpen' => $anyIgrejaActive,
                            'icon' => ['kind' => 'icon', 'name' => 'church'],
                            'active' => $anyIgrejaActive,
                            'children' => $igrejaChildren,
                        ],
                    ],
                ];
            }
        }

        if ($this->mod('Bible') && $this->routeHas('lideres.bible.index')) {
            $bibleReadingActive = request()->routeIs(
                'lideres.bible.index',
                'lideres.bible.read',
                'lideres.bible.book',
                'lideres.bible.chapter',
                'lideres.bible.verse',
                'lideres.bible.interlinear',
                'lideres.bible.favorites',
                'lideres.bible.favorites.batch',
                'lideres.bible.favorites.toggle',
                'lideres.bible.favorites.destroy',
            );
            $biblePlansActive = request()->routeIs(
                'lideres.bible.plans.*',
                'lideres.bible.reader*',
                'lideres.bible.search',
                'lideres.bible.api.search',
            );
            $bibleOpen = $bibleReadingActive || $biblePlansActive;

            $readingItems = array_values(array_filter([
                $this->childLink('Versões e início', 'lideres.bible.index', request()->routeIs('lideres.bible.index')),
                $this->childLink('Leitor', 'lideres.bible.read', request()->routeIs('lideres.bible.read', 'lideres.bible.book', 'lideres.bible.chapter'), $this->routeHas('lideres.bible.read')),
                $this->childLink('Interlinear', 'lideres.bible.interlinear', request()->routeIs('lideres.bible.interlinear'), $this->routeHas('lideres.bible.interlinear')),
                $this->childLink('Favoritos', 'lideres.bible.favorites', request()->routeIs(
                    'lideres.bible.favorites',
                    'lideres.bible.favorites.batch',
                    'lideres.bible.favorites.toggle',
                    'lideres.bible.favorites.destroy',
                ), $this->routeHas('lideres.bible.favorites')),
            ], fn ($c) => $c !== null));

            $planItems = array_values(array_filter([
                $this->childLink('Os meus planos', 'lideres.bible.plans.index', request()->routeIs(
                    'lideres.bible.plans.index',
                    'lideres.bible.plans.show',
                    'lideres.bible.plans.preview',
                    'lideres.bible.plans.recalculate',
                    'lideres.bible.plans.pdf',
                    'lideres.bible.plans.subscribe',
                    'lideres.bible.reader*',
                ), $this->routeHas('lideres.bible.plans.index')),
                $this->childLink('Catálogo', 'lideres.bible.plans.catalog', request()->routeIs('lideres.bible.plans.catalog'), $this->routeHas('lideres.bible.plans.catalog')),
                $this->childLink('Busca na Bíblia', 'lideres.bible.search', request()->routeIs('lideres.bible.search', 'lideres.bible.api.search'), $this->routeHas('lideres.bible.search')),
            ], fn ($c) => $c !== null));

            $categories[] = [
                'id' => 'biblia',
                'label' => 'Bíblia e estudo',
                'entries' => [
                    [
                        'type' => 'group',
                        'alpineKey' => 'liderNavBible',
                        'label' => 'Bíblia e planos',
                        'defaultOpen' => $bibleOpen,
                        'icon' => ['kind' => 'icon', 'name' => 'book-bible'],
                        'active' => $bibleOpen,
                        'visible' => true,
                        'subsections' => array_values(array_filter([
                            count($readingItems) ? ['label' => 'Leitura', 'items' => $readingItems] : null,
                            count($planItems) ? ['label' => 'Planos', 'items' => $planItems] : null,
                        ])),
                    ],
                ],
            ];
        }

        $comunicacao = $this->filterEntries(array_values(array_filter([
            $this->linkEntry(
                label: 'Avisos JUBAF',
                route: 'lideres.avisos.index',
                active: request()->routeIs('lideres.avisos.*'),
                icon: ['kind' => 'module', 'module' => 'Avisos'],
                visible: $this->mod('Avisos') && $this->routeHas('lideres.avisos.index'),
            ),
            $this->linkEntry(
                label: 'Blog JUBAF',
                route: 'lideres.blog.index',
                active: request()->routeIs('lideres.blog.*'),
                icon: ['kind' => 'module', 'module' => 'Blog'],
                visible: $this->mod('Blog') && $this->routeHas('lideres.blog.index'),
            ),
            $this->linkEntry(
                label: 'Notificações',
                route: 'lideres.notificacoes.index',
                active: request()->routeIs('lideres.notificacoes.*'),
                icon: ['kind' => 'icon', 'name' => 'bell'],
                visible: $this->mod('Notificacoes') && $this->routeHas('lideres.notificacoes.index'),
            ),
            $this->linkEntry(
                label: 'Chat',
                route: 'lideres.chat.page',
                active: request()->routeIs('lideres.chat.*'),
                icon: ['kind' => 'icon', 'name' => 'messages'],
                visible: $this->mod('Chat') && $this->routeHas('lideres.chat.page'),
            ),
        ])));

        if (count($comunicacao) > 0) {
            $categories[] = [
                'id' => 'comunicacao',
                'label' => 'Comunicação',
                'entries' => $comunicacao,
            ];
        }

        $organizacaoEntries = [];

        if ($this->mod('Calendario') && $this->routeHas('lideres.calendario.index') && $u?->can('calendario.participate')) {
            $organizacaoEntries[] = $this->linkEntry(
                label: 'Eventos',
                route: 'lideres.calendario.index',
                active: request()->routeIs('lideres.calendario.*'),
                icon: ['kind' => 'module', 'module' => 'Calendario'],
                visible: true,
            );
        }

        if ($this->mod('Financeiro') && $this->routeHas('lideres.financeiro.minhas-contas') && $u?->can('financeiro.minhas_contas.view')) {
            $organizacaoEntries[] = $this->linkEntry(
                label: 'Minhas contas',
                route: 'lideres.financeiro.minhas-contas',
                active: request()->routeIs('lideres.financeiro.*'),
                icon: ['kind' => 'module', 'module' => 'Financeiro'],
                visible: true,
            );
        }

        if ($this->mod('Secretaria') && $this->routeHas('lideres.secretaria.index') && $u?->can('secretaria.minutes.view')) {
            $secActive = request()->routeIs('lideres.secretaria.*');
            $secChildren = array_values(array_filter([
                $this->childLink('Início', 'lideres.secretaria.index', request()->routeIs('lideres.secretaria.index')),
                $this->childLink('Atas', 'lideres.secretaria.atas.index', request()->routeIs('lideres.secretaria.atas.*'), $this->routeHas('lideres.secretaria.atas.index')),
                $this->childLink('Convocatórias', 'lideres.secretaria.convocatorias.index', request()->routeIs('lideres.secretaria.convocatorias.*'), $this->routeHas('lideres.secretaria.convocatorias.index')),
                $this->childLink('Documentos', 'lideres.secretaria.documentos.index', request()->routeIs('lideres.secretaria.documentos.*'), $this->routeHas('lideres.secretaria.documentos.index')),
            ], fn ($c) => $c !== null));

            $organizacaoEntries[] = [
                'type' => 'group',
                'alpineKey' => 'liderNavSecretaria',
                'label' => 'Secretaria',
                'defaultOpen' => $secActive,
                'icon' => ['kind' => 'icon', 'name' => 'file-lines'],
                'active' => $secActive,
                'visible' => true,
                'children' => $secChildren,
            ];
        }

        $organizacaoEntries = $this->filterEntries($organizacaoEntries);
        if (count($organizacaoEntries) > 0) {
            $categories[] = [
                'id' => 'organizacao',
                'label' => 'Organização',
                'entries' => $organizacaoEntries,
            ];
        }

        $talento = $this->filterEntries(array_values(array_filter([
            $this->linkEntry(
                label: 'Banco de talentos',
                route: 'lideres.talentos.profile.edit',
                active: request()->routeIs('lideres.talentos.profile.*', 'lideres.talentos.assignments.respond'),
                icon: ['kind' => 'module', 'module' => 'Talentos'],
                visible: $this->mod('Talentos') && $this->routeHas('lideres.talentos.profile.edit') && $u?->can('talentos.profile.edit'),
            ),
            $this->linkEntry(
                label: 'Validar talentos',
                route: 'lideres.talentos.validation.index',
                active: request()->routeIs('lideres.talentos.validation.*'),
                icon: ['kind' => 'icon', 'name' => 'check-double'],
                visible: $this->mod('Talentos') && $this->routeHas('lideres.talentos.validation.index') && $u?->can('paineljovens.talentos.validate'),
            ),
            $this->linkEntry(
                label: 'Juventude (métricas)',
                route: 'lideres.juventude.metrics',
                active: request()->routeIs('lideres.juventude.*'),
                icon: ['kind' => 'icon', 'name' => 'chart-simple'],
                visible: $this->mod('PainelJovens') && $this->routeHas('lideres.juventude.metrics') && $u?->can('paineljovens.dashboard.metrics'),
            ),
        ])));

        if (count($talento) > 0) {
            $categories[] = [
                'id' => 'talento',
                'label' => 'Talento e impacto',
                'entries' => $talento,
            ];
        }

        $categories[] = [
            'id' => 'conta',
            'label' => 'Conta',
            'entries' => $this->filterEntries([
                $this->linkEntry(
                    label: 'Perfil',
                    route: 'lideres.profile.index',
                    active: request()->routeIs('lideres.profile.*'),
                    icon: ['kind' => 'icon', 'name' => 'user-gear'],
                    visible: $this->routeHas('lideres.profile.index'),
                ),
                $this->linkEntry(
                    label: 'Site público',
                    route: 'homepage',
                    active: false,
                    icon: ['kind' => 'icon', 'name' => 'arrow-up-right-from-square'],
                    visible: $this->routeHas('homepage'),
                    external: true,
                ),
            ]),
        ];

        return array_values(array_filter($categories, fn (array $cat) => count($cat['entries']) > 0));
    }

    /**
     * @return list<array{label: string, href: string, active: bool}>
     */
    private function buildQuickLinks(?Authenticatable $user): array
    {
        $u = $user instanceof User ? $user : null;
        $out = [];

        $push = function (string $label, string $route, bool $visible, bool $active) use (&$out): void {
            if (! $visible || ! $this->routeHas($route)) {
                return;
            }
            $out[] = [
                'label' => $label,
                'href' => route($route),
                'active' => $active,
            ];
        };

        $push('Início', 'lideres.dashboard', true, request()->routeIs('lideres.dashboard', 'lideres.dashboard.index', 'lideres.dashboard.filtros', 'lideres.dashboard.estatisticas'));
        $push('Congregação', 'lideres.congregacao.index', (bool) ($this->mod('Igrejas')), request()->routeIs('lideres.congregacao.index'));
        $push('Cadastrar jovem', 'lideres.congregacao.jovens.create', (bool) ($this->mod('Igrejas') && $u?->can('igrejasProvisionYouth')), request()->routeIs('lideres.congregacao.jovens.*'));
        $push('Eventos', 'lideres.calendario.index', (bool) ($this->mod('Calendario') && $u?->can('calendario.participate')), request()->routeIs('lideres.calendario.*'));
        $push('Chat', 'lideres.chat.page', $this->mod('Chat'), request()->routeIs('lideres.chat.*'));
        $push('Bíblia', 'lideres.bible.index', $this->mod('Bible'), request()->routeIs('lideres.bible.*'));
        $push('Secretaria', 'lideres.secretaria.index', (bool) ($this->mod('Secretaria') && $u?->can('secretaria.minutes.view')), request()->routeIs('lideres.secretaria.*'));
        $push('Avisos', 'lideres.avisos.index', $this->mod('Avisos'), request()->routeIs('lideres.avisos.*'));
        $push('Minhas contas', 'lideres.financeiro.minhas-contas', (bool) ($this->mod('Financeiro') && $u?->can('financeiro.minhas_contas.view')), request()->routeIs('lideres.financeiro.*'));
        $push('Pedidos', 'lideres.igrejas.requests.index', (bool) ($this->mod('Igrejas') && $u?->can('igrejas.requests.submit')), request()->routeIs('lideres.igrejas.requests.*'));

        return array_slice($out, 0, 10);
    }

    private function resolvePageEyebrow(): string
    {
        $pairs = [
            ['lideres.dashboard*', 'Principal'],
            ['lideres.congregacao*', 'Igreja e juventude'],
            ['lideres.igrejas.requests*', 'Igreja e juventude'],
            ['lideres.bible*', 'Bíblia e estudo'],
            ['lideres.avisos*', 'Comunicação'],
            ['lideres.blog*', 'Comunicação'],
            ['lideres.notificacoes*', 'Comunicação'],
            ['lideres.chat*', 'Comunicação'],
            ['lideres.calendario*', 'Organização'],
            ['lideres.financeiro*', 'Organização'],
            ['lideres.secretaria*', 'Organização'],
            ['lideres.talentos*', 'Talento e impacto'],
            ['lideres.juventude*', 'Talento e impacto'],
            ['lideres.profile*', 'Conta'],
        ];

        foreach ($pairs as [$pattern, $label]) {
            if (request()->routeIs($pattern)) {
                return $label;
            }
        }

        return 'Painel de líderes';
    }

    private function resolvePageTitle(): string
    {
        $name = request()->route()?->getName() ?? '';

        $titles = [
            'lideres.dashboard' => 'Início',
            'lideres.dashboard.index' => 'Início',
            'lideres.dashboard.filtros' => 'Filtros do painel',
            'lideres.dashboard.estatisticas' => 'Estatísticas',
            'lideres.profile.index' => 'Perfil',
            'lideres.profile.update' => 'Perfil',
            'lideres.congregacao.index' => 'Congregação',
            'lideres.congregacao.jovens.create' => 'Cadastrar jovem',
            'lideres.congregacao.jovens.export' => 'Exportar jovens',
            'lideres.igrejas.requests.index' => 'Pedidos à diretoria',
            'lideres.igrejas.requests.create' => 'Novo pedido',
            'lideres.notificacoes.index' => 'Notificações',
            'lideres.chat.page' => 'Chat',
            'lideres.chat.index' => 'Chat',
            'lideres.calendario.index' => 'Eventos',
            'lideres.financeiro.minhas-contas' => 'Minhas contas',
            'lideres.secretaria.index' => 'Secretaria',
            'lideres.secretaria.atas.index' => 'Atas',
            'lideres.secretaria.convocatorias.index' => 'Convocatórias',
            'lideres.secretaria.documentos.index' => 'Documentos',
            'lideres.talentos.profile.edit' => 'Banco de talentos',
            'lideres.talentos.validation.index' => 'Validar talentos',
            'lideres.juventude.metrics' => 'Métricas da juventude',
            'lideres.avisos.index' => 'Avisos',
            'lideres.blog.index' => 'Blog',
            'lideres.bible.index' => 'Bíblia',
            'lideres.bible.read' => 'Leitor bíblico',
            'lideres.bible.interlinear' => 'Interlinear',
            'lideres.bible.favorites' => 'Favoritos',
            'lideres.bible.plans.index' => 'Planos de leitura',
            'lideres.bible.plans.catalog' => 'Catálogo de planos',
            'lideres.bible.search' => 'Busca na Bíblia',
        ];

        if (isset($titles[$name])) {
            return $titles[$name];
        }

        if (str_starts_with($name, 'lideres.bible.')) {
            return 'Bíblia e estudo';
        }

        if (str_starts_with($name, 'lideres.secretaria.')) {
            return 'Secretaria';
        }

        if (str_starts_with($name, 'lideres.igrejas.requests.')) {
            return 'Pedidos à diretoria';
        }

        return 'Painel de líderes';
    }

    private function routeHas(string $name): bool
    {
        return Route::has($name);
    }

    private function mod(string $module): bool
    {
        return module_enabled($module);
    }

    /**
     * @param  list<array<string, mixed>|null>  $entries
     * @return list<array<string, mixed>>
     */
    private function filterEntries(array $entries): array
    {
        return array_values(array_filter($entries, function ($e) {
            if ($e === null) {
                return false;
            }
            if (($e['type'] ?? '') === 'group') {
                return true;
            }

            return (bool) ($e['visible'] ?? false);
        }));
    }

    /**
     * @return array<string, mixed>|null
     */
    private function linkEntry(
        string $label,
        string $route,
        bool $active,
        array $icon,
        bool $visible,
        bool $external = false,
    ): ?array {
        if (! $visible || ! $this->routeHas($route)) {
            return null;
        }

        return [
            'type' => 'link',
            'label' => $label,
            'href' => route($route),
            'active' => $active,
            'icon' => $icon,
            'visible' => true,
            'external' => $external,
        ];
    }

    /**
     * @return array{label: string, href: string, active: bool}|null
     */
    private function childLink(string $label, string $route, bool $active, bool $can = true): ?array
    {
        if (! $can) {
            return null;
        }

        if (! $this->routeHas($route)) {
            return null;
        }

        return [
            'label' => $label,
            'href' => route($route),
            'active' => $active,
        ];
    }
}
