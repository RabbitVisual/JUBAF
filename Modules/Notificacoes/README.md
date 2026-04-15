# Módulo Notificações (JUBAF)

Notificações in-app para utilizadores autenticados: centro de mensagens nos painéis **Jovens**, **Líderes**, **Diretoria** e **Admin**; API em `/api/notificacoes/*` para contagem e lista (dropdown na barra).

## Configuração

- `config/notificacoes.php` — tipos, `module_sources` (origens alinhadas aos módulos JUBAF).
- Variáveis úteis: `NOTIFICACOES_EMAIL_ENABLED`, `NOTIFICACOES_BROADCASTING_ENABLED`, `NOTIFICACOES_POLLING_INTERVAL`.

## Rotas principais

| Área        | Prefixo |
|------------|---------|
| Social / conta | `notificacoes.*` (lista, marcar lida) |
| Jovens     | `jovens.notificacoes.*` |
| Líderes    | `lideres.notificacoes.*` |
| Diretoria  | `diretoria.notificacoes.*` (CRUD + envio) |
| Admin      | `admin.notificacoes.*` |

## Seeds

`php artisan db:seed --class=Modules\\Notificacoes\\Database\\Seeders\\NotificacoesDatabaseSeeder` (ou via `DatabaseSeeder` / `SeedCatalog`).

## Demonstração local

`GET /notificacoes/demo/create` — apenas em ambiente `local` ou `testing`; cria notificações de exemplo para o utilizador atual.
