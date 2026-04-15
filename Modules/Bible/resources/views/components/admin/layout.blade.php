@props([
    'title',
    'subtitle' => null,
    'showNav' => true,
])

@php
    $isDiretoriaBible = request()->attributes->get('bible_admin_route_prefix') === 'diretoria.bible';
    $effectiveShowNav = $showNav && ! $isDiretoriaBible;
@endphp

<div class="space-y-8 {{ $isDiretoriaBible ? 'max-w-7xl' : '' }}">
    @if ($isDiretoriaBible)
        <div
            class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <x-icon name="book-bible" class="h-9 w-9 shrink-0 text-amber-700 dark:text-amber-400" style="duotone" />
                    {{ $title }}
                </h1>
                @if ($subtitle)
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-2xl">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @else
        <section
            class="relative overflow-hidden rounded-2xl border border-gray-200 bg-gradient-to-br from-blue-600 via-blue-700 to-slate-900 p-6 text-white shadow-lg sm:p-8 dark:border-slate-700">
            <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/4 size-48 rounded-full bg-blue-400/20 blur-2xl"></div>
            <div class="relative flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0 space-y-2">
                    <span
                        class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white/95 ring-1 ring-white/20">Bíblia
                        digital</span>
                    <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">{{ $title }}</h1>
                    @if ($subtitle)
                        <p class="max-w-2xl text-sm leading-relaxed text-blue-100/95 sm:text-base">{{ $subtitle }}</p>
                    @endif
                </div>
                @isset($actions)
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        {{ $actions }}
                    </div>
                @endisset
            </div>
        </section>
    @endif

    <div class="{{ $effectiveShowNav ? 'flex flex-col gap-6 lg:flex-row lg:items-start' : '' }}">
        @if ($effectiveShowNav)
            <aside class="w-full shrink-0 lg:w-72" aria-label="Menu da secção Bíblia">
                <div
                    class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <p class="mb-3 px-1 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Navegação</p>
                    @include('bible::components.admin.nav')
                </div>
            </aside>
        @endif
        <div class="min-w-0 {{ $effectiveShowNav ? 'flex-1' : '' }} space-y-6">
            {{ $slot }}
        </div>
    </div>
</div>
