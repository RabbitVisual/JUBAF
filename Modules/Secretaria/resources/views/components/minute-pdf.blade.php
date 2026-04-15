<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $minute->title }}</title>
    <style>body{font-family:DejaVu Sans,sans-serif;font-size:11px;line-height:1.4}h1{font-size:18px}ul.attach{list-style:none;padding-left:0}ul.attach li{margin-bottom:4px}.page-break{page-break-before:always}table{width:100%;border-collapse:collapse}th,td{border:1px solid #d1d5db;padding:6px;text-align:left;font-size:10px}</style>
</head>
<body>
    <h1>{{ $minute->title }}</h1>
    <p>Estado: {{ $minute->status }}@if($minute->published_at) · Publicada {{ $minute->published_at->format('d/m/Y') }}@endif</p>
    @if($minute->church)<p>Igreja: {{ $minute->church->name }}</p>@endif
    @if($minute->relationLoaded('attachments') && $minute->attachments->isNotEmpty())
        <p><strong>Anexos:</strong></p>
        <ul class="attach">
            @foreach($minute->attachments as $a)
                <li>· {{ $a->original_name ?? basename($a->path) }} ({{ str_replace('_', ' ', $a->kind) }})</li>
            @endforeach
        </ul>
    @endif
    <hr>
    {!! $minute->content !!}

    @if(isset($signatures) && $signatures->isNotEmpty())
        <div class="page-break"></div>
        <h2>Log de assinaturas</h2>
        <table>
            <thead>
            <tr>
                <th>Utilizador</th>
                <th>Papel</th>
                <th>Data</th>
                <th>IP</th>
            </tr>
            </thead>
            <tbody>
            @foreach($signatures as $signature)
                <tr>
                    <td>{{ $signature->user?->name ?? 'N/D' }}</td>
                    <td>{{ $signature->role_at_the_time }}</td>
                    <td>{{ $signature->signed_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $signature->ip_address }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
