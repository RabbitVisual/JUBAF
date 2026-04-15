---
name: Blog JUBAF upgrade integral
overview: "Upgrade ponta a ponta do módulo Blog: UI pública (hero, sidebar, SEO), uniformidade visual com painéis e site institucional, auditoria/remoção de legado, integrações condicionais com módulos JUBAF (prioridade Bible na criação de posts), e práticas Laravel/SEO. Execução em fases para reduzir risco."
todos:
  - id: audit-legacy
    content: Auditar e remover/arquivar legado Blog (vite.module sem assets, master.blade não referenciado, referências mortas); alinhar build ao app principal.
    status: completed
  - id: design-tokens-blog
    content: Definir partials Blade compartilhados (cartão de post, meta autor/data) para blog público + painéis, usando SiteBranding e paleta azul/slate da homepage.
    status: completed
  - id: fix-theme-toggle
    content: Consolidar toggle de tema em blog.blade.php com dark-mode.js (remover script duplicado, ids theme-icon-* ou onclick único).
    status: completed
  - id: footer-branding
    content: Rodapé blog com logo/tagline SiteBranding e links (RSS, site, contato/Homepage).
    status: completed
  - id: controller-show-sidebar
    content: Estender BlogController::show com latestPosts, categorias/tags para sidebar; evitar N+1.
    status: completed
  - id: show-hero-sidebar
    content: Refatorar show.blade.php hero full-width + grelha 8+4, remover imagem destacada duplicada, manter JSON-LD/H1.
    status: completed
  - id: listings-unify
    content: Unificar index/category/tag/search com partials de cartão e sidebar opcional.
    status: completed
  - id: panels-readers-ux
    content: Alinhar paineljovens/painellider/paineldiretoria leitura de posts aos novos partials (mesma hierarquia visual, não layout público idêntico).
    status: completed
  - id: bible-editor-assist
    content: "Integrar assistência Bíblia no editor de post (admin/diretoria): atalho para busca/citação via API ou rotas existentes do módulo Bible, sem acoplar domínio indevido."
    status: completed
  - id: cross-module-widgets
    content: Bloco opcional 'Recursos JUBAF' (links com module_enabled) para Homepage, Calendario, Avisos, etc.; Notificacoes/Chat só se houver API estável.
    status: completed
  - id: tests-seo
    content: Testes BlogFullSuite + verificação manual SEO (canonical, OG, alt, uma H1).
    status: completed
isProject: false
---

# Upgrade integral do módulo Blog JUBAF

## Objetivo

Um blog **coerente em todo o ecossistema**: leitor percebe a mesma identidade no **site público**, nos **painéis** (Jovens, Líder, Diretoria, Admin) e na **homepage**, com código **alinhado ao projeto**, **sem ficheiros mortos** desnecessários e com **integrações reais** (não apenas nomes de módulos) onde fizer sentido técnico.

## Princípios (Laravel + SEO)

- **Controller fino, dados eager-loaded** nas queries públicas; validação e policies já existentes nos fluxos admin.
- **URLs estáveis**, **canonical**, **um H1** por artigo, meta description por página, **alt** em imagens (título ou excerto).
- **Performance**: LCP na hero (`fetchpriority`/eager só onde necessário); thumbs com `loading="lazy"`.
- **Modularidade**: `module_enabled('X')` + `Route::has()` antes de links cruzados para não quebrar instalações parciais.

---

## Fase 0 — Auditoria de legado (Blog)

| Item                                                                                                                                   | Situação provável                                | Ação                                                            |
| -------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------ | --------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| [`Modules/Blog/vite.config.js`](Modules/Blog/vite.config.js)                                                                           | Aponta para `resources/assets/sass               | js` **inexistentes**                                            | Remover build morto ou documentar; o blog público já usa [`@vite(['resources/css/app.css', 'resources/js/app.js'])`](Modules/Blog/resources/views/layouts/blog.blade.php) do app raiz — **não duplicar** pipeline. |
| [`Modules/Blog/resources/views/components/layouts/master.blade.php`](Modules/Blog/resources/views/components/layouts/master.blade.php) | **Sem referências** `blog::components` no código | Candidato a remoção após confirmação grep; era scaffold antigo. |
| `package.json` / scripts do módulo Blog                                                                                                | Verificar se usados no deploy                    | Alinhar ao monorepo Vite principal.                             |

---

## Fase 1 — UI pública (mantém e expande o plano anterior)

- **Tema**: um único caminho com [`resources/js/dark-mode.js`](resources/js/dark-mode.js); remover handler duplicado em [`blog.blade.php`](Modules/Blog/resources/views/layouts/blog.blade.php).
- **Branding**: header já usa `SiteBranding`; **footer** com logo default + tagline + links (RSS, `route('homepage')`, `route('contato')` quando existir).
- **Artigo** [`show.blade.php`](Modules/Blog/resources/views/public/show.blade.php): hero full-width com `featured_image`, overlay, **grelha 8+4**, sidebar com últimas publicações + tags/categorias; **não** repetir a mesma imagem em bloco abaixo.
- **Listagens**: partials partilhados (`post-card`, meta linha) em `Modules/Blog/resources/views/public/partials/`.
- **RSS/sitemap**: só ajustes de copy/branding se necessário.

---

## Fase 2 — Uniformidade “mesmo blog” (público vs painéis)

**Não** forçar o layout `blog::layouts.blog` dentro dos painéis (áreas autenticadas com sidebar própria).

**Sim** reutilizar **os mesmos partials de cartão e tipografia** (título, categoria, data, excerpt, thumbnail) entre:

- [`paineljovens/index|show`](Modules/Blog/resources/views/paineljovens/)
- [`painellider/index|show`](Modules/Blog/resources/views/painellider/)
- Leitura diretoria [`paineldiretoria/show`](Modules/Blog/resources/views/paineldiretoria/show.blade.php) quando aplicável

Tokens: **azul + slate** e **SiteBranding** alinhados à [Homepage](Modules/Homepage) e ao header do blog, em vez de estilos divergentes por ficheiro.

---

## Fase 3 — Integrações com outros módulos (priorizada)

### Prioridade alta — **Bible** (ajuda à criação de postagem)

- Explorar rotas/API existentes em `Modules/Bible` (ex.: prefixo `api/v1/bible`).
- Na **edição/criação** de post (admin + diretoria, onde está o Quill/editor): painel lateral ou modal **“Consultar Bíblia”** que chama API interna (fetch autenticado) e permite **inserir referência ou trecho** no editor — sem duplicar lógica de versículos no Blog (apenas HTTP + apresentação).
- Respeitar `module_enabled('Bible')` e permissões.

### Prioridade média — **Homepage**

- CTAs já possíveis: newsletter/contato; garantir links consistentes no sidebar público do blog.

### Prioridade média — **Calendario, Avisos, Igrejas**

- Secção opcional “Agenda / Avisos / Igrejas” na sidebar ou rodapé do blog **apenas** com `module_enabled` + rotas nomeadas existentes (sem queries pesadas no `BlogController` até definir necessidade).

### Prioridade baixa / condicional — **Notificacoes, Chat, Financeiro, Talentos, Secretaria, Permisao**

- **Não** acoplar regras de negócio desses módulos no núcleo do Blog sem requisito claro.
- Onde fizer sentido: **links de navegação** no rodapé ou “Recursos” para utilizadores autenticados nos painéis (já há padrões em sidebars — alinhar texto/ícones).

### Já existente — **BlogIntegrationController**

- Manter [`BlogIntegrationController`](Modules/Blog/app/Http/Controllers/BlogIntegrationController.php) e relatórios mensais; rever apenas se integração visual nova afeta geração de conteúdo.

---

## Fase 4 — Testes e verificação

- [`Modules/Blog/tests/Feature/BlogFullSuiteTest.php`](Modules/Blog/tests/Feature/BlogFullSuiteTest.php) após alterações de controller/views.
- Smoke manual: post com imagem, sem imagem, comentários, dark mode, link RSS.

---

## Fora de escopo imediato (evitar over-engineering)

- Reescrever **API REST** do Blog inteira só para integrações.
- Notificações push automáticas a cada post sem especificação de produto.
- Unificar **Admin Laravel** e **painel público** num único layout HTML (objetivos UX diferentes).

---

## Ficheiros centrais (referência)

| Área                | Ficheiros                                                                                                                                                                                                                                             |
| ------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Layout público      | [`Modules/Blog/resources/views/layouts/blog.blade.php`](Modules/Blog/resources/views/layouts/blog.blade.php)                                                                                                                                          |
| Controllers público | [`BlogController.php`](Modules/Blog/app/Http/Controllers/BlogController.php)                                                                                                                                                                          |
| Artigo / listagens  | [`public/show.blade.php`](Modules/Blog/resources/views/public/show.blade.php), `index`, `category`, `tag`, `search`                                                                                                                                   |
| Painéis             | [`BlogPainelController.php`](Modules/Blog/app/Http/Controllers/BlogPainelController.php), views `paineljovens`, `painellider`, `paineldiretoria`                                                                                                      |
| Editor posts        | [`admin/edit.blade.php`](Modules/Blog/resources/views/admin/edit.blade.php), [`paineldiretoria/edit.blade.php`](Modules/Blog/resources/views/paineldiretoria/edit.blade.php), [`resources/js/blog-editor.js`](resources/js/blog-editor.js) (app raiz) |

---

## Nota sobre documentação externa

As referências “75 ideias de blogs”, SEO liveSEO e Laravel best practices orientam **conteúdo e qualidade** (títulos, links internos, E-E-A-T); a implementação técnica segue a estrutura acima e o código existente JUBAF.
