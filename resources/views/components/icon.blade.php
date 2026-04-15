@props([
    'name' => null,
    'module' => null,
    'style' => 'duotone',
    'class' => '',
    'size' => null,
    'bordered' => false,
    'pulled' => null,
    'rotate' => null,
    'flip' => null,
    'spin' => false,
    'pulse' => false,
    'brand' => false,
    'brands' => false,
])

@php
    // 0. Resolve Style from Brand Props
    if ($brand || $brands) {
        $style = 'brands';
    }
    // 1. Resolve Name from Module (if applicable)
    if ($module && empty($name)) {
        // Try exact match first, then lowercase, then studly
        $name = config("icons.modules.{$module}");

        if (!$name) {
            $moduleLower = strtolower($module);
            $name = config("icons.modules.{$moduleLower}");
        }

        if (!$name) {
            $moduleKey = Illuminate\Support\Str::studly($module);
            $name = config("icons.modules.{$moduleKey}");
        }

        if (!$name) {
            $name = 'circle-question'; // Fallback if module mapping missing
        }
    }

    // 2. Safety Fallback (Strict Enforcement)
    // Ensure we never return an empty name or invalid string that could result in missing icon
    if (empty($name) || $name === '?' || $name === '(?)') {
        $name = 'circle-question';
    }

    // 2. Safety Fallback
    if (empty($name)) {
        $name = 'circle-question';
    }

    // 3. Resolve Style Prefix (Font Awesome 7 Pro)
    $stylePrefix = match ($style) {
        'duotone' => 'fa-duotone',
        'solid' => 'fa-solid',
        'regular' => 'fa-regular',
        'light' => 'fa-light',
        'thin' => 'fa-thin',
        'brands' => 'fa-brands',
        'sharp' => 'fa-sharp fa-solid',
        'sharp-solid' => 'fa-sharp fa-solid',
        'sharp-regular' => 'fa-sharp fa-regular',
        'sharp-light' => 'fa-sharp fa-light',
        'sharp-thin' => 'fa-sharp fa-thin',
        default => 'fa-duotone',
    };

    // 4. Construct Classes
    $classes = [
        $stylePrefix,
        "fa-{$name}",
        $size ? "fa-{$size}" : '',
        $bordered ? 'fa-border' : '',
        $pulled ? "fa-pull-{$pulled}" : '',
        $rotate ? "fa-rotate-{$rotate}" : '',
        $flip ? "fa-flip-{$flip}" : '',
        $spin ? 'fa-spin' : '',
        $pulse ? 'fa-pulse' : '',
        $class
    ];

    $finalClass = implode(' ', array_filter($classes));
@endphp

<i {{ $attributes->merge(['class' => $finalClass]) }} aria-hidden="true"></i>
