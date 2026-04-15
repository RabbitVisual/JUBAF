<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinição de Senha - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 200px;
            height: auto;
            margin-bottom: 15px;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .email-header p {
            color: #d1fae5;
            margin: 0;
            font-size: 14px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .greeting strong {
            color: #059669;
        }
        .content {
            color: #4b5563;
            font-size: 15px;
            margin-bottom: 25px;
        }
        .info-box {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid #10b981;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            text-align: center;
        }
        .info-box p {
            color: #065f46;
            margin: 10px 0;
            font-size: 14px;
        }
        .info-box .expire-time {
            font-weight: 700;
            font-size: 16px;
            color: #047857;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 40px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            transition: all 0.3s ease;
        }
        .button:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.5);
            transform: translateY(-2px);
        }
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .warning-box h3 {
            color: #92400e;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .warning-box p {
            color: #78350f;
            margin: 5px 0;
            font-size: 14px;
        }
        .link-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            word-break: break-all;
        }
        .link-box p {
            color: #64748b;
            font-size: 12px;
            margin: 5px 0;
        }
        .link-box a {
            color: #10b981;
            word-break: break-all;
            text-decoration: none;
        }
        .footer {
            background: #1f2937;
            color: #9ca3af;
            padding: 30px 20px;
            text-align: center;
            font-size: 13px;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer strong {
            color: #ffffff;
        }
        .footer a {
            color: #10b981;
            text-decoration: none;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .button {
                padding: 14px 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🔐 Redefinição de Senha</h1>
            <p>{{ config('app.name') }} - Secretaria Municipal de Agricultura</p>
        </div>

        <div class="email-body">
            <div class="greeting">
                Olá <strong>{{ $user->name ?? 'Usuário' }}</strong>,
            </div>

            <div class="content">
                <p>Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.</p>
            </div>

            <!-- Informações Importantes -->
            <div class="info-box">
                <p><strong>⏰ Este link de redefinição de senha expira em <span class="expire-time">{{ $expire }} minutos</span>.</strong></p>
                <p style="margin-top: 10px;">Por segurança, use o link o quanto antes para redefinir sua senha.</p>
            </div>

            <!-- Botão de Ação -->
            <div class="button-container">
                <a href="{{ $url }}" class="button">🔑 Redefinir Minha Senha</a>
            </div>

            <!-- Aviso de Segurança -->
            <div class="warning-box">
                <h3>⚠️ Importante</h3>
                <p><strong>Se você não solicitou a redefinição de senha, ignore este e-mail.</strong></p>
                <p style="margin-top: 10px;">Nenhuma ação adicional é necessária. Sua conta permanece segura.</p>
            </div>

            <!-- Link Alternativo -->
            <div class="link-box">
                <p><strong>Problemas ao clicar no botão?</strong></p>
                <p>Copie e cole o link abaixo no seu navegador:</p>
                <p><a href="{{ $url }}">{{ $url }}</a></p>
            </div>

            <!-- Instruções Adicionais -->
            <div class="content" style="margin-top: 30px;">
                <p style="font-size: 14px; color: #6b7280;">
                    <strong>Dicas de Segurança:</strong><br>
                    • Use uma senha forte com no mínimo 8 caracteres<br>
                    • Combine letras maiúsculas, minúsculas, números e símbolos<br>
                    • Não compartilhe sua senha com ninguém<br>
                    • Mantenha sua senha em local seguro
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>{{ config('app.name') }}</strong></p>
            <p>Secretaria Municipal de Agricultura</p>
            <p>Prefeitura Municipal de Coração de Maria - Bahia</p>
            <p style="margin-top: 20px; font-size: 11px; color: #6b7280;">
                Este é um e-mail automático. Por favor, não responda a esta mensagem.<br>
                Se você não solicitou esta redefinição de senha, entre em contato com o suporte imediatamente.
            </p>
            <p style="margin-top: 15px;">
                <a href="{{ config('app.url') }}">Acessar o Sistema</a>
            </p>
        </div>
    </div>
</body>
</html>
