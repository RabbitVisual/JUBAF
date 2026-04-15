<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jornal Oficial - {{ $month }}/{{ $year }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 25px;
            background: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .coat-of-arms {
            width: 80px;
            height: auto;
            margin: 0 auto 10px auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .coat-of-arms svg {
            width: 60px;
            height: 60px;
            fill: #000;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
        }

        .header h2 {
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0 0 0;
            text-transform: uppercase;
        }

        .meta-header {
            margin-top: 10px;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            font-family: Arial, sans-serif;
        }

        .content-body {
            column-count: 2;
            column-gap: 20px;
            text-align: justify;
        }

        .post {
            margin-bottom: 25px;
            page-break-inside: avoid;
            background: #fff;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }

        .post-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .post-meta {
            font-size: 10px;
            color: #444;
            margin-bottom: 8px;
            font-style: italic;
            font-family: Arial, sans-serif;
        }

        .post-content {
            font-size: 12px;
            line-height: 1.4;
        }

        .post-content p {
            margin: 0 0 10px 0;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            margin: 5px 0;
            display: block;
            border: 1px solid #eee;
            filter: grayscale(100%);
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 5px;
            background: #fff;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .content-body {
                column-count: 2;
            }
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #f3f4f6;
            padding: 15px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-family: sans-serif;
            z-index: 50;
        }

        .btn {
            background: #10b981;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #059669;
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button type="button" onclick="window.print()" class="btn">Imprimir / Salvar PDF</button>
    </div>

    <div class="header">
        <div class="coat-of-arms">
            <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M50 5 L90 20 V50 C90 75 50 95 50 95 C50 95 10 75 10 50 V20 L50 5 Z" fill="none" stroke="black"
                    stroke-width="3" />
                <circle cx="50" cy="45" r="15" fill="none" stroke="black" stroke-width="2" />
                <path d="M35 70 L65 70" stroke="black" stroke-width="2" />
                <text x="50" y="48" font-size="10" text-anchor="middle" font-family="serif">BRASÃO</text>
            </svg>
        </div>

        <h1>Diário Oficial do Município</h1>
        <h2>Relatório de Gestão e Transparência</h2>

        <div class="meta-header">
            <span>Edição Mensal</span>
            <span>Período: {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</span>
            <span>Coração de Maria - BA</span>
        </div>
    </div>

    <div class="content-body">
        @forelse($posts as $post)
            <div class="post">
                <h3 class="post-title">{{ $post->title }}</h3>
                <div class="post-meta">
                    Publicado em: {{ $post->published_at->format('d/m/Y') }} |
                    Categoria: {{ $post->category->name }}
                    @if ($post->demanda)
                        | Ref.: #{{ $post->demanda->id }}
                    @endif
                </div>

                <div class="post-content">
                    {!! $post->content !!}
                </div>

                @if (!empty($post->team_members) && $post->team->count() > 0)
                    <div style="font-size: 10px; margin-top: 5px; border-top: 1px dotted #ccc; padding-top: 3px;">
                        <strong>Resp. Técnico:</strong> {{ $post->team->pluck('nom_pessoa')->join(', ') }}
                    </div>
                @endif
            </div>
        @empty
            <div class="post" style="text-align: center; color: #666; column-span: all; border: none;">
                <p>Nenhuma publicação registrada para este período.</p>
            </div>
        @endforelse
    </div>

    <div class="footer">
        Documento gerado eletronicamente pelo sistema JUBAF em {{ now()->format('d/m/Y H:i:s') }}.<br>
        A autenticidade deste documento pode ser confirmada junto da diretoria da JUBAF.
    </div>
</body>

</html>
