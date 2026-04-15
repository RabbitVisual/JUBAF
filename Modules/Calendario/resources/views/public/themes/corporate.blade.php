@php
    $tc = $event->resolvedThemeConfig();
    $primary = $tc['primary_color'];

    $typeLabels = [
        'evento' => 'Evento geral',
        'reuniao' => 'Reunião',
        'culto_especial' => 'Culto especial',
        'campanha' => 'Campanha / oferta',
        'formacao' => 'Formação',
        'assembleia' => 'Assembleia',
    ];
    $typeLabel = $typeLabels[$event->type] ?? ($event->type ? \Illuminate\Support\Str::headline(str_replace('_', ' ', $event->type)) : null);

    $startsLine = $event->starts_at
        ? ($event->all_day
            ? $event->starts_at->format('d/m/Y').' · dia inteiro'
            : $event->starts_at->format('d/m/Y · H:i'))
        : null;
@endphp
<article class="mt-8 overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-lg dark:border-slate-700 dark:bg-slate-800">
    <div class="relative border-b border-gray-100 px-6 py-10 text-white dark:border-slate-700" style="background: linear-gradient(135deg, {{ $primary }} 0%, #0f172a 100%);">
        @if(!empty($isPreview))
            <p class="mb-3 inline-flex rounded-full bg-amber-400/20 px-3 py-1 text-xs font-bold uppercase tracking-wider text-amber-100">Pré-visualização (rascunho)</p>
        @endif
        @if($event->cover_path)
            <div class="mb-4 overflow-hidden rounded-2xl border border-white/20">
                <img src="{{ asset('storage/'.$event->cover_path) }}" alt="" class="max-h-64 w-full object-cover">
            </div>
        @endif
        <div class="flex flex-wrap items-start gap-4">
            <img src="{{ asset('images/logo/logo.png') }}" alt="JUBAF" class="h-10 w-auto shrink-0 opacity-95" width="120" height="38" loading="lazy">
            <div class="min-w-0">
                @if($startsLine)
                    <p class="text-xs font-bold uppercase tracking-[0.15em] text-white/90">{{ $startsLine }}</p>
                @endif
                <h1 class="mt-2 text-2xl font-bold tracking-tight md:text-3xl">{{ $event->title }}</h1>
                @if($typeLabel)
                    <p class="mt-2 text-sm font-medium text-white/90">{{ $typeLabel }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="space-y-5 px-6 py-8">
        @include('calendario::public.partials.event-detail', ['event' => $event, 'skipTopSummary' => true])
    </div>
</article>
