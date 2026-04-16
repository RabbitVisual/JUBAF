<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $plan->title }} — {{ \App\Support\SiteBranding::siteName() }} · Plano de leitura</title>
    @preloadFonts
    <link href="{{ asset('vendor/fontawesome-pro/css/all.css') }}" rel="stylesheet">
    <style>
        :root {
            --jubaf-blue: #2563eb;
            --jubaf-blue-dark: #1e40af;
            --primary: #1c1917;
            --secondary: #64748b;
            --border: #e7e5e4;
            --bg-even: #fafaf9;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(180deg, #e7e5e4 0%, #d6d3d1 100%);
            margin: 0;
            padding: 32px 24px 100px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .page {
            background: #fffefb;
            width: 297mm;
            min-height: 210mm;
            height: auto;
            padding: 14mm 15mm;
            box-shadow:
                0 25px 50px -12px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(37, 99, 235, 0.08);
            position: relative;
            display: flex;
            flex-direction: column;
            margin-bottom: 40px;
            border-radius: 4px;
        }

        .brand-strip {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--jubaf-blue) 0%, var(--jubaf-blue-dark) 100%);
            border-radius: 4px 4px 0 0;
        }

        header {
            text-align: center;
            margin-bottom: 8mm;
            padding-bottom: 5mm;
            padding-top: 2mm;
            border-bottom: 2px solid var(--jubaf-blue);
        }

        .brand-lockup {
            font-size: 8pt;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--jubaf-blue);
            margin-bottom: 3mm;
        }

        h1 {
            font-size: 17pt;
            text-transform: none;
            color: var(--primary);
            margin: 0;
            letter-spacing: -0.02em;
            font-weight: 800;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 9pt;
            color: var(--secondary);
            margin-top: 3mm;
            font-weight: 600;
        }

        .subtitle span {
            color: var(--jubaf-blue);
        }

        .content-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4mm;
            width: 100%;
        }

        .reading-card {
            background: #fff;
            border: 1px solid var(--border);
            border-top: 2px solid rgba(37, 99, 235, 0.35);
            padding: 3mm;
            break-inside: avoid;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            gap: 2px;
            border-radius: 2px;
        }

        .reading-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--jubaf-blue);
            padding-bottom: 2px;
            margin-bottom: 2px;
        }

        .day-label {
            font-size: 8pt;
            font-weight: 800;
            color: var(--jubaf-blue-dark, #0f766e);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .reading-content {
            font-size: 8pt;
            color: #334155;
            flex: 1;
        }

        .reading-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2px;
            border-bottom: 1px dashed #f1f5f9;
            padding-bottom: 1px;
        }

        .reading-row:last-child {
            border-bottom: none;
        }

        .check-box {
            width: 12px;
            height: 12px;
            border: 1px solid #94a3b8;
            background: white;
            border-radius: 2px;
            flex-shrink: 0;
        }

        footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            padding-top: 3mm;
            text-align: center;
            font-size: 7pt;
            color: var(--secondary);
            line-height: 1.5;
        }

        footer .jubaf {
            font-weight: 800;
            color: var(--jubaf-blue);
            letter-spacing: 0.06em;
        }

        .fab-print {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: var(--jubaf-blue);
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 12px 30px -8px rgba(37, 99, 235, 0.45);
            cursor: pointer;
            transition: transform 0.2s;
            border: none;
            z-index: 100;
        }
        .fab-print:hover {
            transform: scale(1.06);
            background: #0f766e;
        }
        .fab-print svg {
            width: 24px;
            height: 24px;
            fill: currentColor;
        }

        .screen-hint {
            max-width: 297mm;
            margin: 0 auto 16px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-size: 12px;
            color: #57534e;
            text-align: center;
        }
        .screen-hint strong {
            color: var(--jubaf-blue);
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            html, body {
                height: auto;
                background: white;
            }

            body {
                margin: 0;
                padding: 0;
                display: block;
            }

            .screen-hint,
            .fab-print {
                display: none !important;
            }

            .page {
                box-shadow: none;
                width: 100%;
                max-width: none;
                padding: 0;
                margin: 0;
                border-radius: 0;
                background: white;
            }

            .brand-strip {
                display: none;
            }

            header {
                margin-bottom: 5mm;
                page-break-after: avoid;
            }

            .content-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 4mm;
            }

            footer {
                margin-top: 5mm;
            }
        }
    </style>
</head>
<body>

    <p class="screen-hint">
        <strong>JUBAF — Painel Unijovem.</strong> Checklist do plano para leitura física ou arquivo. Usa o botão para imprimir ou guardar PDF.
    </p>

    <button type="button" class="fab-print" onclick="window.print()" title="Imprimir ou guardar como PDF" aria-label="Imprimir ou PDF">
        <i class="fa-duotone fa-print fa-lg"></i>
    </button>

    <div class="page">
        <div class="brand-strip" aria-hidden="true"></div>
        <header>
            <div class="brand-lockup">{{ \App\Support\SiteBranding::siteName() }} · Plano de leitura</div>
            <h1>{{ $plan->title }}</h1>
            <div class="subtitle">
                <span>Painel de jovens</span> — marca cada dia ao ler na Bíblia ou nos materiais do plano
            </div>
        </header>

        <div class="content-grid">
            @foreach($days as $day)
                <div class="reading-card">
                    <div class="reading-header">
                        <span class="day-label">Dia {{ str_pad($day->day_number, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="reading-content">
                        @foreach($day->contents as $content)
                            <div class="reading-row">
                                <span style="font-size: 8pt; color: #334155;">
                                    @if($content->type === 'scripture' && $content->book)
                                        <strong>{{ $content->book->name }}</strong>
                                        {{ $content->chapter_start }}
                                        @if($content->chapter_end && $content->chapter_end != $content->chapter_start)-{{ $content->chapter_end }}@endif
                                        @if($content->verse_start):{{ $content->verse_start }}@endif

                                    @elseif($content->type === 'devotional')
                                        <i class="fa-solid fa-pen-nib text-xs mr-1 opacity-50"></i>
                                        <strong>{{ $content->title ?: 'Devocional' }}</strong>

                                    @elseif($content->type === 'video')
                                        <i class="fa-solid fa-play text-xs mr-1 opacity-50"></i>
                                        <strong>{{ $content->title ?: 'Vídeo' }}</strong>
                                    @endif
                                </span>
                                <div class="check-box"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <footer>
            <span class="jubaf">JUBAF</span> · Painel Unijovem · Documento gerado em {{ now()->format('d/m/Y H:i') }}
            <br><span style="opacity:0.88">{{ \App\Support\SiteBranding::siteTagline() }}</span>
        </footer>
    </div>

</body>
</html>
