<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Balancete financeiro — JUBAF</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 22mm 18mm 24mm 18mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9.5pt;
            line-height: 1.45;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
        }

        .doc {
            max-width: 100%;
        }

        .header {
            border-bottom: 1.5pt solid #0f766e;
            padding-bottom: 10pt;
            margin-bottom: 14pt;
            overflow: hidden;
        }

        .header__brand {
            display: table;
            width: 100%;
        }

        .header__logo {
            display: table-cell;
            width: 56mm;
            vertical-align: middle;
        }

        .header__logo img {
            max-height: 22mm;
            max-width: 52mm;
        }

        .header__title {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            padding-left: 8pt;
        }

        .header__title h1 {
            margin: 0 0 4pt 0;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.02em;
            color: #0f172a;
        }

        .header__title .sub {
            margin: 0;
            font-size: 8.5pt;
            color: #475569;
            font-weight: normal;
        }

        .meta {
            width: 100%;
            margin-bottom: 14pt;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .meta td {
            padding: 5pt 10pt;
            border: 0.4pt solid #e2e8f0;
            vertical-align: top;
        }

        .meta td.label {
            width: 28%;
            background: #f8fafc;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 7.5pt;
        }

        .meta td.value {
            color: #0f172a;
        }

        .section-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #0f766e;
            margin: 16pt 0 8pt 0;
            padding-bottom: 4pt;
            border-bottom: 0.5pt solid #cbd5e1;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .kpi-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14pt;
            table-layout: fixed;
        }

        .kpi-row td {
            border: 0.4pt solid #e2e8f0;
            padding: 10pt 12pt;
            vertical-align: top;
            width: 33.33%;
        }

        .kpi-row .lbl {
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 4pt;
        }

        .kpi-row .num {
            font-size: 12pt;
            font-weight: bold;
            font-variant-numeric: tabular-nums;
        }

        .kpi-row .in .num {
            color: #047857;
        }

        .kpi-row .out .num {
            color: #be123c;
        }

        .kpi-row .bal .num {
            color: #0f172a;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12pt;
            font-size: 8.5pt;
            page-break-inside: auto;
        }

        table.data thead {
            display: table-header-group;
        }

        table.data tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table.data th {
            background: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
            padding: 6pt 8pt;
            border: 0.4pt solid #cbd5e1;
            font-size: 7.5pt;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        table.data td {
            padding: 5pt 8pt;
            border: 0.4pt solid #e2e8f0;
            vertical-align: top;
        }

        table.data th.right,
        table.data td.right {
            text-align: right;
        }

        table.data td.muted {
            color: #64748b;
            font-size: 8pt;
        }

        table.data td.mono {
            font-family: DejaVu Sans Mono, monospace;
            font-size: 7.5pt;
        }

        .tag {
            display: inline-block;
            padding: 2pt 6pt;
            border-radius: 2pt;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .tag-in {
            background: #ecfdf5;
            color: #065f46;
        }

        .tag-out {
            background: #fff1f2;
            color: #9f1239;
        }

        .footer {
            margin-top: 18pt;
            padding-top: 10pt;
            border-top: 0.4pt solid #e2e8f0;
            font-size: 7.5pt;
            color: #64748b;
            line-height: 1.5;
        }

        .footer strong {
            color: #475569;
        }

        .note {
            margin-top: 8pt;
            font-size: 7.5pt;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="doc">
        <div class="header">
            <div class="header__brand">
                <div class="header__logo">
                    @if (!empty($logoDataUri))
                        <img src="{{ $logoDataUri }}" alt="Logótipo">
                    @else
                        <div style="font-size:16pt;font-weight:bold;color:#0f766e;">JUBAF</div>
                    @endif
                </div>
                <div class="header__title">
                    <h1>Balancete financeiro</h1>
                    <p class="sub">Juventude Batista Feirense · Tesouraria regional</p>
                </div>
            </div>
        </div>

        <table class="meta">
            <tr>
                <td class="label">Período do relatório</td>
                <td class="value">
                    <strong>{{ $periodLabel }}</strong><br>
                    {{ \Carbon\Carbon::parse($from)->format('d/m/Y') }} —
                    {{ \Carbon\Carbon::parse($to)->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td class="label">Emissão</td>
                <td class="value">
                    {{ $generatedAt->timezone(config('app.timezone'))->format('d/m/Y \à\s H:i') }}
                    @if (!empty($issuerName))
                        · Emitido por {{ $issuerName }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Lançamentos no período</td>
                <td class="value">{{ $txCount }} movimento(s)</td>
            </tr>
        </table>

        @php $balance = $totals['in'] - $totals['out']; @endphp
        <table class="kpi-row">
            <tr>
                <td class="in">
                    <div class="lbl">Receitas</div>
                    <div class="num">R$ {{ number_format($totals['in'], 2, ',', '.') }}</div>
                </td>
                <td class="out">
                    <div class="lbl">Despesas</div>
                    <div class="num">R$ {{ number_format($totals['out'], 2, ',', '.') }}</div>
                </td>
                <td class="bal">
                    <div class="lbl">Saldo do período</div>
                    <div class="num">R$ {{ number_format($balance, 2, ',', '.') }}</div>
                </td>
            </tr>
        </table>

        <div class="section-title">Resumo por âmbito</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Âmbito</th>
                    <th>Tipo</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scopeBreakdown as $row)
                    <tr>
                        <td>{{ \Modules\Financeiro\App\Models\FinTransaction::normalizeScopeLabel($row->scope) }}</td>
                        <td>
                            <span class="tag {{ $row->direction === 'in' ? 'tag-in' : 'tag-out' }}">
                                {{ $row->direction === 'in' ? 'Receita' : 'Despesa' }}
                            </span>
                        </td>
                        <td class="right" style="font-variant-numeric: tabular-nums;">R$
                            {{ number_format((float) $row->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="muted">Sem movimentos por âmbito neste período.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">Por categoria</div>
        <table class="data">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Código</th>
                    <th>Grupo</th>
                    <th>Tipo</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($byCategory as $row)
                    @php $cat = $categories[$row->category_id] ?? null; @endphp
                    <tr>
                        <td>{{ $cat->name ?? '—' }}</td>
                        <td class="mono muted">{{ $cat->code ?? '—' }}</td>
                        <td class="muted">
                            {{ $cat ? \Modules\Financeiro\App\Models\FinCategory::groupLabel($cat->group_key) : '—' }}
                        </td>
                        <td>
                            <span class="tag {{ $row->direction === 'in' ? 'tag-in' : 'tag-out' }}">
                                {{ $row->direction === 'in' ? 'Receita' : 'Despesa' }}
                            </span>
                        </td>
                        <td class="right" style="font-variant-numeric: tabular-nums;">R$
                            {{ number_format((float) $row->total, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="muted">Sem movimentos por categoria neste período.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($churchBreakdown->isNotEmpty())
            <div class="section-title">Por igreja (âmbito congregação)</div>
            <table class="data">
                <thead>
                    <tr>
                        <th>Igreja</th>
                        <th class="right">Receitas</th>
                        <th class="right">Despesas</th>
                        <th class="right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($churchBreakdown as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td class="right" style="font-variant-numeric: tabular-nums; color:#047857;">R$
                                {{ number_format($row['in'], 2, ',', '.') }}</td>
                            <td class="right" style="font-variant-numeric: tabular-nums; color:#be123c;">R$
                                {{ number_format($row['out'], 2, ',', '.') }}</td>
                            <td class="right" style="font-variant-numeric: tabular-nums;"><strong>R$
                                    {{ number_format($row['balance'], 2, ',', '.') }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            <strong>Documento institucional</strong> — valores conforme lançamentos registados no sistema, no intervalo
            de datas indicado.
            Conferir sempre com o livro da tesouraria e anexos (recibos, notas, conciliação bancária).
            <p class="note">Gerado automaticamente pelo painel JUBAF. Não requer assinatura manuscrita para consulta
                interna; para assembleia,
                pode ser anexado ao expediente oficial da diretoria.</p>
        </div>
    </div>
</body>

</html>
