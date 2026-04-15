<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notificacao->title }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-error { background: #fee2e2; color: #991b1b; }
        .badge-alert { background: #fed7aa; color: #9a3412; }
        .badge-system { background: #f1f5f9; color: #475569; }
        .title {
            font-size: 24px;
            font-weight: 700;
            margin: 20px 0;
            color: #111827;
        }
        .message {
            font-size: 16px;
            color: #4b5563;
            margin: 20px 0;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
        .button:hover {
            background: #5568d3;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 28px;">{{ config('app.name') }}</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Sistema de Notificações</p>
    </div>

    <div class="content">
        <span class="badge badge-{{ $notificacao->type }}">
            {{ $notificacao->type_texto }}
        </span>

        <h2 class="title">{{ $notificacao->title }}</h2>

        <div class="message">
            {!! nl2br(e($notificacao->message)) !!}
        </div>

        @if($notificacao->action_url)
            <a href="{{ $notificacao->action_url }}" class="button">
                Ver Detalhes
            </a>
        @endif

        @if($notificacao->module_source)
            <p style="font-size: 14px; color: #6b7280; margin-top: 20px;">
                <strong>Módulo:</strong> {{ $notificacao->module_source }}
            </p>
        @endif

        <div class="footer">
            <p>Esta é uma notificação automática do sistema {{ config('app.name') }}.</p>
            <p>Você recebeu este email porque está cadastrado em nosso sistema.</p>
            <p style="margin-top: 10px;">
                Data: {{ $notificacao->created_at->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>
</body>
</html>

