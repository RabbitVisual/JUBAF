@props([
    'aviso',
    'variant' => 'card',
])

@php
    $author = $aviso->usuario;
@endphp

@if($author)
    @php
        $photo = user_photo_url($author);
        $name = trim((string) ($author->name ?? ''));
        $parts = preg_split('/\s+/', $name);
        $initials = count($parts) >= 2
            ? mb_strtoupper(mb_substr($parts[0], 0, 1).mb_substr($parts[count($parts) - 1], 0, 1))
            : mb_strtoupper(mb_substr($name !== '' ? $name : '?', 0, 2));
        $official = user_is_aviso_official_author($author);
        $onDark = $variant === 'banner';
    @endphp

    @if($variant === 'toast')
        {{-- Uma linha compacta para flutuante/toast em ecrãs pequenos --}}
        <div class="mt-1.5 flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1 text-gray-600 dark:text-gray-400">
            @if($photo)
                <img src="{{ $photo }}" alt="" class="h-6 w-6 shrink-0 rounded-full object-cover ring-1 ring-gray-200 dark:ring-slate-600" width="24" height="24" loading="lazy" />
            @else
                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-slate-200 text-[10px] font-bold text-slate-700 ring-1 ring-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:ring-slate-600" aria-hidden="true">{{ $initials }}</span>
            @endif
            <span class="min-w-0 truncate text-xs font-medium text-gray-800 dark:text-gray-200">{{ $author->name }}</span>
            @if($official)
                <span class="inline-flex shrink-0 items-center gap-0.5 rounded px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wide bg-cyan-100 text-cyan-900 dark:bg-cyan-900/40 dark:text-cyan-100">
                    <x-icon name="shield-check" class="h-2.5 w-2.5" style="duotone" />
                    JUBAF
                </span>
            @endif
        </div>
    @else
        <div class="mt-3 flex items-start gap-3 {{ $onDark ? 'text-white/95' : 'text-slate-600 dark:text-slate-300' }}">
            @if($photo)
                <img src="{{ $photo }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover ring-2 {{ $onDark ? 'ring-white/40' : 'ring-slate-200 dark:ring-slate-600' }}" width="36" height="36" loading="lazy" />
            @else
                <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold ring-2 {{ $onDark ? 'bg-white/20 text-white ring-white/40' : 'bg-slate-200 text-slate-700 ring-slate-300 dark:bg-slate-700 dark:text-slate-100 dark:ring-slate-600' }}" aria-hidden="true">{{ $initials }}</span>
            @endif
            <div class="min-w-0 flex-1">
                <div class="text-sm font-semibold leading-tight truncate">{{ $author->name }}</div>
                @if($official)
                    <span class="mt-1 inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $onDark ? 'bg-white/20 text-white' : 'bg-cyan-100 text-cyan-900 dark:bg-cyan-900/50 dark:text-cyan-100' }}">
                        <x-icon name="shield-check" class="w-3 h-3" style="duotone" />
                        Aviso oficial JUBAF
                    </span>
                @endif
            </div>
        </div>
    @endif
@endif
