@props([
    'user',
    'size' => 'md',
    'class' => '',
])

@php
    $url = $user ? user_photo_url($user) : null;
    $sizes = [
        'xs' => 'h-7 w-7 min-h-7 min-w-7 text-[10px]',
        'sm' => 'h-8 w-8 min-h-8 min-w-8 text-xs',
        'md' => 'h-10 w-10 min-h-10 min-w-10 text-sm',
        'lg' => 'h-12 w-12 min-h-12 min-w-12 text-base',
        'xl' => 'h-16 w-16 min-h-16 min-w-16 text-xl',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $name = $user->name ?? '';
    $initial = strtoupper(mb_substr(trim((string) ($user->first_name ?? $name)) !== '' ? ($user->first_name ?? $name) : '?', 0, 1));
@endphp

@if($url)
    <img
        src="{{ $url }}"
        alt="{{ $name }}"
        {{ $attributes->merge(['class' => $sizeClass.' rounded-full object-cover ring-2 ring-gray-200 dark:ring-slate-600 '.$class]) }}
    >
@else
    <span
        {{ $attributes->merge(['class' => $sizeClass.' inline-flex items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700 ring-2 ring-indigo-200 dark:bg-indigo-950/50 dark:text-indigo-200 dark:ring-indigo-900 '.$class]) }}
        aria-hidden="true"
    >{{ $initial }}</span>
@endif
