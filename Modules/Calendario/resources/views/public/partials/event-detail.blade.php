@php
    $typeLabels = [
        'evento' => 'Evento geral',
        'reuniao' => 'Reunião',
        'culto_especial' => 'Culto especial',
        'campanha' => 'Campanha / oferta',
        'formacao' => 'Formação',
        'assembleia' => 'Assembleia',
    ];
    $typeLabel = $typeLabels[$event->type] ?? ($event->type ? \Illuminate\Support\Str::headline(str_replace('_', ' ', $event->type)) : null);

    $startsLabel = $event->starts_at
        ? ($event->all_day
            ? $event->starts_at->format('d/m/Y').' · dia inteiro'
            : $event->starts_at->format('d/m/Y · H:i'))
        : null;
    $omitTopSummary = !empty($skipTopSummary);
@endphp

@if($event->banner_path)
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-600">
        <img src="{{ asset('storage/'.$event->banner_path) }}" alt="" class="max-h-56 w-full object-cover md:max-h-72">
    </div>
@endif

@unless($omitTopSummary)
    @if($startsLabel)
        <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">{{ $startsLabel }}</p>
    @endif

    @if($typeLabel)
        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $typeLabel }}</p>
    @endif
@endunless

@if($event->schedule && count($event->schedule))
    <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-600 dark:bg-slate-900/40">
        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Programa</p>
        <ul class="mt-2 space-y-2 text-sm text-slate-800 dark:text-slate-200">
            @foreach($event->schedule as $block)
                <li class="flex gap-2">
                    @if(!empty($block['time']))
                        <span class="shrink-0 font-mono text-xs text-slate-500">{{ $block['time'] }}</span>
                    @endif
                    <span>{{ $block['label'] ?? $block['title'] ?? '' }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif

@if($event->location)
    <p class="flex items-start gap-2 text-slate-700 dark:text-slate-300">
        <x-icon name="location-dot" class="mt-0.5 h-5 w-5 shrink-0" style="duotone" />
        <span><strong class="text-slate-900 dark:text-white">Local:</strong> {{ $event->location }}</span>
    </p>
@endif

@if($event->church_id && $event->relationLoaded('church') && $event->church)
    <p class="text-sm text-slate-600 dark:text-slate-400">
        <strong class="text-slate-900 dark:text-white">Congregação:</strong> {{ $event->church->name }}
    </p>
@endif

@if($event->ends_at)
    <p class="text-sm text-slate-600 dark:text-slate-400">
        <strong class="text-slate-900 dark:text-white">{{ $event->all_day ? 'Termina em' : 'Término:' }}</strong>
        {{ $event->all_day ? $event->ends_at->format('d/m/Y') : $event->ends_at->format('d/m/Y H:i') }}
    </p>
@endif

@if($event->description)
    <div class="prose prose-sm max-w-none text-slate-700 dark:prose-invert dark:text-slate-300 whitespace-pre-wrap">{{ $event->description }}</div>
@endif

@if($event->metadata && !empty($event->metadata['tips']))
    <div class="rounded-xl border border-blue-200/80 bg-blue-50/80 px-4 py-3 text-sm text-blue-950 dark:border-blue-900/50 dark:bg-blue-950/30 dark:text-blue-100">
        <strong>Dicas:</strong> {{ $event->metadata['tips'] }}
    </div>
@endif

@if($event->metadata && !empty($event->metadata['dress_code']))
    <div class="rounded-xl border border-violet-200/80 bg-violet-50/80 px-4 py-3 text-sm text-violet-950 dark:border-violet-900/50 dark:bg-violet-950/30 dark:text-violet-100">
        <strong>Vestuário:</strong> {{ $event->metadata['dress_code'] }}
    </div>
@endif

@php
    $hasContact = $event->contact_name || $event->contact_email || $event->contact_phone || $event->contact_whatsapp;
@endphp
@if($hasContact)
    <div class="rounded-xl border border-slate-200 bg-slate-50/90 px-4 py-3 text-sm text-slate-700 dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-200">
        <p class="font-bold text-slate-900 dark:text-white">Contacto</p>
        <ul class="mt-1 list-inside list-disc space-y-0.5">
            @if($event->contact_name)<li>{{ $event->contact_name }}</li>@endif
            @if($event->contact_email)<li><a href="mailto:{{ $event->contact_email }}" class="font-semibold text-teal-700 underline dark:text-teal-400">{{ $event->contact_email }}</a></li>@endif
            @if($event->contact_phone)<li>{{ $event->contact_phone }}</li>@endif
            @if($event->contact_whatsapp)<li>WhatsApp: {{ $event->contact_whatsapp }}</li>@endif
        </ul>
    </div>
@endif

@if($event->registration_open || $event->registration_fee || ($event->relationLoaded('batches') && $event->batches->isNotEmpty()))
    <p class="rounded-xl border border-amber-200/80 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100">
        @if($event->registration_open && $event->isRegistrationPeriodOpen())
            Inscrições abertas — utilize o painel de jovens ou de líderes (com login) para se inscrever e efetuar pagamento quando aplicável.
        @elseif($event->registration_fee || ($event->relationLoaded('batches') && $event->batches->isNotEmpty()))
            Valores e inscrições geridos no painel de jovens ou líderes quando as inscrições estiverem activas.
        @else
            As inscrições online podem ser activados pela diretoria — consulte o painel de jovens ou de líderes quando estiverem abertas.
        @endif
    </p>
    @if($event->registration_open && $event->isRegistrationPeriodOpen())
        <div class="flex flex-col gap-2 text-sm sm:flex-row sm:flex-wrap sm:gap-4">
            @if(Route::has('jovens.calendario.show'))
                <a href="{{ route('jovens.calendario.show', $event) }}" class="inline-flex items-center gap-2 font-bold text-teal-700 underline dark:text-teal-400">
                    <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                    Inscrição — painel de jovens
                </a>
            @endif
            @if(Route::has('lideres.calendario.show'))
                <a href="{{ route('lideres.calendario.show', $event) }}" class="inline-flex items-center gap-2 font-bold text-emerald-800 underline dark:text-emerald-400">
                    <x-icon name="arrow-right" class="h-4 w-4" style="duotone" />
                    Inscrição — painel de líderes
                </a>
            @endif
        </div>
    @endif
@endif

@if(module_enabled('Blog') && $event->blog_post_id && $event->relationLoaded('blogPost') && $event->blogPost)
    <a href="{{ route('blog.show', $event->blogPost->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:underline dark:text-blue-400">
        <x-icon name="book-open" class="h-4 w-4" style="duotone" />
        Ler artigo relacionado
    </a>
@endif

@if(module_enabled('Avisos') && $event->aviso_id && $event->relationLoaded('aviso') && $event->aviso && Route::has('avisos.show'))
    <a href="{{ route('avisos.show', $event->aviso) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 hover:underline dark:text-slate-300">
        <x-icon name="bullhorn" class="h-4 w-4" style="duotone" />
        Ver aviso oficial
    </a>
@endif
