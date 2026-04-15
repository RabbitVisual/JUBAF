@props([
    'label' => null,
    'name',
    'error' => null,
])

@php
    $id = $attributes->get('id') ?? $name;
    $errKey = $error ?? $name;
    $fieldClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-indigo-400 dark:focus:ring-indigo-400/20';
    if (isset($errors) && is_object($errors) && $errors->has($errKey)) {
        $fieldClass .= ' border-red-500 focus:border-red-500 focus:ring-red-500/25 dark:border-red-500';
    }
@endphp

<div>
    @if ($label)
        <label for="{{ $id }}" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $label }}</label>
    @endif

    <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => $fieldClass]) }}>
        {{ $slot }}
    </select>

    @error($errKey)
        <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
