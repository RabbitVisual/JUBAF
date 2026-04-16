@props([
    'title' => null,
    /** Mantido por compatibilidade; visual unificado estilo Flowbite Admin */
    'accent' => 'default',
])

<div {{ $attributes->class(['rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:p-6']) }}>
    @if (isset($header))
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-4 dark:border-gray-700">
            {{ $header }}
        </div>
    @elseif ($title)
        <h2 class="mb-4 border-b border-gray-100 pb-3 text-lg font-semibold text-gray-900 dark:border-gray-700 dark:text-white">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>
