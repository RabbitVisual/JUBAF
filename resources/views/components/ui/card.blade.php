@props([
    'title' => null,
    /** @var string default|dense */
    'padding' => 'default',
])

@php
    $pad = $padding === 'dense' ? 'p-4' : 'p-5 sm:p-6';
    $shell = 'rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800';
@endphp

<div {{ $attributes->merge(['class' => $shell]) }}>
    @isset($header)
        <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700/80">
            {{ $header }}
        </div>
    @elseif ($title)
        <div class="border-b border-gray-100 px-5 py-4 dark:border-slate-700/80">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">{{ $title }}</h2>
        </div>
    @endif

    <div class="{{ $pad }}">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="border-t border-gray-100 px-5 py-4 dark:border-slate-700/80">
            {{ $footer }}
        </div>
    @endisset
</div>
