---
name: padronizar-vistas-painel-jovens
overview: Padronizar 100% das views do painel de jovens para o mesmo padrão visual e estrutural do arquivo de referência `Modules/Bible/resources/views/paineljovens/plans/dashboard.blade.php`, incluindo layout, hero, tokens e componentes compartilhados.
todos:
    - id: map-layout-contract
      content: Garantir contrato estrutural único em todas as views jovens (extends/layout, jovens_content, breadcrumbs).
      status: completed
    - id: normalize-bible-theme
      content: Remover tokens antigos em Bible jovens (teal/violet/fuchsia/indigo/slate) e alinhar ao hero blue/gray da referência.
      status: completed
    - id: normalize-shared-components
      content: Padronizar componentes compartilhados do PainelJovens/Talentos para não reintroduzir paletas antigas.
      status: completed
    - id: normalize-jovens-pages
      content: Padronizar páginas jovens fora da pasta paineljovens (dashboard, profile, devotionals, wallet) para o mesmo padrão visual.
      status: completed
    - id: final-sweep-and-tests
      content: Executar varredura final de classes/tokens e rodar testes focados no painel jovens.
      status: completed
isProject: false
---

# Padronização Total do Painel Jovens

## Objetivo

Unificar todas as telas do painel jovens para um único padrão: layout `paineljovens::layouts.jovens`, conteúdo em `jovens_content`, visual gray/blue e hero de gradiente no estilo da referência.

## Referência canônica

- Arquivo base: [`c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/plans/dashboard.blade.php`](c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/plans/dashboard.blade.php)
- Layout herdado para Bible: [`c:/laragon/www/JUBAF/Modules/Bible/resources/views/components/layouts/panel.blade.php`](c:/laragon/www/JUBAF/Modules/Bible/resources/views/components/layouts/panel.blade.php)
- Layout global jovens: [`c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/layouts/jovens.blade.php`](c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/layouts/jovens.blade.php)

## Escopo de correção

- Views `paineljovens` em módulos: Avisos, Bible, Blog, Calendario, Chat, Igrejas, Notificacoes, Talentos.
- Views do módulo PainelJovens que são rotas `jovens.*` mesmo fora da pasta `paineljovens`.
- Partials/componentes compartilhados que ainda forçam tokens antigos (teal/violet/indigo/slate).

## Estratégia técnica

- Consolidar contrato estrutural em todas as telas:
    - `@extends('paineljovens::layouts.jovens')` (ou via layout intermediário que estende ele)
    - `@section('jovens_breadcrumbs')` quando aplicável
    - `@section('jovens_content')` obrigatório em páginas completas
- Unificar linguagem visual para o padrão da referência:
    - Hero gradiente `from-blue-700 via-blue-800 to-gray-900`
    - superfícies e bordas em `gray-*`
    - ações primárias em `blue-*`
    - remover variações antigas (`teal-*`, `violet-*`, `fuchsia-*`, `indigo-*`, `slate-*`) onde forem estilo base de layout
- Padronizar componentes compartilhados para não reintroduzir tema antigo.

## Arquivos prioritários (desvios já identificados)

- Bible tokens/tema antigo:
    - [`c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/partials/jovens-bible-styles.blade.php`](c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/partials/jovens-bible-styles.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/bible/search.blade.php`](c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/bible/search.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/plans/congratulations.blade.php`](c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/plans/congratulations.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/plans/pdf.blade.php`](c:/laragon/www/JUBAF/Modules/Bible/resources/views/paineljovens/plans/pdf.blade.php) (versão print, padronizar paleta mantendo objetivo PDF)
- Shell/componentes com tokens residuais:
    - [`c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/components/ui/jovens/status-pill.blade.php`](c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/components/ui/jovens/status-pill.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/components/ui/jovens/ticket-card.blade.php`](c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/components/ui/jovens/ticket-card.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/profile/index.blade.php`](c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views/profile/index.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/Talentos/resources/views/painel/partials/inscription-form.blade.php`](c:/laragon/www/JUBAF/Modules/Talentos/resources/views/painel/partials/inscription-form.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/Talentos/resources/views/painel/partials/assignment-invite-actions.blade.php`](c:/laragon/www/JUBAF/Modules/Talentos/resources/views/painel/partials/assignment-invite-actions.blade.php)

## Validação

- Fazer varredura final em `Modules/**/resources/views/**/paineljovens/**/*.blade.php` para garantir ausência de tokens antigos no estilo base.
- Revisar também páginas `jovens.*` fora de `paineljovens` (dashboard, perfil, devocionais, wallet) para manter o mesmo padrão visual.
- Rodar testes de painel jovens e integrações relacionadas para evitar regressões de renderização/rotas.
