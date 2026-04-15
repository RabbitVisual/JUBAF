# Modelo canónico ERP JUBAF

Este documento define a **fonte única de verdade** para o rebuild ERP eclesiástico (cadastro, setores, secretaria, tesouraria).

## Entidades

| Entidade | Descrição | Tabela / modelo |
|----------|-----------|-----------------|
| **JubafSector** | Setor associacional (ex.: vice-presidência, encontro de setores). | `jubaf_sectors` → `Modules\Igrejas\App\Models\JubafSector` |
| **Church** | Igreja ou congregação ASBAF. Liga-se a um setor opcional. | `igrejas_churches` → `Church` |
| **User** | Pessoa com autenticação. `church_id` = congregação principal; `jubaf_sector_id` = setor atribuído a vice-presidentes. | `users` |
| **Minute** | Ata com estados de workflow e integridade (protocolo, hash); pode estar `archived`. | `secretaria_minutes` |
| **FinTransaction** | Movimento financeiro; `church_id` nulo = âmbito regional; `secretaria_minute_id` opcional (evidência). | `fin_transactions` |
| **FinObligation** | Cota / obrigação associativa por igreja e ano (início do ciclo mar.–fev.). | `fin_obligations` |

## Governança por setor

- **Super-admin / Presidente / Secretários / Tesoureiros**: visão global (sem filtro por `jubaf_sector_id`), salvo políticas Spatie específicas.
- **Vice-presidente (1º ou 2º)** com `users.jubaf_sector_id` definido: apenas igrejas com `igrejas_churches.jubaf_sector_id` igual; atas e transações filtradas em conformidade.
- **Líder / Pastor / Jovens**: âmbito de congregação já existente (`affiliatedChurchIds()`).

## Migração de dados (ondas)

1. Criar registos em `jubaf_sectors` (seed inicial).
2. Preencher `igrejas_churches.jubaf_sector_id` (manual ou script) a partir do campo legado `sector` quando aplicável.
3. Atribuir `users.jubaf_sector_id` aos vice-presidentes.
4. Remover dependência exclusiva do texto livre `sector` após validação (o campo pode manter-se sincronizado com o nome do setor).

## Eventos de integração

Ver lista completa em [`docs/erp-events-catalog.md`](erp-events-catalog.md).

- `ChurchSectorAssigned`, `LeaderAssignedToChurch`, `MinutePublished`, `FinancialObligationGenerated`, `FinancialObligationPaid`.
