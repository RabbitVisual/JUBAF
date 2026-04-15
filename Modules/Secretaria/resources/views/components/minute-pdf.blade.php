<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $minute->title }}</title>
    <style>body{font-family:DejaVu Sans,sans-serif;font-size:11px;line-height:1.4}h1{font-size:18px}ul.attach{list-style:none;padding-left:0}ul.attach li{margin-bottom:4px}</style>
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
    {!! $minute->body !!}
</body>
</html>
