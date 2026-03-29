<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('events::messages.badges') }} - {{ $event->title }}</title>
    <style>
        @page {
            margin: 5mm;
            size: {{ ($paperSize ?? 'A4') }}{{ (($orientation ?? 'portrait') === 'landscape') ? ' landscape' : '' }};
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', Arial, sans-serif;
            background-color: white;
        }
        .badge {
            width: 95mm;
            height: 60mm;
            float: left;
            margin: 5mm 2.5mm;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            text-align: center;
            position: relative;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            overflow: hidden;
        }
        .badge-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: white;
            padding: 8px 10px;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .badge-body {
            padding: 10px;
            height: 38mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .participant-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
            line-height: 1.2;
            max-height: 18mm;
            overflow: hidden;
        }
        .role-badge {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 8px;
        }
        .badge-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f1f5f9;
            padding: 6px;
            font-size: 7px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .badge-id {
            position: absolute;
            top: 35px;
            right: 8px;
            background: rgba(255,255,255,0.9);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 7px;
            color: #64748b;
            font-weight: 600;
        }
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #64748b;
        }
        .empty-message h2 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #1e3a8a;
        }
        .empty-message p {
            font-size: 11px;
            margin-bottom: 20px;
        }
        .sample-label {
            position: absolute;
            top: 35px;
            left: 8px;
            background: #dc2626;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 6px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Custom template wrapper */
        .custom-badge-wrapper {
            width: 95mm;
            height: 60mm;
            float: left;
            margin: 5mm 2.5mm;
            overflow: hidden;
        }
    </style>
</head>
<body>
    @php
        $participants = $registrations->pluck('participants')->flatten();
        $perPage = $badgesPerPage ?? 8;
        $hasParticipants = $participants->count() > 0;
        $eventDate = $event->start_date->format('d/m/Y');
        $eventLocation = $event->location ?? config('app.name');
        $eventTitle = Str::limit($event->title, 45);
        $participantLabel = __('events::messages.participant');
    @endphp

    @if(!$hasParticipants)
        {{-- Quando não há participantes, apenas uma mensagem simples --}}
        <div class="empty-message">
            <h2>{{ __('events::messages.sample_badge') }}</h2>
            <p>{{ __('events::messages.no_participants_for_badges') }}</p>
        </div>
    @else
        {{-- Crachás reais dos participantes (engine de PDF cuida da paginação) --}}
        @if(!empty($customTemplate))
            @foreach($participants as $participant)
                <div class="custom-badge-wrapper">
                    {!! app(\Modules\Events\App\Services\BadgePdfService::class)->parseTemplate($customTemplate, [
                        'name' => Str::limit($participant->name, 35),
                        'event' => $eventTitle,
                        'role' => $participantLabel,
                        'date' => $eventDate,
                        'location' => Str::limit($eventLocation, 25),
                        'qr_code' => '',
                    ]) !!}
                </div>
            @endforeach
        @else
            @foreach($participants as $index => $participant)
                <div class="badge">
                    <div class="badge-header">
                        {{ $eventTitle }}
                    </div>
                    <div class="badge-id">#{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="badge-body">
                        <div class="participant-name">
                            {{ Str::limit($participant->name, 35) }}
                        </div>
                        <div class="role-badge">
                            {{ $participantLabel }}
                        </div>
                    </div>
                    <div class="badge-footer">
                        {{ $eventDate }} • {{ Str::limit($eventLocation, 25) }}
                    </div>
                </div>
            @endforeach
        @endif
    @endif
</body>
</html>
