@props(['module', 'class' => 'w-5 h-5', 'size' => null])

@php
    // If a size prop is provided (e.g. 8), convert it to Tailwind width/height classes.
    // Otherwise use the provided class (default w-5 h-5).
    $finalClass = $size ? "w-{$size} h-{$size}" : $class;
@endphp

<x-icon :module="$module" :class="$finalClass" {{ $attributes }} />
