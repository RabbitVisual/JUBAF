{{--
    Hero unificado — Painel Diretoria Blog (use com @include; variáveis opcionais abaixo).
    @var string $kicker
    @var string $title
    @var string|null $lead
--}}
@php
    $kicker = $kicker ?? 'Blog JUBAF';
    $title = $title ?? '';
    $lead = $lead ?? null;
    $iconName = $iconName ?? null;
    $crumbs = $crumbs ?? [];
@endphp
<div
    class="overflow-hidden rounded-3xl border border-emerald-200/70 bg-gradient-to-br from-emerald-50/90 via-white to-teal-50/25 p-6 shadow-sm dark:border-emerald-900/30 dark:from-emerald-950/30 dark:via-slate-900 dark:to-slate-900 md:p-8">
    <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
        <div class="min-w-0 flex-1">
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-700 dark:text-emerald-400">{{ $kicker }}</p>
            <h1
                class="mt-2 flex flex-wrap items-center gap-3 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">
                @if ($iconName)
                    <span
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-lg shadow-emerald-600/25">
                        <x-icon :name="$iconName" class="h-6 w-6" style="duotone" />
                    </span>
                @endif
                {{ $title }}
            </h1>
            @if ($lead)
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">{{ $lead }}</p>
            @endif
            @if (count($crumbs) > 0)
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500"
                    aria-label="breadcrumb">
                    @foreach ($crumbs as $i => $c)
                        @if ($i > 0)
                            <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                        @endif
                        @if (! empty($c['url']))
                            <a href="{{ $c['url'] }}"
                                class="font-medium text-emerald-700 transition hover:text-emerald-900 hover:underline dark:text-emerald-400 dark:hover:text-emerald-300">{{ $c['label'] }}</a>
                        @else
                            <span
                                class="font-semibold text-gray-800 dark:text-slate-300">{{ $c['label'] }}</span>
                        @endif
                    @endforeach
                </nav>
            @endif
        </div>
        @isset($actions)
            <div class="flex shrink-0 flex-wrap items-center justify-end gap-2">{!! $actions !!}</div>
        @endisset
    </div>
</div>
