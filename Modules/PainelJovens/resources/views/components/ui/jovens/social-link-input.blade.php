@props([
    'label',
    'name',
    'value' => '',
    'placeholder' => '',
    'icon' => 'link',
])

<div class="space-y-1.5">
    <label class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300" for="{{ $name }}">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
            <x-icon :name="$icon" class="h-4 w-4" style="duotone" />
        </span>
        {{ $label }}
    </label>
    <input id="{{ $name }}" type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
        {{ $attributes->class([
            'block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm transition',
            'placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/25 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500',
        ]) }} />
</div>
