@props([
    'title',
    'description' => null,
    'icon' => 'calendar-days',
])

<div {{ $attributes->class([
    'flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center dark:border-gray-600 dark:bg-gray-900/40',
]) }} role="status">
    <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-500">
        <x-icon :name="$icon" class="h-7 w-7" style="duotone" />
    </div>
    <p class="mt-4 text-base font-semibold text-gray-900 dark:text-white">{{ $title }}</p>
    @if ($description)
        <p class="mt-2 max-w-md text-sm leading-relaxed text-gray-500 dark:text-gray-400">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-6">{{ $action }}</div>
    @endisset
</div>
