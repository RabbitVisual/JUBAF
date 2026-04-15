# Módulo Bible (JUBAF)

**Bíblia digital:** versões, livros, capítulos e versículos; leitura pública; API `v1/bible`; painel `/admin` com papel Spatie **`super-admin`** (importação, planos de leitura, Strong’s, áudio por capítulo); área autenticada (planos de leitura, favoritos, busca sob o prefixo `social/bible`).

## O que ficou integrado no JUBAF

| Área | Onde está definido |
|------|---------------------|
| **Site público** | Módulo [`routes/public-site.php`](routes/public-site.php) — prefixo `biblia` (`bible.public.*`), `PublicBibleController`, `InterlinearController` |
| **API** | [`routes/api.php`](../../routes/api.php) — `api/v1/bible/*` |
| **Admin Bíblia** | [`routes/admin.php`](../../routes/admin.php) — URL `/admin/biblia-digital`, nomes de rota `admin.bible.*` (versões, planos, relatórios, estudo: Strong’s, comentários, refs.); middleware `role:super-admin` |
| **Utilizador autenticado** | [`Modules/Bible/routes/web.php`](routes/web.php) — `bibles` (comparar), `social/bible/plans` (`member.bible.*`), favoritos |

Vistas do módulo: namespace **`bible::`** (ex.: `bible::public.chapter`, `bible::admin.bible.index`). O **shell** do painel admin vem do módulo **Admin** (`admin::layouts.admin`); as páginas da área Bíblia usam o componente **`x-bible::admin.layout`** e o submenu **`bible::components.admin.nav`**. Helpers globais: `bible_admin_route()`, `bible_route_is()` (prefixos `admin.bible` / `diretoria.bible` conforme o contexto). O **build Vite** é o da **raiz** do projeto (`resources/css`, `resources/js`).

## Comentários e referências cruzadas (estudo)

Importadores: `php artisan bible:import-cross-refs`, `php artisan bible:import-commentary`. **Só devem ser importados textos em domínio público ou com licença explícita para redistribuição.** O repositório mantém formato, migrações e comandos — não conteúdo protegido sem autorização.

## Layout do painel autenticado

As vistas em `resources/views/memberpanel/` usam [`bible::components.layouts.panel`](resources/views/components/layouts/panel.blade.php), que estende [`auth::layouts.panel`](../../Modules/Auth/resources/views/layouts/panel.blade.php) — substitui referências legadas a `memberpanel::` de outro projeto.

## Modelos e dados

Modelos em `app/Models/` (ex.: `BibleVersion`, `Book`, `Chapter`, `Verse`, planos, interlinear, Strong’s). Migrações em [`database/migrations/`](database/migrations/). Comandos de importação em [`app/Console/Commands/`](app/Console/Commands/) (registados em `BibleServiceProvider`).

## Permissões e acesso

- **Painel admin da Bíblia (`admin.bible.*`):** definido em [`routes/admin.php`](../../routes/admin.php) dentro do grupo com middleware **`role:super-admin`** (importação, planos, estudo avançado, etc.).
- **`bible.study.lexicon.overlay`:** painel de liderança — campos editoriais PT do léxico (`painel/lideranca/biblia/estudo/lexico`).
- **Rotas `member.bible.*`:** middleware `web` + `auth` + `verified` (definidas no módulo).

## Documentação global

- `CHANGLOG.md`, `PLANOJUBAF/`, `AGENTS.md`, `docs/module-icons.md` (ícone PNG do módulo em `public/modules/icons/` quando aplicável).

## Testes

- [`tests/`](tests/) no módulo (quando existirem); alinhar novos testes a PHPUnit na raiz do projecto.
