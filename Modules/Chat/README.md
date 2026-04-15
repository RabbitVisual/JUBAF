# Módulo Chat - Sistema Completo em Tempo Real

## 📋 Visão Geral

O módulo **Chat** é um sistema completo de chat institucional para suporte ao público e comunicação interna, totalmente integrado com WebSockets em tempo real, fallback automático para polling, e integração completa com o módulo de Notificações.

## ✨ Funcionalidades Principais

### 🚀 Tempo Real Completo
- ✅ **Polling Nativo**: Sistema funciona perfeitamente com polling (sem dependências externas)
- ✅ **Redis Opcional**: Suporte a Redis para WebSockets (opcional, não obrigatório)
- ✅ **Fallback Automático**: Polling automático quando WebSockets não estão disponíveis
- ✅ **Broadcasting Laravel**: Eventos Laravel para broadcasting em tempo real
- ✅ **Sem Dependências Externas**: Funciona 100% sem Pusher ou serviços externos

### 💬 Recursos de Chat
- ✅ **Chat Público**: Widget para visitantes do site
- ✅ **Validação de CPF**: CPF obrigatório e validado para chat público
- ✅ **Prevenção de Múltiplas Sessões**: Um CPF = Uma sessão ativa (impede spam)
- ✅ **Chat Interno**: Comunicação entre usuários do sistema
- ✅ **Indicadores de Digitação**: Mostra quando alguém está digitando
- ✅ **Status de Sessão**: Aguardando, Ativo, Encerrado
- ✅ **Atribuição de Atendentes**: Sistema de atribuição automática ou manual
- ✅ **Contadores de Não Lidas**: Contadores separados para visitante e atendente
- ✅ **Histórico Completo**: Todas as mensagens são salvas e podem ser consultadas

### 🔔 Integração com Notificações
- ✅ **Notificações Automáticas**: Notifica atendentes sobre novas mensagens
- ✅ **Notificações de Nova Sessão**: Alerta quando há nova sessão aguardando
- ✅ **Integração Completa**: Usa o módulo Notificacoes para todas as notificações

### 🎨 Interface Moderna
- ✅ **Design Responsivo**: Funciona perfeitamente em desktop e mobile
- ✅ **Dark Mode**: Suporte completo a modo escuro
- ✅ **Widget Flutuante**: Widget moderno para chat público
- ✅ **Interface Admin**: Painel completo para gerenciamento

## 🏗️ Estrutura

```
Modules/Chat/
├── app/
│   ├── Events/
│   │   ├── ChatMessageSent.php          # Evento quando mensagem é enviada
│   │   ├── ChatSessionUpdated.php       # Evento quando sessão é atualizada
│   │   └── UserTyping.php               # Evento de indicador de digitação
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── ChatAdminController.php
│   │   │   ├── Api/
│   │   │   │   └── ChatApiController.php
│   │   │   └── ChatPublicController.php
│   ├── Models/
│   │   ├── ChatConfig.php               # Configurações do chat
│   │   ├── ChatMessage.php              # Model de mensagens
│   │   └── ChatSession.php              # Model de sessões
│   ├── Services/
│   │   └── ChatService.php              # Service principal do chat
│   └── Providers/
│       ├── ChatServiceProvider.php
│       ├── EventServiceProvider.php
│       └── RouteServiceProvider.php
├── database/
│   └── migrations/
│       ├── create_chat_configs_table.php
│       ├── create_chat_sessions_table.php
│       └── create_chat_messages_table.php
├── resources/
│   └── views/
│       ├── admin/
│       ├── co-admin/
│       └── public/
└── routes/
    ├── api.php
    └── web.php
```

## 🔧 Configuração

### 1. Variáveis de Ambiente

Adicione ao seu `.env`:

```env
# Broadcasting (sem dependências externas - usar apenas Redis ou log)
BROADCAST_DRIVER=redis  # ou 'log', 'null' (sem Pusher)

# Redis (se usar Redis para WebSockets)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

**Nota**: O sistema funciona perfeitamente com `BROADCAST_DRIVER=log` usando apenas polling, sem necessidade de serviços externos.

### 2. Instalar Dependências

**Nenhuma dependência externa necessária!** O sistema funciona com polling nativo.

Se quiser usar WebSockets com Redis (opcional):
```bash
npm install laravel-echo socket.io-client
```

### 3. Executar Migrações

```bash
php artisan migrate
```

### 4. Configurar Broadcasting Channels

Os canais já estão configurados em `routes/channels.php`:

- `chat.session.{sessionId}` - Canal público para sessões
- `private-chat.session.{sessionId}` - Canal privado para sessões
- `chat.agents` - Canal para atendentes

## 📡 Eventos de Broadcasting

### ChatMessageSent
Disparado quando uma mensagem é enviada.

**Canais:**
- `private-chat.session.{sessionId}` (atendentes)
- `chat.session.{sessionId}` (visitantes)
- `chat.agents` (todos os atendentes)

**Dados:**
```json
{
  "id": 1,
  "chat_session_id": 1,
  "sender_type": "visitor|user|system",
  "message": "Texto da mensagem",
  "created_at": "2025-01-20T10:00:00.000000Z",
  "sender": {
    "id": 1,
    "name": "Nome do Usuário"
  }
}
```

### ChatSessionUpdated
Disparado quando uma sessão é atualizada (atribuição, status, etc).

**Canais:**
- `private-chat.session.{sessionId}`
- `chat.session.{sessionId}`
- `chat.agents`

### UserTyping
Disparado quando um usuário está digitando.

**Canais:**
- `chat.session.{sessionId}`

## 🎯 Uso

### Chat Público (Widget)

O widget é incluído automaticamente nas páginas públicas. Para incluir manualmente:

```blade
@include('chat::public.widget')
```

### Chat Admin/Co-Admin

Acesse as rotas:
- `/co-admin/chat` - Lista de sessões
- `/co-admin/chat/{id}` - Visualizar sessão
- `/admin/chat/config` - Configurações

### API Endpoints

#### Para Visitantes (Público)
```
GET  /chat/status                    # Verificar disponibilidade
POST /chat/start                     # Iniciar sessão
GET  /chat/session/{sessionId}       # Obter sessão e mensagens
POST /chat/session/{sessionId}/message  # Enviar mensagem
POST /chat/session/{sessionId}/typing   # Indicador de digitação
```

#### Para Atendentes (Autenticado)
```
GET  /api/chat/sessions             # Listar sessões ativas
GET  /api/chat/session/{id}/messages # Obter mensagens
POST /api/chat/session/{id}/message  # Enviar mensagem
POST /api/chat/session/{id}/typing   # Indicador de digitação
POST /api/chat/session/{id}/read     # Marcar como lida
PUT  /api/chat/session/{id}/status   # Atualizar status
```

## 💻 JavaScript

### Inicialização

O sistema de chat é inicializado automaticamente nas views. Para inicializar manualmente:

```javascript
// Inicializar chat
ChatSystem.init(sessionId, sessionDbId, userId);

// Carregar mensagens
ChatSystem.loadMessages();

// Enviar mensagem
ChatSystem.sendMessage();
```

### Configuração Global

O sistema detecta automaticamente:
- `window.BROADCAST_DRIVER` - Driver de broadcasting
- `window.PUSHER_APP_KEY` - Chave do Pusher
- `window.PUSHER_APP_CLUSTER` - Cluster do Pusher
- `window.currentUserId` - ID do usuário atual

## 🔌 Integração com Notificações

O módulo Chat está totalmente integrado com o módulo Notificacoes:

- **Nova Mensagem**: Notifica o atendente atribuído
- **Nova Sessão**: Notifica todos os atendentes disponíveis
- **Sessão Atribuída**: Notifica o atendente que recebeu a sessão

## 📊 Service: ChatService

O `ChatService` centraliza toda a lógica do chat:

```php
use Modules\Chat\App\Services\ChatService;

$chatService = app(ChatService::class);

// Enviar mensagem
$message = $chatService->sendMessage($session, $text, 'user', $user);

// Atribuir sessão
$chatService->assignSession($session, $agent);

// Fechar sessão
$chatService->closeSession($session, 'Motivo do encerramento');

// Marcar como lida
$chatService->markAsRead($session, 'user');

// Obter estatísticas
$stats = $chatService->getStatistics();
```

## 🎨 Personalização

### Configurações do Chat

Acesse `/admin/chat/config` para configurar:
- Habilitar/desabilitar chat
- Mensagem de boas-vindas
- Mensagem offline
- Horários de funcionamento
- Timeout de auto-encerramento
- Limite de sessões simultâneas

### Estilos

Os estilos podem ser personalizados via Tailwind CSS. As classes principais:
- `.chat-widget-container` - Container do widget
- `.chat-messages-container` - Container de mensagens
- `.chat-message` - Mensagem individual

## 🔒 Segurança

- ✅ Autenticação obrigatória para endpoints de API
- ✅ Verificação de permissões para atendentes
- ✅ Validação de sessões
- ✅ Proteção CSRF em todos os formulários
- ✅ Sanitização de mensagens
- ✅ Rate limiting (recomendado)

## 🚀 Performance

- ✅ Polling otimizado (3 segundos)
- ✅ WebSockets para tempo real
- ✅ Cache de sessões ativas
- ✅ Índices no banco de dados
- ✅ Lazy loading de mensagens antigas

## 📝 Logs

O sistema registra:
- Erros ao enviar mensagens
- Erros de broadcasting
- Erros de notificações
- Atividades importantes

## 🐛 Troubleshooting

### WebSockets não funcionam
1. Verifique `BROADCAST_DRIVER` no `.env`
2. Verifique credenciais do Pusher/Redis
3. O sistema automaticamente usa polling como fallback

### Mensagens não aparecem
1. Verifique console do navegador
2. Verifique logs do Laravel
3. Verifique se eventos estão sendo disparados

### Notificações não chegam
1. Verifique se módulo Notificacoes está habilitado
2. Verifique permissões dos usuários
3. Verifique logs do Laravel

## 📚 Recursos Adicionais

- [Documentação Laravel Broadcasting](https://laravel.com/docs/broadcasting)
- [Documentação Pusher](https://pusher.com/docs)
- [Documentação Redis](https://redis.io/docs)

## 🎯 Próximas Melhorias

- [ ] Suporte a anexos e arquivos
- [ ] Busca de mensagens
- [ ] Histórico exportável
- [ ] Chat em grupo
- [ ] Reações em mensagens
- [ ] Mensagens de voz
- [ ] Integração com WhatsApp/Telegram

## 👥 Contribuição

Para contribuir com melhorias:
1. Siga os padrões de código do projeto
2. Adicione testes quando possível
3. Documente mudanças importantes
4. Mantenha compatibilidade com versões anteriores

---

**Versão**: 2.0.0  
**Data**: 2025-01-20  
**Status**: ✅ Completo e Funcional

