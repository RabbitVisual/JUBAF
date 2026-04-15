@props([
    'label' => null,
    'name',
    'value' => null,
    'type' => 'text',
    'error' => null,
    'hint' => null,
])

@php
    $id = $attributes->get('id') ?? $name;
    $errKey = $error ?? $name;
    $fieldClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400/20';
    if (isset($errors) && is_object($errors) && $errors->has($errKey)) {
        $fieldClass .= ' border-red-500 focus:border-red-500 focus:ring-red-500/25 dark:border-red-500';
    }
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $label }}</label>
    @endif

    @isset($icon)
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                {{ $icon }}
            </span>
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $id }}"
                value="{{ old($name, $value) }}"
                {{ $attributes->merge(['class' => $fieldClass.' pl-10']) }}
            />
        </div>
    @else
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ old($name, $value) }}"
            {{ $attributes->merge(['class' => $fieldClass]) }}
        />
    @endisset

    @if ($hint && (! isset($errors) || ! $errors->has($errKey)))
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $hint }}</p>
    @endif

    @error($errKey)
        <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
