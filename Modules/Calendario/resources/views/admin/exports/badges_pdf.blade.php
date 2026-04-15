<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 12mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #0f172a; }
        .header { text-align: center; margin-bottom: 14px; border-bottom: 2px solid #1e40af; padding-bottom: 8px; }
        .header img { max-height: 42px; }
        .title { font-size: 14px; font-weight: bold; color: #1e40af; }
        .sub { font-size: 10px; color: #64748b; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px; text-align: left; }
        th { background: #eff6ff; font-size: 10px; text-transform: uppercase; }
        .token { font-family: DejaVu Sans Mono, monospace; font-size: 9px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="header">
        @if(!empty($logoDataUri))
            <img src="{{ $logoDataUri }}" alt="JUBAF">
        @else
            <div class="title">JUBAF</div>
        @endif
        <div class="title" style="margin-top:8px;">{{ $event->title }}</div>
        <div class="sub">{{ $event->starts_at?->format('d/m/Y H:i') }} @if($event->location) · {{ $event->location }} @endif</div>
    </div>
    <p style="font-size:10px;color:#64748b;">Credenciais / check-in — gerado em {{ now()->format('d/m/Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Estado</th>
                <th>Código check-in</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->user?->name }}</td>
                    <td>{{ $r->user?->email }}</td>
                    <td>{{ $r->status }}</td>
                    <td class="token">{{ $r->checkin_token }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
