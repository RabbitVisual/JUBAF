@props([
    'tone' => 'neutral',
])

@php
    $tones = [
        'neutral' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200',
        'success' => 'bg-emerald-50 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200',
        'warning' => 'bg-amber-50 text-amber-900 dark:bg-amber-900/25 dark:text-amber-100',
        'danger' => 'bg-red-50 text-red-800 dark:bg-red-900/30 dark:text-red-200',
        'info' => 'bg-sky-50 text-sky-900 dark:bg-sky-900/30 dark:text-sky-100',
    ];
    $toneClass = $tones[$tone] ?? $tones['neutral'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-lg px-2 py-0.5 text-xs font-semibold '.$toneClass]) }}>{{ $slot }}</span>
