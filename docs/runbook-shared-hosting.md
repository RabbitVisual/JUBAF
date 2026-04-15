# Runbook — JUBAF em hospedagem partilhada (ERP)

Este guia cobre operação sem Redis, Horizon ou WebSockets obrigatórios.

## Pré-requisitos

- PHP 8.2+, extensões habituais Laravel (pdo, mbstring, openssl, tokenizer, xml, ctype, json, fileinfo).
- MySQL/MariaDB com base dedicada.
- Cron do sistema com acesso ao `php` do projeto.

## Deploy

1. **Código**: enviar ficheiros ou `git pull` na pasta do site.
2. **Dependências**:
   - `composer install --no-dev --optimize-autoloader`
   - `npm ci && npm run build` (se alterar assets front-end).
3. **Ambiente**: copiar `.env.example` → `.env`, gerar `APP_KEY`, configurar `DB_*`, `APP_URL`, mail.
4. **Migrações**:
   ```bash
   php artisan migrate --force
   ```
5. **Cache de config/rota (produção)**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

## Agendamento (cron)

No painel da hospedagem, uma entrada **por minuto**:

```cron
* * * * * cd /caminho/para/JUBAF && php artisan schedule:run >> /dev/null 2>&1
```

O Laravel dispara jobs agendados em `routes/console.php` conforme configurado.

## Fila (queue)

- Preferência: `QUEUE_CONNECTION=database` em `.env`.
- Criar tabela de jobs (se ainda não existir) via migrações Laravel padrão.
- Em alojamento sem worker dedicado, pode usar **cron** para processar a fila em lotes:

```cron
* * * * * cd /caminho/para/JUBAF && php artisan queue:work database --stop-when-empty >> /dev/null 2>&1
```

Ou aumentar frequência conforme carga. Para tarefas críticas síncronas, alguns fluxos podem usar `dispatchSync` — avaliar caso a caso.

## Notificações

- Sem broadcast em tempo real: a UI pode usar **polling** (intervalo configurado no front) ou recarregar listagens.
- E-mail depende de `MAIL_*` válidos no `.env`.

## ERP — setores e âmbito

- Tabelas `jubaf_sectors`, colunas `jubaf_sector_id` em `users` e `igrejas_churches` (migrações `2026_04_14_*`).
- Vice-presidentes com `jubaf_sector_id` veem apenas dados do setor em **Igrejas**, **Secretaria** (atas) e **Financeiro** (lançamentos, obrigações e relatórios).
- Atribuir setor em **Admin → Utilizadores** para papéis `vice-presidente-1` / `vice-presidente-2`.
- Backfill do texto legado `sector` → FK: `php artisan jubaf:backfill-sectors` (use `--dry-run` primeiro).

## ERP — cotas (obrigações)

- Tabela `fin_obligations`; geração: `php artisan financeiro:generate-obligations` (agendado 1 de março por defeito; configurável em [`Modules/Financeiro`](../Modules/Financeiro/config/config.php) `quota.default_amount`).
- Pagamentos Gateway podem fechar uma obrigação se `raw_last_payload` incluir `fin_obligation_id` (ver [`docs/erp-events-catalog.md`](erp-events-catalog.md)).

## Verificação pós-deploy

- `php artisan about`
- Abrir login e um painel (Diretoria) com utilizador de teste.
- `php artisan migrate:status` — confirmar migrações aplicadas.

## Testes (CI / local)

```bash
php artisan test
```

Requer base `jubaf_test` (ou ajustar `phpunit.xml`) e migrações aplicadas nessa base.
