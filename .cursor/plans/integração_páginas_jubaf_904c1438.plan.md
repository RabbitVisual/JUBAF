---
name: Integração páginas JUBAF
overview: Remover legado agrário/infra; diretoria dinâmica (admin + co-admin, fotos); Rádio 3:16 como página própria (radio.blade.php + layout JUB); sistema de devocionais separado e completo (painéis admin e Co-Admin, multimédia, citação bíblica carregada do módulo Bible estilo YouVersion, autores com cargo/pastor); interlinear público mantido no formato acordado (simples, sem links externos). Sem painel público jovens/liderança nesta fase.
todos:
    - id: remove-legacy-portals
      content: Apagar views portal/localidade/agricultor, PortalAgricultorController e limpar index.blade.php + navbar de CTAs legados
      status: completed
    - id: unify-diretoria
      content: Rota /diretoria; board_members + upload fotos; CRUD em admin; permissões admin + co-admin diretoria; SystemConfig textos/toggles
      status: completed
    - id: radio-316-page
      content: Rota pública /radio; evoluir/integrar Modules/Homepage/resources/views/public/radio.blade.php + layout homepage atual; SystemConfig (ligar, embedUrl, textos); componente player coerente com design JUB (não misturar com devocionais)
      status: completed
    - id: devotionals-full-stack
      content: Sistema devocionais completo — migração modelo (slug, datas, referência, texto, vídeo/imagem, autor vinculado a user/board_member ou tipo pastor convidado); rotas públicas + views devotionals-index/show no layout homepage; painel Admin (routes/admin.php + views/admin) e Co-Admin (routes/co-admin.php + resources/views/Co-Admin) com UI profissional alinhada aos layouts existentes; permissões; não é CMS genérico — formulários estruturados
      status: completed
    - id: devotionals-scripture-fetch
      content: Endpoint/serviço interno — ao introduzir referência (ex. Salmos 3:1-5) carregar texto da Bíblia a partir do módulo Bible (versão configurável) para pré-preencher campo do devocional (fluxo tipo YouVersion)
      status: completed
    - id: navbar-footer-wiring
      content: Links Diretoria, Devocionais, Rádio condicionados a toggles e Route::has
      status: completed
    - id: bible-interlinear-format
      content: Manter interlinear público no formato acordado — sem links externos; UI simplificada para jovens; auditoria grep http nas views Bible do interlinear
      status: completed
    - id: admin-scope-no-youth-panel
      content: Não implementar painel público liderança/jovens; publicação de devocionais e gestão via admin + co-admin apenas
      status: completed
    - id: tests-copy-qa
      content: Testes fluxos devocionais/rádio/diretoria; copy PLANOJUBAF; opcional carrossel index.blade.php
      status: completed
isProject: false
---

# Integração JUBAF — Rádio e Devocionais são independentes

## Correção importante

- **Rádio** e **Devocionais** são **módulos/entregas distintos** no plano e nos todos. Não há todo combinado `devotionals-radio-316`.
- **Rádio**: página dedicada já existente no repo — [`Modules/Homepage/resources/views/public/radio.blade.php`](Modules/Homepage/resources/views/public/radio.blade.php) (Rádio Rede 3.16, player, embed). Evoluir para alinhar ao **layout público atual** do projeto (`homepage::layouts.homepage` / master coerente), rotas em [`Modules/Homepage/routes/web.php`](Modules/Homepage/routes/web.php), config via `SystemConfig`.
- **Devocionais**: produto **completo e profissional** — não é “adaptação mínima”. Inclui painéis em [`routes/admin.php`](routes/admin.php) + [`resources/views/admin`](resources/views/admin) e [`routes/co-admin.php`](routes/co-admin.php) + [`resources/views/Co-Admin`](resources/views/Co-Admin) (já existe estrutura Co-Admin com `co-admin-or-admin`).

## Devocionais — requisitos funcionais

**Público**

- Listagem: [`Modules/Homepage/resources/views/public/devotionals-index.blade.php`](Modules/Homepage/resources/views/public/devotionals-index.blade.php).
- Detalhe: [`Modules/Homepage/resources/views/public/devotional-show.blade.php`](Modules/Homepage/resources/views/public/devotional-show.blade.php).
- Layout e tipografia **iguais ao design system** da homepage (navbar/footer JUBAF, Tailwind coerente).
- Conteúdo visível quando o módulo/funcionalidade estiver ativa (`SystemConfig` ou flag).

**Quem publica**

- Membros da **diretoria** (via co-admin) e **admin** global.
- **Pastores / convidados**: modelo de autor com tipo (ex. `board_member` | `pastor_guest`) para exibir no público: _“Devocional do dia — Pastor Alex Reis — [data]”_ ou _nome + cargo da diretoria_ conforme origem.

**Campos (estruturados — não CMS de páginas livres)**

- Título, slug, data do devocional, referência bíblica (string), **texto da passagem** (preenchido automaticamente ou editável após fetch), corpo/reflexão, capa/imagem opcional, **vídeo** (upload ou URL segura conforme política do projeto), estado rascunho/publicado, autor vinculado.

**Referência bíblica tipo YouVersion**

- No painel, ao digitar referência em linguagem natural (ex. `Salmos 3:1-5`), o sistema **chama o módulo Bible** (serviço interno / rota API já existente ou nova) para **resolver livro, capítulo, versículos** e **preencher o texto** no devocional.
- Validar limites de intervalo e versão (ex. tradução padrão configurável).

**Autorização**

- Policies ou permissões Spatie: ex. `manage_devotionals`, `publish_devotionals`; admin total; co-admin com escopo adequado (só criar/editar próprios ou todos, conforme decisão de produto documentada na implementação).

## Rádio 3:16 — requisitos

- Manter/evoluir [`public/radio.blade.php`](Modules/Homepage/resources/views/public/radio.blade.php): hero, player, iframe `embedUrl` a partir de config.
- Opcional: extrair card do player para `<x-homepage::radio-player>` reutilizável (variante compacta na home) **sem** misturar rotas ou controllers com devocionais.

## Diretoria (resumo)

- CRUD + fotos no admin; co-admin com permissão; página pública única `/diretoria`.

## Bible — interlinear

- **Manter interlinear público** no **formato acordado** com o utilizador: **sem links externos**, interface **simplificada** para jovens (menos ferramentas de estudo densas).
- Não remover o interlinear por defeito; foco em conformidade visual/UX e segurança de conteúdo.

## O que não fazer nesta fase

- Painel público separado “jovens” ou “liderança” (além do site público normal).
- CMS genérico (Gutenberg, blocos livres): usar **modelo + formulários** específicos de devocional.

## Diagrama

```mermaid
flowchart TD
  subgraph publish [Publicação]
    AdminDev[admin devocionais]
    CoAdminDev[co-admin devocionais]
    AdminRadio[admin config rádio]
    Board[CRUD diretoria]
  end
  subgraph db [BD]
    DV[(devotionals)]
    BM[(board_members)]
    SC[(system_configs)]
  end
  subgraph public [Público]
    DevIndex[/devocionais]
    DevShow[/devocionais/slug]
    RadioP[/radio]
    Dir[/diretoria]
    BibleInt[/biblia/interlinear]
  end
  AdminDev --> DV
  CoAdminDev --> DV
  AdminRadio --> SC
  Board --> BM
  SC --> RadioP
  DV --> DevIndex
  DV --> DevShow
  BM --> Dir
```

## Ordem sugerida

1. Modelo + migração `devotionals` e políticas.
2. Serviço de resolução de passagem (Bible).
3. Controllers + views Admin e Co-Admin (mesmo padrão visual dos layouts existentes).
4. Rotas públicas + refactor das blades `devotionals-*` para `homepage::`.
5. Rádio: rota + alinhamento layout + SystemConfig.
6. Interlinear: passada de remoção de externos + simplificação UI.

## Riscos

- Upload de vídeo: limites de tamanho, storage, streaming vs URL.
- Fetch de passagem: normalização de nomes de livros (PT) e erros amigáveis se referência for inválida.
