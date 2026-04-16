@props([
    'noPadding' => false,
])

@php
    $pad = $noPadding ? '' : 'px-0 sm:px-0';
@endphp

<div {{ $attributes->class(['mx-auto w-full max-w-6xl space-y-6 pb-8 md:space-y-8', $pad]) }}>
    {{ $slot }}
</div>
