<p align="center">
  <img src="../public/images/logo/logo.png" alt="JUBAF" width="280">
</p>

# Módulos da plataforma JUBAF

Este documento descreve **cada módulo Laravel** presente em `Modules/`, no estado atual do repositório.  
A arquitetura segue **[nwidart/laravel-modules](https://github.com/nWidart/laravel-modules)** (um `module.json` por pasta, providers, rotas e recursos isolados).

> **Nota:** Versões anteriores deste ficheiro continham descrições de outro produto (gestão municipal, demandas, infraestruturas, etc.). **Esses conteúdos não se aplicam ao projeto JUBAF** e foram substituídos por esta documentação.

---

## Índice

1. [Visão geral da arquitetura](#visão-geral-da-arquitetura)
2. [Tabela dos módulos](#tabela-dos-módulos)
3. [Detalhe por módulo](#detalhe-por-módulo)
4. [Painéis e papéis (JUBAF)](#painéis-e-papéis-jubaf)
5. [Integração entre módulos](#integração-entre-módulos)
6. [Base de dados e migrations (squash)](#base-de-dados-e-migrations-squash)
7. [Estrutura típica de uma pasta `Modules/Nome`](#estrutura-típica-de-uma-pasta-modulesnome)

---

## Visão geral da arquitetura

- **Monólito modular:** a aplicação principal (`app/`, `routes/`, `resources/`) regista utilizadores, políticas e rotas globais; cada **módulo** encapsula um domínio (avisos, bíblia, blog, …).
- **Ativação:** módulos podem ser consultados com helpers como `module_enabled('Nome')` antes de registar rotas condicionais (ex.: painel Diretoria).
- **Permissões:** [Spatie Laravel Permission](https://github.com/spatie/laravel-permission) + papéis JUBAF em `config/jubaf_roles.php` e `App\Support\JubafRoleRegistry`.
- **UI:** Blade + Tailwind + Alpine; painéis com layouts dedicados (`PainelDiretoria`, `PainelJovens`, `PainelLider`, etc.).

---

## Tabela dos módulos

| Módulo (`alias`) | Responsabilidade principal |
|-------------------|---------------------------|
| **Admin** `admin` | Núcleo de área `/admin` (shell, assets e integração com o painel administrativo global). |
| **Avisos** `avisos` | Avisos, banners e CTAs no site e nos painéis; audiência por igreja e políticas por perfil. |
| **Bible** `bible` | Bíblia digital (livros, capítulos, versículos, planos, favoritos) e integração com homepage e site. |
| **Blog** `blog` | Artigos, categorias, tags, RSS e conteúdo editorial para o site JUBAF. |
| **Calendario** `calendario` | Calendário institucional de eventos e datas relevantes. |
| **Chat** `chat` | Mensagens e atendimento/chat institucional (configuração e UI no padrão do projeto). |
| **Financeiro** `financeiro` | Base do domínio financeiro (estrutura extensível para lançamentos e relatórios). |
| **Gateway** `gateway` | Pagamentos e webhooks (contas de provedor, pagamentos, auditoria de eventos). |
| **Homepage** `homepage` | Página inicial, secções, carrossel, contactos, newsletter e conteúdos configuráveis. |
| **Igrejas** `igrejas` | Congregações e vínculos associacionais (pedidos, pastores, Unijovem, exportações, etc.). |
| **Notificacoes** `notificacoes` | Notificações in-app, e-mail e integração com broadcast. |
| **PainelDiretoria** `paineldiretoria` | *Shell* do painel `/diretoria`: dashboard, perfis, utilizadores, carrossel, devocionais, membros de mesa, integração com Homepage/Avisos/Permissões. |
| **PainelJovens** `paineljovens` | Painel e rotas da experiência **jovens** (layout e funcionalidades do perfil). |
| **PainelLider** `painellider` | Painel e rotas da experiência **líder** (Unijovem / liderança). |
| **Permisao** `permisao` | Suporte a RBAC e hubs de segurança; rotas canónicas em `routes/admin.php` e `routes/diretoria.php`. |
| **Secretaria** `secretaria` | Secretaria associacional: reuniões, atas, convocatórias e arquivo documental (fluxos de submissão/aprovação/publicação). |
| **Talentos** `talentos` | Base para talentos, missões e voluntariado (perfis e oportunidades). |

---

## Detalhe por módulo

### Admin (`Admin` / `admin`)

- **Finalidade:** camada de **superfície administrativa** em `/admin` (layout, recursos partilhados e extensões do painel global), alinhada ao núcleo da aplicação.

### Gateway (`Gateway` / `gateway`)

- **Finalidade:** **pagamentos** e **webhooks** (contas de provedor, registos de pagamento, eventos e auditoria), com rotas e políticas próprias.

### Avisos (`Avisos` / `avisos`)

- **Finalidade:** comunicação institucional com **banners**, **toasts** e conteúdos ricos; visibilidade por datas, tipo, estilo e audiência (incl. filtro por congregações quando aplicável).
- **Superfícies:** vistas públicas (guest), área **Diretoria** (`diretoria.avisos.*`), e painéis **Jovens** / **Líder** / pastor conforme rotas e políticas.
- **Tecnologia:** modelo `Aviso`, policies, serviços de listagem, componente Blade de banner e estatísticas de visualização/clique onde implementado.

### Bible (`Bible` / `bible`)

- **Finalidade:** leitura bíblica no site — versões, navegação livro/capítulo, versículos, favoritos e **planos de leitura**.
- **Integração:** conteúdos podem ser referenciados na homepage e noutras áreas (ex.: devocionais no painel Diretoria).
- **Nota:** marcado como núcleo de leitura espiritual da plataforma.

### Blog (`Blog` / `blog`)

- **Finalidade:** publicação de **notícias e artigos** para a comunidade JUBAF (substitui qualquer copy legado de “prefeitura” por comunicação associacional).
- **Inclui:** categorias, pesquisa, tags, RSS e ferramentas administrativas/diretoria conforme configuração.

### Calendario (`Calendario` / `calendario`)

- **Finalidade:** **agenda institucional** — eventos, reuniões públicas ou internos e visualização alinhada ao calendário da juventude/igrejas.

### Chat (`Chat` / `chat`)

- **Finalidade:** **comunicação por mensagens** (suporte ou canais internos), com UI e rotas no padrão JUBAF.

### Financeiro (`Financeiro` / `financeiro`)

- **Finalidade:** camada base do **domínio financeiro** (estrutura de módulo, rotas e painel admin); preparado para evolução com lançamentos, relatórios e permissões específicas.

### Homepage (`Homepage` / `homepage`)

- **Finalidade:** **página inicial** do site — secções editáveis, carrossel, destaques, formulários de contacto, newsletter e integração com outros módulos (ex.: avisos, bíblia).
- **Painel Diretoria:** rotas `diretoria.homepage.*` (com permissão `homepage.edit`) para configurar conteúdos e leads.

### Igrejas (`Igrejas` / `igrejas`)

- **Finalidade:** cadastro e gestão de **congregações** arroladas à ASBAF — dados institucionais, líderes, pastores, Unijovem, pedidos de alteração e exportações.
- **Público / painéis:** fluxos diferenciados para diretoria e participantes conforme políticas.

### Notificacoes (`Notificacoes` / `notificacoes`)

- **Finalidade:** **notificações** na área autenticada (lista, leitura, criação administrativa), com suporte a **broadcast** (Pusher/Redis) quando configurado.
- **Documentação extra:** ver `Modules/Notificacoes/README.md` e guias de integração na mesma pasta.

### PainelDiretoria (`PainelDiretoria` / `paineldiretoria`)

- **Finalidade:** experiência do **painel da Diretoria JUBAF** em `/diretoria` — dashboard, perfil, **utilizadores**, **carrossel**, **devocionais**, **membros de mesa**, pedidos de alteração de dados sensíveis, e **integração** com Homepage, Avisos, Notificações e hub de segurança (`Permisao`).
- **Rotas canónicas:** definidas em `routes/diretoria.php` (não depender apenas dos ficheiros vazios em `Modules/PainelDiretoria/routes/web.php`).

### PainelJovens (`PainelJovens` / `paineljovens`)

- **Finalidade:** **painel do perfil jovem** — layout, dashboard e funcionalidades específicas (avisos, formação, recursos) conforme as rotas registadas no `RouteServiceProvider` do módulo.

### PainelLider (`PainelLider` / `painellider`)

- **Finalidade:** **painel do perfil líder** (Unijovem / liderança), com vistas e fluxos próprios alinhados ao papel na JUBAF.

### Permisao (`Permisao` / `permisao`)

- **Finalidade:** **controlo de acesso** — integração com o *Access Hub*, páginas de segurança na Diretoria/Admin e coerência com Spatie Permission. As rotas web canónicas estão centralizadas em `routes/admin.php` e `routes/diretoria.php`.

### Secretaria (`Secretaria` / `secretaria`)

- **Finalidade:** **secretaria associacional** — **reuniões**, **atas** (com PDF e anexos), **convocatórias** e **arquivo** documental, com estados de rascunho/submissão/aprovação/publicação.
- **Rotas:** prefixos `secretaria.*` em contextos admin e diretoria conforme `Modules/Secretaria/routes/`.

### Talentos (`Talentos` / `talentos`)

- **Finalidade:** **talentos e missões** — perfis, oportunidades de serviço e voluntariado no modelo JUBAF.

---

## Painéis e papéis (JUBAF)

| Painel / área | Caminho típico | Notas |
|---------------|------------------|--------|
| **Diretoria** | `/diretoria` | Gestão associativa ampla; integra Homepage, Avisos, Notificações, Secretaria (conforme permissões). |
| **Líder** | Prefixo do módulo `PainelLider` | Liderança Unijovem / congregação. |
| **Jovens** | Prefixo do módulo `PainelJovens` | Participantes jovens. |
| **Secretaria** | `/admin/secretaria` e/ou `/diretoria/secretaria` | Conforme `routes/` registadas. |
| **Super-Admin / Admin** | `/admin` | Configuração global, utilizadores, módulos ligados ao núcleo `app/`. |

Os nomes exatos dos middlewares e políticas podem variar; a fonte de verdade é **`routes/*.php`** e **`app/Policies`**.

---

## Integração entre módulos

- **Homepage ↔ Avisos / Bible:** conteúdos e widgets públicos podem consumir dados destes módulos.
- **PainelDiretoria ↔ Homepage / Avisos / Notificacoes:** rotas condicionais com `module_enabled(...)` em `routes/diretoria.php`.
- **Igrejas ↔ Avisos / utilizadores:** audiência e vínculos por congregação quando modelados nas tabelas e políticas.
- **Notificacoes:** pode ser disparada a partir de eventos noutros módulos (via jobs/listeners conforme implementação).

### Utilizador, congregação e papéis (módulo Igrejas)

- **Entidade congregação:** `Modules\Igrejas\App\Models\Church` (`igrejas_churches`), com `pastor_user_id` e `unijovem_leader_user_id` como referências oficiais na ficha.
- **`users.church_id`:** igreja principal do utilizador (jovem, líder ou pastor). Ao gravar a ficha da igreja, `ChurchLeadershipSync` alinha o `church_id` do líder Unijovem e o pivot `user_churches` (papéis `pastor` / `lider_unijovem` no pivot).
- **Papéis Spatie:** no painel `/lideres`, quem tem o papel **`lider`** e **`church_id`** preenchido pode **cadastrar jovens** na congregação (gate `igrejasProvisionYouth`); a permissão `igrejas.jovens.provision` continua no seeder para relatórios/RBAC explícito. O papel `lider` deve estar alinhado com `unijovem_leader_user_id` na ficha da igreja.
- **Evento:** `Modules\Igrejas\App\Events\LeaderAssignedToChurch` é disparado quando um pastor ou líder Unijovem **passa a estar** vinculado a uma congregação via `ChurchService` / pedidos aprovados (e continua a ser usado no fluxo de alteração de utilizador em `App\Services\Admin\UserService`).

Não existe um “fluxo único” tipo demanda→ordem de serviço; o grafo de dependências é **por funcionalidade** e **por papel**.

---

## Base de dados e migrations (squash)

O esquema completo da aplicação (núcleo + todos os módulos) está consolidado em **`database/schema/mysql-schema.sql`**, gerado com o comando oficial do Laravel:

```bash
php artisan schema:dump --prune
```

- **`--prune`** remove os ficheiros PHP em `database/migrations/` (o histórico fica representado no dump e na tabela `migrations` incluída no SQL).
- As pastas `Modules/*/database/migrations/` ficam **vazias** no repositório: novas alterações ao esquema devem ser feitas com **novas migrations** em `database/migrations/` e, quando a equipa decidir, voltar a correr `schema:dump --prune` para um novo baseline.
- **Novos clones / CI:** com MySQL, criar a base vazia e executar `php artisan migrate` — o Laravel carrega primeiro o ficheiro em `database/schema/{ligação}-schema.sql` e depois corre migrations pendentes (se existirem).
- **Seeders:** continuam em `database/seeders/` e em `Modules/*/database/seeders/`; não dependem dos ficheiros de migration removidos.
- **Testes automatizados:** `phpunit.xml` e `.env.testing` usam a base **`jubaf_test`** (MySQL). Crie-a e importe o schema uma vez, por exemplo:

```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS jubaf_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root jubaf_test < database/schema/mysql-schema.sql
```

O arranque de testes usa `tests/bootstrap.php` para garantir `APP_ENV=testing` e variáveis de BD coerentes com `.env.testing`.

---

## Estrutura típica de uma pasta `Modules/Nome`

```
Modules/
└── NomeDoModulo/
    ├── module.json              # alias, provider, meta
    ├── app/
    │   ├── Http/Controllers/
    │   ├── Models/              # (se existir)
    │   └── Providers/
    ├── routes/                  # web.php, api.php — carregados pelo ServiceProvider
    ├── resources/views/
    ├── database/migrations/     # opcional; o baseline global está em database/schema/
    └── config/                  # (opcional)
```

Para **ativar/desativar** módulos, use a configuração do pacote `nwidart/laravel-modules` (`modules_statuses.json` / config) e **não** assuma ficheiros comentados neste README como existentes se não estiverem no repositório.

---

<p align="center">
  <sub><strong>JUBAF</strong> — documentação de módulos alinhada ao código em <code>Modules/*/module.json</code> e à missão associacional da Juventude Batista Feirense.</sub>
</p>
