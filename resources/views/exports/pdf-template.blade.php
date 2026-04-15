<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — JUBAF</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #1e293b;
            background: white;
        }

        /* Container Principal */
        .document-container {
            width: 100%;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        /* --- Header --- */
        .top-bar {
            background: #1e293b;
            color: white;
            padding: 12px 20px;
            display: table;
            width: 100%;
            border-bottom: 2px solid #0f172a;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 50%;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 50%;
        }

        .brand-wrapper {
            display: inline-block;
        }

        .logo-img {
            vertical-align: middle;
            width: 32px;
            height: 32px;
            margin-right: 10px;
            display: inline-block;
        }

        .brand-info {
            display: inline-block;
            vertical-align: middle;
        }

        .brand-text {
            font-size: 16px;
            font-weight: 900;
            letter-spacing: -0.5px;
            text-transform: uppercase;
            color: #fff;
            display: block;
        }

        .brand-subtext {
            font-size: 8px;
            opacity: 0.7;
            font-weight: 600;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        .report-title h1 {
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #60a5fa;
            margin: 0;
        }

        .report-title p {
            font-size: 8px;
            opacity: 0.8;
            font-weight: 700;
            margin-top: 2px;
            color: #cbd5e1;
            text-transform: uppercase;
        }

        /* --- Summary Grid --- */
        .section {
            width: 100%;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-grid {
            width: 100%;
            display: table;
            table-layout: fixed;
            background: #fff;
        }

        .summary-cell {
            display: table-cell;
            padding: 10px 20px;
            border-right: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .summary-cell:last-child {
            border-right: none;
        }

        .label {
            font-size: 7px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            display: block;
            margin-bottom: 4px;
        }

        .value {
            font-size: 10px;
            font-weight: 800;
            color: #0f172a;
        }

        /* --- Main Data Table --- */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            /* Allow auto layout for generic columns, or fixed if desired. Auto is safer for generic. */
        }

        .data-table th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 8px;
            text-align: left;
            padding: 8px 10px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .data-table td {
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:nth-child(even) {
            background: #fdfdfd;
        }

        /* --- Footer --- */
        .footer {
            background: #f8fafc;
            padding: 10px 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 8px;
            color: #94a3b8;
            display: table;
            width: 100%;
        }

        .footer-left { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }

        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <div class="document-container">
        <!-- Header -->
        <div class="top-bar">
            <div class="header-left">
                <div class="brand-wrapper">
                    <img src="{{ public_path('images/logo-icon.svg') }}" class="logo-img" alt="">
                    <div class="brand-info">
                        <span class="brand-text">JUBAF</span>
                        <span class="brand-subtext">Plataforma modular</span>
                    </div>
                </div>
            </div>
            <div class="header-right">
                <div class="report-title">
                    <h1>{{ $title }}</h1>
                    <p>Relatório de Gestão - Sistema Integrado</p>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="section">
            <div class="summary-grid">
                <div class="summary-cell">
                    <span class="label">Total de Registros</span>
                    <div class="value">{{ count($data) }}</div>
                </div>
                <div class="summary-cell">
                    <span class="label">Data de Geração</span>
                    <div class="value">{{ now()->format('d/m/Y') }}</div>
                </div>
                <div class="summary-cell">
                    <span class="label">Horário</span>
                    <div class="value">{{ now()->format('H:i:s') }}</div>
                </div>
                <div class="summary-cell">
                    <span class="label">Solicitado por</span>
                    <div class="value">
                        {{ auth()->check() ? auth()->user()->name : 'Sistema' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="section" style="border-bottom: none;">
            <table class="data-table">
                <thead>
                    <tr>
                        @foreach($columns as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                        <tr>
                            @foreach(array_keys($columns) as $key)
                                <td>
                                    @php
                                        $value = is_object($row) ? ($row->{$key} ?? null) : ($row[$key] ?? null);
                                        // Formatting Logic
                                        if (is_null($value)) {
                                            echo '<span style="color:#94a3b8;">-</span>';
                                        } elseif (is_bool($value)) {
                                            echo $value ? 'Sim' : 'Não';
                                        } elseif ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
                                            echo $value->format('d/m/Y H:i:s');
                                        } elseif (is_numeric($value) && strpos((string)$value, '.') !== false) {
                                            // Heuristic for money/decimals
                                            echo number_format((float)$value, 2, ',', '.');
                                        } else {
                                            echo $value;
                                        }
                                    @endphp
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) }}" class="text-center" style="padding: 20px; color: #94a3b8; text-align: center;">
                                Nenhum registro encontrado para os filtros selecionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <strong>JUBAF</strong> &bull; Plataforma modular
            </div>
            <div class="footer-right">
                Documento gerado eletronicamente em {{ now()->format('d/m/Y \à\s H:i:s') }}
            </div>
        </div>
    </div>

</body>
</html>
