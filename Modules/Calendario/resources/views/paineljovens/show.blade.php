@extends('paineljovens::layouts.jovens')

@section('title', $event->title)


@section('jovens_content')
@php
    $regLabels = [
        'confirmed' => 'Inscrição confirmada',
        'waitlist' => 'Lista de espera',
        'cancelled' => 'Cancelada',
    ];
    $regLabel = $registration
        ? ($regLabels[$registration->status] ?? $registration->status)
        : '';
@endphp
<x-ui.jovens::page-shell class="space-y-8 pb-8 md:space-y-10">
    <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 transition-all hover:gap-2.5 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
        <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
        Voltar aos eventos
    </a>

    <article class="overflow-hidden rounded-[2rem] border border-gray-200/90 bg-white shadow-xl dark:border-gray-800 dark:bg-gray-900">
        <div class="relative overflow-hidden border-b border-white/10 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 px-6 py-10 text-white md:px-10 md:py-12">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-100/90">{{ $event->starts_at->format('d/m/Y · H:i') }}</p>
                <h1 class="mt-3 text-2xl font-bold leading-tight tracking-tight md:text-3xl">{{ $event->title }}</h1>
                @if($event->type)
                    <p class="mt-2 text-sm font-medium capitalize text-blue-100/95">{{ $event->type }}</p>
                @endif
            </div>
        </div>

        <div class="space-y-6 px-6 py-8 md:px-10 md:py-10">
            @if($event->ends_at)
                <p class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <x-icon name="clock" class="h-4 w-4 text-blue-500 dark:text-blue-400" style="duotone" />
                    <span><strong class="text-gray-900 dark:text-white">Até:</strong> {{ $event->ends_at->format('d/m/Y H:i') }}</span>
                </p>
            @endif
            @if($event->location)
                <p class="flex items-start gap-3 text-gray-700 dark:text-gray-300">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                        <x-icon name="location-dot" class="h-5 w-5" style="duotone" />
                    </span>
                    <span><strong class="text-gray-900 dark:text-white">Local:</strong><br>{{ $event->location }}</span>
                </p>
            @endif
            @if($event->description)
                <div class="rounded-2xl border border-blue-100 bg-blue-50/50 px-5 py-4 dark:border-blue-900/40 dark:bg-blue-950/20">
                    <div class="prose prose-sm max-w-none whitespace-pre-wrap text-gray-700 dark:prose-invert dark:text-gray-300">{{ $event->description }}</div>
                </div>
            @endif
            @if($event->registration_fee)
                <p class="rounded-2xl border border-amber-200/80 bg-amber-50 px-5 py-4 text-sm font-medium text-amber-950 dark:border-amber-900/50 dark:bg-amber-950/30 dark:text-amber-100">
                    Taxa de inscrição: <strong>R$ {{ number_format((float) $event->registration_fee, 2, ',', '.') }}</strong> — confirma com a tesouraria / diretoria da JUBAF.
                </p>
            @endif

            @if($registration)
                <div class="rounded-2xl border border-emerald-200/90 bg-gradient-to-br from-emerald-50 to-white p-6 dark:border-emerald-900/40 dark:from-emerald-950/40 dark:to-gray-900/50">
                    <p class="flex items-center gap-2 text-base font-bold text-emerald-900 dark:text-emerald-100">
                        <x-icon name="circle-check" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" style="solid" />
                        {{ $regLabel }}
                    </p>
                    @if($registration->status !== 'cancelled')
                        <form action="{{ route($routePrefix.'.cancel', $event) }}" method="post" class="mt-4" onsubmit="return confirm('Cancelar inscrição?');">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-rose-600 hover:underline dark:text-rose-400">Cancelar inscrição</button>
                        </form>
                    @endif
                </div>
            @elseif($event->registration_open && $event->isRegistrationPeriodOpen())
                <div class="space-y-4" x-data="{ registerOpen: false }" @keydown.escape.window="registerOpen = false">
                    <button type="button" @click="registerOpen = true"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-8 py-4 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 active:scale-[0.99] sm:w-auto">
                        <x-icon name="circle-check" class="h-5 w-5" style="solid" />
                        Inscrever-me neste evento
                    </button>

                    <div x-show="registerOpen" x-cloak x-transition.opacity.duration.200ms
                        class="fixed inset-0 z-50 flex items-end justify-center overflow-y-auto bg-gray-900/60 p-4 sm:items-center"
                        role="dialog" aria-modal="true" aria-labelledby="jovens-register-title">
                        <div class="relative w-full max-w-lg rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-900" @click.outside="registerOpen = false">
                            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-800">
                                <h3 id="jovens-register-title" class="text-lg font-bold text-gray-900 dark:text-white">Confirmar inscrição</h3>
                                <button type="button" @click="registerOpen = false" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-200" aria-label="Fechar">
                                    <x-icon name="xmark" class="h-5 w-5" />
                                </button>
                            </div>
                            <form action="{{ route($routePrefix.'.register', $event) }}" method="post" class="space-y-4 px-5 py-4">
                                @csrf
                                @if($event->batches->isNotEmpty())
                                    <div>
                                        <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Lote</label>
                                        <select name="event_batch_id" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                            <option value="">— Taxa base (sem lote) —</option>
                                            @foreach($event->batches as $batch)
                                                <option value="{{ $batch->id }}">{{ $batch->name ?: 'Lote #'.$batch->id }} — R$ {{ number_format((float) $batch->price, 2, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div>
                                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Código promocional (opcional)</label>
                                    <input type="text" name="discount_code" value="{{ old('discount_code') }}" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white" placeholder="Se tiveres um código">
                                </div>
                                <div class="flex flex-wrap justify-end gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
                                    <button type="button" @click="registerOpen = false"
                                        class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">Cancelar</button>
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                        <x-icon name="circle-check" class="h-4 w-4" style="solid" />
                                        Confirmar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <p class="rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 text-sm text-gray-600 dark:border-gray-600 dark:bg-gray-900/50 dark:text-gray-400">As inscrições estão fechadas para este evento.</p>
            @endif

            @if(!empty($hasPublicCalendar))
                <div class="border-t border-gray-100 pt-6 dark:border-gray-700">
                    <a href="{{ route('eventos.index') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-bold text-blue-700 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">
                        <x-icon name="globe" class="h-4 w-4" style="duotone" />
                        Ver mais na agenda pública
                    </a>
                </div>
            @endif
        </div>
    </article>
</x-ui.jovens::page-shell>
@endsection
