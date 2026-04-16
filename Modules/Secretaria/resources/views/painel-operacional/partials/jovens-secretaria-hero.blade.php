@php
    $eyebrow = $eyebrow ?? 'Unijovem · Secretaria';
@endphp
<header
    class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 text-white shadow-xl dark:border-gray-800"
>
    <div
        class="pointer-events-none absolute inset-0 opacity-[0.12]"
        style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"
    ></div>
    <div class="relative flex flex-col gap-8 px-6 py-10 md:px-10 md:py-12 lg:flex-row lg:items-center lg:justify-between">
        <div class="max-w-2xl min-w-0">
            <p class="mb-3 text-xs font-bold uppercase tracking-widest text-blue-200/90">{{ $eyebrow }}</p>
            <h1 class="flex flex-wrap items-center gap-3 text-3xl font-bold leading-tight tracking-tight md:text-4xl">
                <span
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 shadow-lg ring-1 ring-white/25"
                >
                    <x-icon name="file-lines" class="h-8 w-8 text-white" style="duotone" />
                </span>
                {{ $title }}
            </h1>
            @if (filled($description ?? null))
                <p class="mt-4 text-sm font-medium leading-relaxed text-blue-100/95 md:text-base">
                    {{ $description }}
                </p>
            @endif
        </div>
        @if ($showHomeLink ?? false)
            <div class="flex w-full shrink-0 flex-col gap-3 sm:flex-row lg:max-w-xs lg:flex-col">
                <a
                    href="{{ route('jovens.dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-3.5 text-sm font-bold text-blue-900 shadow-lg transition-all hover:bg-blue-50 active:scale-[0.98]"
                >
                    <x-icon name="house" class="h-4 w-4" style="duotone" />
                    Início do painel
                </a>
            </div>
        @endif
    </div>
</header>
