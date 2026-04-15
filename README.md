<p align="center">
  <img src="public/images/logo/logo.png" alt="JUBAF — Juventude Batista Feirense" width="320">
</p>

<h1 align="center">JUBAF — Plataforma digital</h1>

<p align="center">
  <strong>Juventude Batista Feirense</strong> · Comunidade batista vinculada à ASBAF (Associação Batista Feirense)<br>
  Site e painéis para comunicação, formação, congregações e gestão associacional.
</p>

---

## O que é o JUBAF

A **JUBAF** (*Juventude Batista Feirense*) é uma entidade civil de natureza religiosa, sem fins lucrativos, que reúne jovens das igrejas batistas arroladas à ASBAF, com fins de integração espiritual, cultural e associacional (congressos, Unijovem, estudos, desporto, artes, entre outros). O enquadramento institucional está descrito no **[Estatuto JUBAF](PLANOJUBAF/ESTATUTOJUBAF.md)** no repositório.

Este repositório é a **plataforma web Laravel** que dá suporte digital a esse ecossistema: **site público**, **áreas autenticadas por perfil** (jovens, liderança, diretoria, secretaria, administração) e **módulos funcionais** (avisos, bíblia, blog, calendário, igrejas, notificações, etc.), com permissões centralizadas e design alinhado à identidade JUBAF.

## Funcionalidades em alto nível

| Área | Descrição |
|------|-----------|
| **Público** | Homepage, conteúdos institucionais, blog, calendário, módulo Bíblia, avisos e formulários conforme configuração. |
| **Painéis** | Experiências dedicadas por perfil: Diretoria, Líder, Jovens, Secretaria, Super-Admin — com rotas e políticas próprias. |
| **Comunicação** | Avisos/banners, notificações em tempo real (broadcast), newsletter e contactos onde aplicável. |
| **Associacional** | Cadastro e fluxos relacionados a **igrejas/congregações** (ASBAF), alinhados ao modelo JUBAF. |
| **Governança** | RBAC (Spatie Permission + papéis JUBAF), auditoria de permissões e hubs de segurança nos painéis. |

## Stack técnica

- **Backend:** PHP 8.2+, **Laravel 12**, **Laravel Sanctum**, **Spatie Laravel Permission**
- **Modularização:** [`nwidart/laravel-modules`](https://github.com/nWidart/laravel-modules) — cada domínio em `Modules/`
- **Frontend:** **Vite**, **Tailwind CSS 4**, **Alpine.js**, componentes Blade e assets por módulo
- **Outros:** DomPDF, PhpSpreadsheet, Pusher (broadcast), Web Push (onde configurado), etc.

## Requisitos

- PHP **8.2+** com extensões usuais do Laravel (mbstring, openssl, pdo, tokenizer, xml, ctype, json, bcmath)
- **Composer 2**
- **Node.js 20+** e npm (para Vite)
- **MySQL** / MariaDB (ou driver compatível com Eloquent)

## Instalação rápida

```bash
git clone <url-do-repositorio> jubaf
cd jubaf
composer install
cp .env.example .env
php artisan key:generate
```

Configure `.env` (base de dados, `APP_URL`, mail, broadcast, etc.), depois:

```bash
php artisan migrate --seed   # se usar seeders do projeto
npm install
npm run build                  # ou npm run dev em desenvolvimento
php artisan serve
```

Ajuste permissões de `storage/` e `bootstrap/cache/` conforme o ambiente.

## Documentação no repositório

| Documento | Conteúdo |
|-----------|----------|
| **[Modules/README.md](Modules/README.md)** | Catálogo **oficial dos módulos** Laravel, responsabilidades e integrações. |
| **[CHANGELOG.md](CHANGELOG.md)** | Histórico de versões e alterações relevantes. |
| **[PLANOJUBAF/ESTATUTOJUBAF.md](PLANOJUBAF/ESTATUTOJUBAF.md)** | Texto de referência institucional (estatuto). |
| **`.cursor/plans/`** | Planos de evolução técnica (contexto interno; não substitui documentação de produto). |

## Estrutura de pastas (resumo)

```
app/                 # Núcleo da aplicação, políticas, providers, suporte JUBAF
config/              # Configuração, incl. jubaf_roles e módulos
database/            # Migrações, seeders e factories globais
Modules/             # Módulos de domínio (avisos, bible, igrejas, …)
public/              # Ponto de entrada web; logos em public/images/logo/
resources/           # Views globais, CSS/JS de entrada
routes/              # web.php, admin.php, diretoria.php, api.php, …
tests/               # Testes PHPUnit / Feature
```

## Identidade visual (README / GitHub)

- Logo principal: `public/images/logo/logo.png` (uso recomendado em fundo escuro; o ficheiro oficial da JUBAF).
- Logos claros/escuros e variantes podem ser referenciados em `config` / `SystemConfig` conforme o tema do site.

## Licença e créditos

O projeto segue a licença indicada em [`LICENSE`](LICENSE) (quando presente). Créditos de desenvolvimento e empresas parceiras podem constar nas páginas institucionais do próprio sistema.

---

<p align="center">
  <sub>Plataforma <strong>JUBAF</strong> — documentação alinhada ao código em <code>Modules/</code> e ao propósito associacional da Juventude Batista Feirense.</sub>
</p>
