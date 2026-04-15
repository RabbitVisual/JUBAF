<?php

namespace Modules\Homepage\App\Support;

use App\Support\SiteBranding;

/**
 * Modelos HTML para campanhas da newsletter (estilos inline, compatíveis com a maioria dos clientes).
 * Marcadores: ___SITE_NAME___, ___YEAR___, ___HOMEPAGE_URL___, ___CONTATO_URL___
 */
final class HomepageNewsletterTemplates
{
    public const TOKEN_SITE_NAME = '___SITE_NAME___';

    public const TOKEN_YEAR = '___YEAR___';

    public const TOKEN_HOMEPAGE_URL = '___HOMEPAGE_URL___';

    public const TOKEN_CONTATO_URL = '___CONTATO_URL___';

    /**
     * @return array<string, string>
     */
    public static function tokenLabels(): array
    {
        return [
            self::TOKEN_SITE_NAME => 'Nome do site (JUBAF)',
            self::TOKEN_YEAR => 'Ano atual',
            self::TOKEN_HOMEPAGE_URL => 'URL da página inicial',
            self::TOKEN_CONTATO_URL => 'URL da página de contato',
        ];
    }

    public static function applyTokens(string $html): string
    {
        return str_replace(
            [self::TOKEN_SITE_NAME, self::TOKEN_YEAR, self::TOKEN_HOMEPAGE_URL, self::TOKEN_CONTATO_URL],
            [
                e(SiteBranding::siteName()),
                date('Y'),
                url('/'),
                route('contato'),
            ],
            $html
        );
    }

    /**
     * @return list<array{id: string, name: string, description: string, html: string}>
     */
    public static function definitions(): array
    {
        return [
            [
                'id' => 'boletim-regional',
                'name' => 'Boletim regional',
                'description' => 'Cabeçalho JUBAF, destaque, texto e rodapé — ideal para novidades da região.',
                'html' => self::templateBoletimRegional(),
            ],
            [
                'id' => 'evento',
                'name' => 'Convite a evento',
                'description' => 'Data, hora, local e botão de confirmação ou site.',
                'html' => self::templateEvento(),
            ],
            [
                'id' => 'comunicado',
                'name' => 'Comunicado oficial',
                'description' => 'Texto direto da diretoria, tom formal e curto.',
                'html' => self::templateComunicado(),
            ],
            [
                'id' => 'lista-links',
                'name' => 'Resumo com lista',
                'description' => 'Título + lista de pontos com links.',
                'html' => self::templateListaLinks(),
            ],
            [
                'id' => 'minimal',
                'name' => 'Mínimo (só estrutura)',
                'description' => 'Parágrafo único e assinatura — para colar o seu HTML.',
                'html' => self::templateMinimal(),
            ],
        ];
    }

    private static function templateBoletimRegional(): string
    {
        return <<<HTML
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0;padding:0;background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 24px rgba(15,23,42,0.08);">
        <tr>
          <td style="background:linear-gradient(135deg,#1d4ed8 0%,#1e3a8a 100%);background-color:#1d4ed8;padding:28px 24px;text-align:center;">
            <p style="margin:0;font-family:Georgia,'Times New Roman',serif;font-size:22px;font-weight:bold;color:#ffffff;">___SITE_NAME___</p>
            <p style="margin:8px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#bfdbfe;">Juventude Batista Feirense · Feira de Santana e região</p>
          </td>
        </tr>
        <tr>
          <td style="padding:28px 24px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:1.6;color:#1e293b;">
            <p style="margin:0 0 16px;font-size:18px;font-weight:bold;color:#0f172a;">Olá!</p>
            <p style="margin:0 0 16px;">Escreva aqui o corpo do seu comunicado regional. Pode usar <strong>negrito</strong>, <em>itálico</em> e <a href="___HOMEPAGE_URL___" style="color:#2563eb;">links para o site</a>.</p>
            <p style="margin:0 0 24px;">Subtítulo ou segundo parágrafo com mais detalhes para as igrejas e jovens da ASBAF.</p>
            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto;">
              <tr>
                <td style="border-radius:8px;background:#2563eb;">
                  <a href="___HOMEPAGE_URL___" style="display:inline-block;padding:14px 28px;font-family:Arial,Helvetica,sans-serif;font-size:15px;font-weight:bold;color:#ffffff;text-decoration:none;">Visitar o site</a>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:20px 24px;background:#f8fafc;border-top:1px solid #e2e8f0;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.5;color:#64748b;text-align:center;">
            <p style="margin:0;">___SITE_NAME___ · ___YEAR___</p>
            <p style="margin:8px 0 0;"><a href="___CONTATO_URL___" style="color:#2563eb;">Fale connosco</a> · <a href="___HOMEPAGE_URL___" style="color:#2563eb;">Página inicial</a></p>
            <p style="margin:12px 0 0;font-size:11px;color:#94a3b8;">Recebeu este e-mail por estar inscrito na newsletter regional da JUBAF.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
HTML;
    }

    private static function templateEvento(): string
    {
        return <<<HTML
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0;padding:0;background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;border:1px solid #e2e8f0;">
        <tr>
          <td style="padding:24px;font-family:Arial,Helvetica,sans-serif;">
            <p style="margin:0 0 8px;font-size:12px;font-weight:bold;letter-spacing:0.08em;text-transform:uppercase;color:#2563eb;">Convite · ___SITE_NAME___</p>
            <h1 style="margin:0 0 20px;font-size:24px;line-height:1.25;color:#0f172a;">Título do evento</h1>
            <table role="presentation" width="100%" style="margin-bottom:20px;">
              <tr>
                <td style="padding:12px 16px;background:#eff6ff;border-radius:8px;font-size:15px;color:#1e3a8a;">
                  <strong>Data:</strong> __/__/____ &nbsp;·&nbsp; <strong>Hora:</strong> __h__<br>
                  <strong>Local:</strong> Nome do local · Feira de Santana / BA
                </td>
              </tr>
            </table>
            <p style="margin:0 0 16px;font-size:16px;line-height:1.6;color:#334155;">Descrição do programa, público-alvo (jovens, lideranças) e o que levar.</p>
            <p style="margin:0 0 20px;font-size:16px;line-height:1.6;color:#334155;">Inscrições ou dúvidas: responda a este e-mail ou use a página de contacto.</p>
            <a href="___CONTATO_URL___" style="display:inline-block;padding:12px 22px;background:#0f172a;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:bold;font-size:14px;">Confirmar interesse / Contactar</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
HTML;
    }

    private static function templateComunicado(): string
    {
        return <<<HTML
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0;padding:0;background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;padding:32px 28px;border:1px solid #e5e7eb;">
        <tr>
          <td style="font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:1.65;color:#111827;">
            <p style="margin:0 0 8px;font-size:13px;color:#6b7280;">Comunicado da diretoria · ___SITE_NAME___</p>
            <h2 style="margin:0 0 20px;font-size:20px;color:#111827;">Assunto do comunicado</h2>
            <p style="margin:0 0 16px;">Corpo do texto oficial. Informe de forma clara decisões, datas ou orientações para as igrejas da região.</p>
            <p style="margin:0 0 24px;">Segundo parágrafo opcional.</p>
            <p style="margin:0;font-size:15px;color:#374151;">Com os votos de paz,<br><strong>Diretoria ___SITE_NAME___</strong></p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
HTML;
    }

    private static function templateListaLinks(): string
    {
        return <<<HTML
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0;padding:0;background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
        <tr>
          <td style="padding:24px 24px 8px;font-family:Arial,Helvetica,sans-serif;">
            <h2 style="margin:0;font-size:20px;color:#0f172a;">Resumo da semana · ___SITE_NAME___</h2>
            <p style="margin:10px 0 0;font-size:14px;color:#64748b;">___YEAR___ · Feira de Santana e região</p>
          </td>
        </tr>
        <tr>
          <td style="padding:8px 24px 24px;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.55;color:#334155;">
            <ul style="margin:16px 0;padding-left:20px;">
              <li style="margin-bottom:10px;"><a href="___HOMEPAGE_URL___" style="color:#2563eb;">Notícia ou artigo 1</a> — breve descrição.</li>
              <li style="margin-bottom:10px;"><a href="___HOMEPAGE_URL___" style="color:#2563eb;">Notícia ou artigo 2</a> — breve descrição.</li>
              <li style="margin-bottom:10px;"><a href="___CONTATO_URL___" style="color:#2563eb;">Contacto / inscrições</a> — como participar.</li>
            </ul>
            <p style="margin:20px 0 0;font-size:14px;color:#64748b;">Edite os itens acima com os links reais do blog ou do site.</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
HTML;
    }

    private static function templateMinimal(): string
    {
        return <<<HTML
<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="margin:0;padding:0;background-color:#f4f6fb;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;width:100%;background:#ffffff;border-radius:8px;padding:24px;">
        <tr>
          <td style="font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:1.6;color:#1e293b;">
            <p style="margin:0 0 16px;">O seu texto aqui. Pode colar HTML adicional abaixo ou substituir todo o bloco.</p>
            <p style="margin:0;font-size:14px;color:#64748b;">___SITE_NAME___ · <a href="___HOMEPAGE_URL___" style="color:#2563eb;">Site</a></p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
HTML;
    }
}
