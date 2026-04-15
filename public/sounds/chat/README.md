# Arquivos de Som do Chat

## Onde colocar os arquivos de som

Coloque seus arquivos de áudio nesta pasta: `public/sounds/chat/`

## Nome do arquivo

O sistema procura pelo arquivo: **`notification.mp3`**

## Formatos suportados

- `.mp3` (recomendado - melhor compatibilidade)
- `.wav` (também funciona, mas arquivos maiores)
- `.ogg` (menor tamanho, mas menor compatibilidade)

## Como adicionar

1. Baixe ou crie um arquivo de som de notificação
2. Renomeie para `notification.mp3`
3. Coloque na pasta `public/sounds/chat/notification.mp3`
4. O sistema detectará automaticamente e tocará quando houver novas mensagens

## Exemplo de URLs de som gratuitos

Você pode baixar sons de notificação gratuitos de:
- https://freesound.org/
- https://mixkit.co/free-sound-effects/notification/
- https://www.zapsplat.com/sound-effect-categories/notification-sounds/

## Configuração

O som pode ser habilitado/desabilitado nas configurações do chat em:
- Admin: `/admin/chat/config`
- Opção: "Som de Notificação"

## Personalização

Se quiser usar outro arquivo ou nome, edite as views:
- `Modules/Chat/resources/views/admin/show.blade.php`
- `Modules/Chat/resources/views/co-admin/show.blade.php`
- `Modules/Chat/resources/views/public/widget.blade.php`

Procure por `/sounds/chat/notification.mp3` e altere para o caminho desejado.

