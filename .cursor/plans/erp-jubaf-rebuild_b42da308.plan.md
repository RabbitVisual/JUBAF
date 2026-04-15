---
name: erp-jubaf-rebuild
overview: Transformar o JUBAF em um ERP eclesiástico integrado com prioridade em cadastro base e setorização, usando um rebuild planejado por etapas. O plano mantém operação em hospedagem compartilhada, sem dependências externas obrigatórias, com integração entre módulos via eventos, workflows e painéis por perfil.
todos:
    - id: fase0-blueprint
      content: Mapear entidades canônicas (igreja, setor, pessoa, vínculos) e estratégia de migração em ondas
      status: completed
    - id: fase1-cadastro-setorizacao
      content: Implementar cadastro base unificado com filtros de acesso por setor/igreja nos painéis
      status: completed
    - id: fase2-secretaria
      content: Reestruturar secretaria com workflow documental, protocolo, versões e integração com notificações
      status: completed
    - id: fase3-financeiro
      content: Integrar tesouraria a igrejas/setores e criar dashboards de transparência
      status: completed
    - id: fase4-eventos
      content: Substituir integrações diretas críticas por eventos/listeners canônicos entre módulos
      status: completed
    - id: fase5-operacao-qualidade
      content: Ampliar testes, auditoria e runbook para hospedagem compartilhada sem dependências externas
      status: completed
isProject: false
---

# Plano ERP Eclesiástico JUBAF (Rebuild Planejado)

## Contexto Técnico Validado do Projeto

- Backend atual: `laravel/framework:^12.0` e `php:^8.2` em [`c:/laragon/www/JUBAF/composer.json`](c:/laragon/www/JUBAF/composer.json).
- Modularização atual: `nwidart/laravel-modules:^12.0` em [`c:/laragon/www/JUBAF/composer.json`](c:/laragon/www/JUBAF/composer.json) e configuração em [`c:/laragon/www/JUBAF/config/modules.php`](c:/laragon/www/JUBAF/config/modules.php).
- Frontend atual: `tailwindcss:^4.2.0` + `@tailwindcss/vite:^4.2.0` em [`c:/laragon/www/JUBAF/package.json`](c:/laragon/www/JUBAF/package.json), com plugin ativo em [`c:/laragon/www/JUBAF/vite.config.js`](c:/laragon/www/JUBAF/vite.config.js).
- Bootstrap Laravel 12: roteamento e middleware centralizados em [`c:/laragon/www/JUBAF/bootstrap/app.php`](c:/laragon/www/JUBAF/bootstrap/app.php) (sem dependência de `app/Console/Kernel.php` no estado atual).

## Objetivo Estratégico

Transformar o JUBAF em um ERP eclesiástico integrado para aproximadamente 70 igrejas, priorizando:

- cadastro canônico único;
- setorização por vice-presidência;
- secretaria profissional;
- tesouraria transparente;
- integração entre módulos sem depender obrigatoriamente de serviços externos.

## Restrições Não-Negociáveis (Hospedagem Compartilhada)

- Sem exigir Redis/Horizon/WebSocket externo para funcionamento básico.
- Fila padrão com `database` e fallback síncrono quando necessário.
- Notificações operando por polling quando broadcast em tempo real não estiver disponível.
- Jobs e rotinas críticas executáveis por cron padrão (`schedule:run`) sem infraestrutura dedicada.

## Diagnóstico Atual (baseado no código)

- O projeto possui módulos maduros de funcionalidades (ex.: `Bible`, `Avisos`, `Notificacoes`, `Chat`) e módulos de governança a consolidar (`Igrejas`, `Financeiro`, `Secretaria`, `Permisao`, painéis).
- Boa parte da integração atual ocorre por chamadas diretas e gates `module_enabled`, com poucos fluxos formais de eventos de negócio.
- Painéis (`PainelDiretoria`, `PainelLider`, `PainelJovens`) estão mais como camada de experiência; o fortalecimento deve acontecer no núcleo de dados e regras.
- O arquivo [`c:/laragon/www/JUBAF/update_projeto/1.md`](c:/laragon/www/JUBAF/update_projeto/1.md) traz boas ideias de produto, mas precisa ser filtrado para o cenário real do stack e da hospedagem.

## Arquitetura-Alvo ERP (v2)

```mermaid
flowchart LR
  cadastroCanonico[CadastroCanonico] --> governancaSetorial[GovernancaSetorial]
  governancaSetorial --> secretariaDigital[SecretariaDigital]
  governancaSetorial --> tesourariaDigital[TesourariaDigital]
  secretariaDigital --> notificacoesHub[NotificacoesHub]
  tesourariaDigital --> notificacoesHub
  notificacoesHub --> painelDiretoria[PainelDiretoria]
  notificacoesHub --> painelLider[PainelLider]
  notificacoesHub --> painelJovens[PainelJovens]
```

## Macroescopo Funcional (ERP JUBAF)

- Cadastro ativo de igrejas, pastores, líderes e jovens com vínculo organizacional.
- Gestão setorial por vice-presidência (igreja vinculada a setor, com visão e ação por escopo).
- Secretaria digital para atas, ofícios, protocolos, acervo e histórico.
- Tesouraria com plano de contas, cotas associativas, prestação de contas e relatórios.
- Agenda institucional conectada com comunicação e notificações.
- Painéis por perfil (Super Admin, Diretoria, Líder, Jovens) com permissões e dados adequados ao nível.

## Roadmap de Rebuild por Fases

## Fase 0 — Blueprint e Governança de Dados

- Definir modelo canônico para `Igreja`, `Setor`, `Pessoa`, `VínculoMinisterial`, `MandatoSetorial`.
- Criar catálogo de origem/destino de dados por módulo para migração sem perda.
- Congelar novos campos duplicados nos módulos até aprovação do modelo canônico.
- Definir estratégia de migração em ondas: `backfill`, validação, corte e limpeza.

Arquivos-base:

- [`c:/laragon/www/JUBAF/Modules/Igrejas`](c:/laragon/www/JUBAF/Modules/Igrejas)
- [`c:/laragon/www/JUBAF/Modules/Permisao`](c:/laragon/www/JUBAF/Modules/Permisao)
- [`c:/laragon/www/JUBAF/database/migrations`](c:/laragon/www/JUBAF/database/migrations)
- [`c:/laragon/www/JUBAF/database/seeders/RolesPermissionsSeeder.php`](c:/laragon/www/JUBAF/database/seeders/RolesPermissionsSeeder.php)

## Fase 1 — Prioridade Definida: Cadastro Base + Setorização

- Unificar cadastro de pessoas (jovens, líderes, pastores, diretoria) e igrejas com referência única.
- Modelar setor administrativo e vínculo igreja-setor.
- Implementar escopo de acesso por perfil:
    - Super Admin: visão total.
    - Diretoria: visão associacional.
    - Vice-presidente: somente setor vinculado.
    - Líder local: somente igreja(s) vinculada(s).
- Garantir filtros em consultas, dashboards e exportações por escopo de autorização.

Pontos críticos:

- [`c:/laragon/www/JUBAF/config/jubaf_roles.php`](c:/laragon/www/JUBAF/config/jubaf_roles.php)
- [`c:/laragon/www/JUBAF/app/Support/JubafRoleRegistry.php`](c:/laragon/www/JUBAF/app/Support/JubafRoleRegistry.php)
- [`c:/laragon/www/JUBAF/routes/diretoria.php`](c:/laragon/www/JUBAF/routes/diretoria.php)
- [`c:/laragon/www/JUBAF/routes/lideres.php`](c:/laragon/www/JUBAF/routes/lideres.php)
- [`c:/laragon/www/JUBAF/routes/jovens.php`](c:/laragon/www/JUBAF/routes/jovens.php)

## Fase 2 — Secretaria Profissional (Ata + Acervo + Protocolo)

- Implementar workflow de documentos: rascunho, revisão, aprovado, publicado, arquivado.
- Atas com protocolo, anexos, histórico de alterações e trilha de ação por usuário.
- Vínculo de ata com calendário/reunião e com atos administrativos.
- Publicação com notificação interna para papéis estratégicos.

Arquivos-alvo:

- [`c:/laragon/www/JUBAF/Modules/Secretaria`](c:/laragon/www/JUBAF/Modules/Secretaria)
- [`c:/laragon/www/JUBAF/Modules/Calendario`](c:/laragon/www/JUBAF/Modules/Calendario)
- [`c:/laragon/www/JUBAF/Modules/Notificacoes`](c:/laragon/www/JUBAF/Modules/Notificacoes)

## Fase 3 — Tesouraria e Transparência Financeira

- Consolidar plano de contas e categorias padronizadas.
- Lançamentos vinculáveis a igreja, setor e documento autorizador (ex.: ata/ofício).
- Gestão de cotas associativas por igreja com status e histórico.
- Painel financeiro da diretoria com visão macro e visão por setor.

Arquivos-alvo:

- [`c:/laragon/www/JUBAF/Modules/Financeiro`](c:/laragon/www/JUBAF/Modules/Financeiro)
- [`c:/laragon/www/JUBAF/Modules/Gateway`](c:/laragon/www/JUBAF/Modules/Gateway)
- [`c:/laragon/www/JUBAF/config/queue.php`](c:/laragon/www/JUBAF/config/queue.php)
- [`c:/laragon/www/JUBAF/routes/console.php`](c:/laragon/www/JUBAF/routes/console.php)

## Fase 4 — Integração Intermódulos por Eventos

- Definir eventos canônicos:
    - `ChurchAssignedToSector`
    - `LeaderAssignedToChurch`
    - `MinutePublished`
    - `FinancialObligationGenerated`
    - `FinancialObligationPaid`
- Criar listeners por módulo para reduzir chamadas diretas e acoplamento.
- Centralizar fan-out de comunicação no módulo `Notificacoes`.

Pontos técnicos a evoluir:

- [`c:/laragon/www/JUBAF/Modules/Admin/app/Events/AdminNavigationBuilding.php`](c:/laragon/www/JUBAF/Modules/Admin/app/Events/AdminNavigationBuilding.php)
- [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Services/SecretariaIntegrationBus.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Services/SecretariaIntegrationBus.php)
- [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Services/IgrejasIntegrationBus.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Services/IgrejasIntegrationBus.php)

## Fase 5 — UI/UX Profissional com Tailwind CSS v4.2

- Padronizar sistema visual de painéis com componentes reutilizáveis.
- Garantir consistência entre dashboards (Diretoria, Líder, Jovens, Admin).
- Aplicar boas práticas Tailwind v4.2 do projeto:
    - manter classes utilitárias atuais do codebase;
    - evitar utilitários removidos de v3;
    - reforçar responsividade e dark mode onde já houver suporte.

Arquivos-alvo:

- [`c:/laragon/www/JUBAF/Modules/Admin/resources/views`](c:/laragon/www/JUBAF/Modules/Admin/resources/views)
- [`c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views`](c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views)
- [`c:/laragon/www/JUBAF/Modules/PainelLider/resources/views`](c:/laragon/www/JUBAF/Modules/PainelLider/resources/views)
- [`c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views`](c:/laragon/www/JUBAF/Modules/PainelJovens/resources/views)
- [`c:/laragon/www/JUBAF/resources/css`](c:/laragon/www/JUBAF/resources/css)

## Fase 6 — Operação, Qualidade e Segurança

- Cobrir módulos críticos sem testes com suítes mínimas de regressão.
- Padronizar políticas, validações e autorização por escopo setorial.
- Estabelecer trilha de auditoria para ações sensíveis (secretaria, finanças, permissões).
- Publicar runbook de deploy para hospedagem compartilhada:
    - migrações;
    - cron;
    - fila database;
    - fallback de notificações.

Arquivos-chave:

- [`c:/laragon/www/JUBAF/bootstrap/app.php`](c:/laragon/www/JUBAF/bootstrap/app.php)
- [`c:/laragon/www/JUBAF/routes/channels.php`](c:/laragon/www/JUBAF/routes/channels.php)
- [`c:/laragon/www/JUBAF/app/helpers.php`](c:/laragon/www/JUBAF/app/helpers.php)
- [`c:/laragon/www/JUBAF/.env.example`](c:/laragon/www/JUBAF/.env.example)

## Backlog Técnico por Sprint (ordem exata)

## Sprint 1 — Fundação do Cadastro Canônico (Igrejas + Pessoas + Setor)

Objetivo: estruturar a base única para igreja, pessoa e setorização sem quebrar fluxos atuais.

Ordem de implementação:

1. Definir contrato de dados e validações de escopo.
2. Criar/ajustar migrations de setorização e vínculos canônicos.
3. Ajustar models e policies.
4. Ajustar controllers e requests do módulo `Igrejas`.
5. Validar listagens por escopo de acesso.

Arquivos-alvo por módulo:

- `Modules/Igrejas`
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Models/Church.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Models/Church.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Models/ChurchChangeRequest.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Models/ChurchChangeRequest.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Policies/ChurchPolicy.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Policies/ChurchPolicy.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Controllers/DiretoriaChurchController.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Controllers/DiretoriaChurchController.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Controllers/AdminChurchController.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Controllers/AdminChurchController.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Requests/StoreChurchRequest.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Requests/StoreChurchRequest.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Requests/UpdateChurchRequest.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Http/Requests/UpdateChurchRequest.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/routes/admin.php`](c:/laragon/www/JUBAF/Modules/Igrejas/routes/admin.php)
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/routes/diretoria.php`](c:/laragon/www/JUBAF/Modules/Igrejas/routes/diretoria.php)
- `Núcleo`
    - [`c:/laragon/www/JUBAF/database/migrations`](c:/laragon/www/JUBAF/database/migrations)
    - [`c:/laragon/www/JUBAF/routes/diretoria.php`](c:/laragon/www/JUBAF/routes/diretoria.php)
    - [`c:/laragon/www/JUBAF/database/seeders/RolesPermissionsSeeder.php`](c:/laragon/www/JUBAF/database/seeders/RolesPermissionsSeeder.php)

Critério de pronto:

- Cada igreja está vinculada a um setor e a consulta respeita escopo por perfil.

## Sprint 2 — RBAC Setorial e Painéis por Escopo

Objetivo: aplicar controle de acesso setorial (Super Admin, Diretoria, VP, Líder local) de ponta a ponta.

Ordem de implementação:

1. Fechar matriz de papéis/permissões por ação.
2. Ajustar middleware e guards de painel.
3. Aplicar filtros por escopo em listagens e dashboards.
4. Ajustar sidebars para exibir apenas recursos autorizados.

Arquivos-alvo por módulo:

- `Permisao`
    - [`c:/laragon/www/JUBAF/Modules/Permisao/app/Http/Controllers/RoleManagementController.php`](c:/laragon/www/JUBAF/Modules/Permisao/app/Http/Controllers/RoleManagementController.php)
    - [`c:/laragon/www/JUBAF/Modules/Permisao/app/Http/Controllers/PermissionManagementController.php`](c:/laragon/www/JUBAF/Modules/Permisao/app/Http/Controllers/PermissionManagementController.php)
    - [`c:/laragon/www/JUBAF/Modules/Permisao/app/Http/Controllers/UserManagementController.php`](c:/laragon/www/JUBAF/Modules/Permisao/app/Http/Controllers/UserManagementController.php)
    - [`c:/laragon/www/JUBAF/Modules/Permisao/routes/web.php`](c:/laragon/www/JUBAF/Modules/Permisao/routes/web.php)
- `Núcleo`
    - [`c:/laragon/www/JUBAF/config/jubaf_roles.php`](c:/laragon/www/JUBAF/config/jubaf_roles.php)
    - [`c:/laragon/www/JUBAF/app/Support/JubafRoleRegistry.php`](c:/laragon/www/JUBAF/app/Support/JubafRoleRegistry.php)
    - [`c:/laragon/www/JUBAF/bootstrap/app.php`](c:/laragon/www/JUBAF/bootstrap/app.php)
    - [`c:/laragon/www/JUBAF/routes/diretoria.php`](c:/laragon/www/JUBAF/routes/diretoria.php)
    - [`c:/laragon/www/JUBAF/routes/lideres.php`](c:/laragon/www/JUBAF/routes/lideres.php)
    - [`c:/laragon/www/JUBAF/routes/jovens.php`](c:/laragon/www/JUBAF/routes/jovens.php)

Critério de pronto:

- VP não enxerga dados fora do setor; líder local não enxerga dados de outras igrejas.

## Sprint 3 — Secretaria Digital Profissional

Objetivo: consolidar workflow de atas/documentos com rastreabilidade e protocolo.

Ordem de implementação:

1. Revisar estados do workflow e regras de transição.
2. Garantir validação e políticas por estado.
3. Ajustar controllers (admin e diretoria) para fluxo único.
4. Integrar notificações internas na publicação.
5. Fechar telas e filtros de acervo.

Arquivos-alvo por módulo:

- `Secretaria`
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Models/Minute.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Models/Minute.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Models/Meeting.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Models/Meeting.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Models/SecretariaDocument.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Models/SecretariaDocument.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Policies/MinutePolicy.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Policies/MinutePolicy.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Http/Controllers/Admin/MinuteController.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Http/Controllers/Admin/MinuteController.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Http/Controllers/Diretoria/MinuteController.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Http/Controllers/Diretoria/MinuteController.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Services/SecretariaNotificationDispatcher.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Services/SecretariaNotificationDispatcher.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/routes/admin.php`](c:/laragon/www/JUBAF/Modules/Secretaria/routes/admin.php)
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/routes/diretoria.php`](c:/laragon/www/JUBAF/Modules/Secretaria/routes/diretoria.php)
- `Notificacoes`
    - [`c:/laragon/www/JUBAF/Modules/Notificacoes/app/Services/NotificacaoService.php`](c:/laragon/www/JUBAF/Modules/Notificacoes/app/Services/NotificacaoService.php)

Critério de pronto:

- Ata completa o ciclo até publicação com log de ação e notificação aos perfis corretos.

## Sprint 4 — Tesouraria Integrada a Igrejas e Governança

Objetivo: conectar lançamentos financeiros a igreja/setor e evidência administrativa.

Ordem de implementação:

1. Padronizar categorias e regras de lançamento.
2. Ligar transações ao contexto de igreja/setor.
3. Integrar solicitação/despesa com referência documental.
4. Fechar dashboards financeiros por escopo.

Arquivos-alvo por módulo:

- `Financeiro`
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/app/Models/FinTransaction.php`](c:/laragon/www/JUBAF/Modules/Financeiro/app/Models/FinTransaction.php)
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/app/Models/FinCategory.php`](c:/laragon/www/JUBAF/Modules/Financeiro/app/Models/FinCategory.php)
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/app/Models/FinExpenseRequest.php`](c:/laragon/www/JUBAF/Modules/Financeiro/app/Models/FinExpenseRequest.php)
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/app/Http/Controllers/Diretoria/TransactionController.php`](c:/laragon/www/JUBAF/Modules/Financeiro/app/Http/Controllers/Diretoria/TransactionController.php)
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/app/Http/Controllers/Diretoria/ReportController.php`](c:/laragon/www/JUBAF/Modules/Financeiro/app/Http/Controllers/Diretoria/ReportController.php)
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/app/Policies/FinTransactionPolicy.php`](c:/laragon/www/JUBAF/Modules/Financeiro/app/Policies/FinTransactionPolicy.php)
    - [`c:/laragon/www/JUBAF/Modules/Financeiro/routes/diretoria.php`](c:/laragon/www/JUBAF/Modules/Financeiro/routes/diretoria.php)
- `Gateway`
    - [`c:/laragon/www/JUBAF/Modules/Gateway/app/Jobs/ReconcilePaymentToFinTransactionJob.php`](c:/laragon/www/JUBAF/Modules/Gateway/app/Jobs/ReconcilePaymentToFinTransactionJob.php)
    - [`c:/laragon/www/JUBAF/Modules/Gateway/app/Services/PaymentOrchestrator.php`](c:/laragon/www/JUBAF/Modules/Gateway/app/Services/PaymentOrchestrator.php)

Critério de pronto:

- Diretoria consegue rastrear receita/despesa por igreja e por setor.

## Sprint 5 — Eventos, Integração Intermódulos e Notificações

Objetivo: reduzir acoplamento direto e padronizar comunicação por eventos/listeners.

Ordem de implementação:

1. Criar eventos canônicos.
2. Implementar listeners por módulo consumidor.
3. Refatorar pontos de integração direta para dispatch de eventos.
4. Garantir idempotência básica e logs de falha.

Arquivos-alvo por módulo:

- `Secretaria`
    - [`c:/laragon/www/JUBAF/Modules/Secretaria/app/Services/SecretariaIntegrationBus.php`](c:/laragon/www/JUBAF/Modules/Secretaria/app/Services/SecretariaIntegrationBus.php)
- `Igrejas`
    - [`c:/laragon/www/JUBAF/Modules/Igrejas/app/Services/IgrejasIntegrationBus.php`](c:/laragon/www/JUBAF/Modules/Igrejas/app/Services/IgrejasIntegrationBus.php)
- `Notificacoes`
    - [`c:/laragon/www/JUBAF/Modules/Notificacoes/app/Events/NotificacaoCriada.php`](c:/laragon/www/JUBAF/Modules/Notificacoes/app/Events/NotificacaoCriada.php)
    - [`c:/laragon/www/JUBAF/Modules/Notificacoes/app/Events/NotificacaoLida.php`](c:/laragon/www/JUBAF/Modules/Notificacoes/app/Events/NotificacaoLida.php)
    - [`c:/laragon/www/JUBAF/Modules/Notificacoes/app/Providers/EventServiceProvider.php`](c:/laragon/www/JUBAF/Modules/Notificacoes/app/Providers/EventServiceProvider.php)
- `Admin`
    - [`c:/laragon/www/JUBAF/Modules/Admin/app/Events/AdminNavigationBuilding.php`](c:/laragon/www/JUBAF/Modules/Admin/app/Events/AdminNavigationBuilding.php)

Critério de pronto:

- Publicação de ata e atualização de cadastro setorial disparam ações em outros módulos sem chamada direta em controller.

## Sprint 6 — UX Profissional, Operação e Qualidade Final

Objetivo: padronizar UI dos painéis e fechar qualidade para produção.

Ordem de implementação:

1. Padronizar componentes visuais e blocos de dashboard.
2. Revisar responsividade e dark mode em telas críticas.
3. Fechar runbook de produção compartilhada.
4. Implementar testes mínimos dos fluxos críticos.

Arquivos-alvo por módulo:

- `PainelDiretoria`
    - [`c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views/components/layouts/app.blade.php`](c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views/components/layouts/app.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views/components/layouts/sidebar.blade.php`](c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views/components/layouts/sidebar.blade.php)
    - [`c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views/dashboard.blade.php`](c:/laragon/www/JUBAF/Modules/PainelDiretoria/resources/views/dashboard.blade.php)
- `Núcleo`
    - [`c:/laragon/www/JUBAF/resources/css`](c:/laragon/www/JUBAF/resources/css)
    - [`c:/laragon/www/JUBAF/vite.config.js`](c:/laragon/www/JUBAF/vite.config.js)
    - [`c:/laragon/www/JUBAF/routes/console.php`](c:/laragon/www/JUBAF/routes/console.php)
    - [`c:/laragon/www/JUBAF/.env.example`](c:/laragon/www/JUBAF/.env.example)

Critério de pronto:

- Sistema consistente visualmente, com fluxo crítico coberto e operação documentada para hospedagem compartilhada.

## Critérios de Aceite por Fase

- Fase 1: uma igreja e uma pessoa possuem identificador canônico único e escopo de acesso funcionando por perfil.
- Fase 2: ata percorre workflow completo com protocolo e rastreabilidade.
- Fase 3: contribuição/cota de igreja pode ser criada, acompanhada e auditada.
- Fase 4: evento de negócio dispara ação útil em outro módulo sem acoplamento direto em controller.
- Fase 5: painéis principais seguem padrão visual consistente e responsivo.
- Fase 6: testes mínimos verdes e runbook reproduzível em hospedagem compartilhada.

## Entregáveis Finais do Rebuild

- Modelo canônico de dados e plano de migração documentado.
- Matriz RBAC setorial aplicada e validada.
- Secretaria digital integrada a agenda e notificações.
- Tesouraria integrada ao cadastro de igrejas e governança documental.
- Catálogo de eventos/listeners entre módulos com cobertura de testes essenciais.
- Guia operacional para produção sem dependências externas obrigatórias.
