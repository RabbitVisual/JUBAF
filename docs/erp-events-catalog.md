# Catálogo de eventos ERP (JUBAF)

Eventos de domínio usados para integração entre módulos (listeners podem notificar, auditar ou sincronizar).

| Evento | Módulo | Disparo típico | Listeners (exemplo) |
|--------|--------|----------------|---------------------|
| `Modules\Igrejas\App\Events\ChurchSectorAssigned` | Igrejas | Criação/alteração de `jubaf_sector_id` na congregação | `LogChurchSectorAssignment` |
| `Modules\Igrejas\App\Events\LeaderAssignedToChurch` | Igrejas | Novo vínculo líder/pastor ↔ igreja (principal ou pivot) | `LogLeaderAssignedToChurch` |
| `Modules\Secretaria\App\Events\MinutePublished` | Secretaria | Publicação de ata | `DispatchMinutePublishedIntegrations` |
| `Modules\Financeiro\App\Events\FinancialObligationGenerated` | Financeiro | Criação de obrigação de cota (`financeiro:generate-obligations`) | `LogFinancialObligationGenerated` |
| `Modules\Financeiro\App\Events\FinancialObligationPaid` | Financeiro | Pagamento Gateway reconciliado com `fin_obligation_id` no payload | `LogFinancialObligationPaid` |
| `Modules\Talentos\App\Events\TalentSkillValidated` | Talentos | Líder local grava `validated_at` / `validated_by` no pivot `talent_profile_skill` | `SendTalentSkillValidatedNotification` (Notificações in-app, painel jovens) |

## Payload Gateway (cotas)

Para associar um pagamento online a uma obrigação, inclua no `raw_last_payload` do pagamento (ou metadados equivalentes):

- `fin_obligation_id` (int): ID em `fin_obligations`.
- `church_id` (int, opcional): congregação; se omitido, usa-se a igreja da obrigação.

## Comandos relacionados

- `php artisan jubaf:backfill-sectors` — preenche `jubaf_sector_id` nas igrejas a partir do texto `sector`.
- `php artisan financeiro:generate-obligations` — gera obrigações por igreja activa (idempotente por ano associativo).
