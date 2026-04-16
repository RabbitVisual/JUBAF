@props([
    'title',
    'description' => null,
    'eyebrow' => null,
    'variant' => 'gradient',
])

@if ($variant === 'surface')
    <div {{ $attributes->class(['overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800']) }}>
        <div class="flex flex-col gap-6 px-5 py-6 sm:px-6 lg:flex-row lg:items-start lg:justify-between lg:py-8">
            <div class="min-w-0 max-w-3xl flex-1">
                @if ($eyebrow)
                    <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">{{ $eyebrow }}</p>
                @endif
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white md:text-3xl">{{ $title }}</h1>
                @if ($description)
                    <p class="mt-3 text-sm leading-relaxed text-slate-600 dark:text-slate-400 md:text-base">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </div>
@else
    <header {{ $attributes->class(['relative overflow-hidden rounded-[2rem] border border-slate-200/90 text-white shadow-xl dark:border-slate-800']) }}>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-950"></div>
        <div class="pointer-events-none absolute inset-0 opacity-[0.12]"
            style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
        <div class="relative flex flex-col gap-6 px-6 py-8 md:flex-row md:items-end md:justify-between md:px-10 md:py-10">
            <div class="max-w-2xl">
                @if ($eyebrow)
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-200/90">{{ $eyebrow }}</p>
                @endif
                <h1 class="mt-2 text-3xl font-bold tracking-tight md:text-4xl">{{ $title }}</h1>
                @if ($description)
                    <p class="mt-3 text-sm leading-relaxed text-emerald-100/95 md:text-base">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex w-full shrink-0 flex-col gap-2 sm:flex-row sm:justify-end md:w-auto">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    </header>
@endif
