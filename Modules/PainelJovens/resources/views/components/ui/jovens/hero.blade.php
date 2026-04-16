@props([
    'title',
    'description' => null,
    'eyebrow' => null,
])

<div {{ $attributes->class(['overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800']) }}>
    <div class="flex flex-col gap-6 px-5 py-6 sm:px-6 lg:flex-row lg:items-start lg:justify-between lg:py-8">
        <div class="min-w-0 max-w-3xl flex-1">
            @if ($eyebrow)
                <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">{{ $eyebrow }}</p>
            @endif
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">{{ $title }}</h1>
            @if ($description)
                <p class="mt-3 text-sm leading-relaxed text-gray-500 dark:text-gray-400 md:text-base">{{ $description }}</p>
            @endif
        </div>
        @isset($actions)
            <div class="flex shrink-0 flex-col gap-2 sm:flex-row sm:items-center">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>
