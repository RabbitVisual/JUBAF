@php
    $liderNav = $liderNav ?? [];
    $defaultSection = $liderNavDefaultSection ?? ($liderNav[0]['id'] ?? 'principal');
    $tooltipAfter = 'pointer-events-none invisible absolute start-full top-1/2 z-[60] ms-3 -translate-y-1/2 whitespace-nowrap rounded-md bg-slate-900 px-2.5 py-1.5 text-xs font-semibold text-white shadow-lg opacity-0 transition-opacity group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100 dark:bg-slate-700';
    $panelLink = 'block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white';
    $panelLinkOn = 'bg-emerald-50 font-semibold text-emerald-900 dark:bg-emerald-950/50 dark:text-emerald-100';
    $detailsSummary = 'flex cursor-pointer list-none items-center justify-between gap-2 rounded-lg px-3 py-2 text-sm font-semibold text-slate-600 marker:content-none hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white [&::-webkit-details-marker]:hidden';
    $subLink = 'block rounded-lg py-2 pl-4 pr-3 text-sm font-medium text-slate-500 transition-colors hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800/80 dark:hover:text-slate-100';
    $subLinkOn = 'bg-emerald-50/90 text-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-100';
@endphp

<div
    class="relative flex h-full min-h-0 w-full bg-white text-slate-800 dark:bg-slate-900 dark:text-slate-100"
    x-data="{
        panelExpanded: true,
        activeSection: @js($defaultSection),
        init() {
            try {
                const v = localStorage.getItem('liderSidebarPanelExpanded');
                if (v !== null) this.panelExpanded = JSON.parse(v);
            } catch (e) {}
        },
        togglePanel() {
            this.panelExpanded = !this.panelExpanded;
            try { localStorage.setItem('liderSidebarPanelExpanded', JSON.stringify(this.panelExpanded)); } catch (e) {}
        },
        pickSection(id) {
            this.activeSection = id;
            if (! this.panelExpanded) this.panelExpanded = true;
            try { localStorage.setItem('liderSidebarPanelExpanded', JSON.stringify(true)); } catch (e) {}
        }
    }"
>
    <button type="button" @click="sidebarOpen = false" class="absolute right-2 top-2 z-[70] flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 lg:hidden dark:bg-slate-800 dark:text-slate-300" aria-label="Fechar menu">
        <x-icon name="xmark" class="h-4 w-4" />
    </button>

    {{-- Rail: ícones + tooltips (HyperUI side-menu) --}}
    <div class="relative flex w-16 shrink-0 flex-col border-e border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-950/90">
        <div class="flex h-16 shrink-0 items-center justify-center border-b border-slate-200 dark:border-slate-800">
            <a href="{{ route('lideres.dashboard') }}" class="grid size-10 place-content-center rounded-lg bg-white shadow-sm ring-1 ring-slate-200/80 dark:bg-slate-900 dark:ring-slate-700" title="{{ \App\Support\SiteBranding::siteName() }}">
                <img src="{{ \App\Support\SiteBranding::logoDarkUrl() }}" alt="" class="h-7 w-auto max-w-[1.75rem] object-contain dark:hidden">
                <img src="{{ \App\Support\SiteBranding::logoLightUrl() }}" alt="" class="hidden h-7 w-auto max-w-[1.75rem] object-contain dark:block">
            </a>
        </div>

        <nav class="min-h-0 flex-1 space-y-0.5 overflow-y-auto px-1.5 py-3" aria-label="Secções do menu">
            @foreach ($liderNav as $cat)
                @php
                    $rail = $cat['rail'] ?? ['icon' => ['kind' => 'icon', 'name' => 'compass'], 'tooltip' => $cat['label']];
                @endphp
                <div class="flex justify-center">
                    <button
                        type="button"
                        @click="pickSection(@js($cat['id']))"
                        :class="activeSection === @js($cat['id'])
                            ? 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-500/30 dark:bg-emerald-950/60 dark:text-emerald-200'
                            : 'text-slate-500 hover:bg-white hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white'"
                        class="group relative flex size-11 items-center justify-center rounded-lg transition-colors"
                        :aria-pressed="activeSection === @js($cat['id']) ? 'true' : 'false'"
                        aria-label="{{ $rail['tooltip'] }}"
                    >
                        @if (($rail['icon']['kind'] ?? '') === 'module')
                            <x-module-icon :module="$rail['icon']['module']" class="size-[1.15rem] shrink-0 opacity-90" style="duotone" alt="" />
                        @else
                            <x-icon :name="$rail['icon']['name']" class="size-[1.15rem] shrink-0 opacity-90" style="duotone" />
                        @endif
                        <span class="{{ $tooltipAfter }}">{{ $rail['tooltip'] }}</span>
                    </button>
                </div>
            @endforeach
        </nav>

        <div class="sticky bottom-0 mt-auto space-y-1 border-t border-slate-200 bg-slate-50 p-1.5 dark:border-slate-800 dark:bg-slate-950/90">
            <button
                type="button"
                @click="togglePanel()"
                class="group relative flex size-11 w-full items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-white hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800"
                :title="panelExpanded ? 'Recolher menu de detalhe' : 'Expandir menu de detalhe'"
            >
                <x-icon name="chevron-left" class="size-[1.05rem] opacity-90" style="duotone" x-show="panelExpanded" x-cloak />
                <x-icon name="chevron-right" class="size-[1.05rem] opacity-90" style="duotone" x-show="!panelExpanded" x-cloak />
                <span class="{{ $tooltipAfter }}"><span x-text="panelExpanded ? 'Recolher' : 'Expandir'"></span></span>
            </button>
            <form method="POST" action="{{ route('logout') }}" class="group relative flex justify-center">
                @csrf
                <button type="submit" class="relative flex size-11 items-center justify-center rounded-lg text-slate-500 transition-colors hover:bg-rose-50 hover:text-rose-700 dark:hover:bg-rose-950/40 dark:hover:text-rose-300" aria-label="Sair">
                    <x-icon name="right-from-bracket" class="size-[1.05rem] opacity-90" style="duotone" />
                    <span class="{{ $tooltipAfter }}">Sair</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Painel: categorias, subcategorias, <details> acordeão --}}
    <div
        class="flex min-h-0 min-w-0 flex-col overflow-hidden border-slate-200 transition-[max-width,opacity] duration-300 ease-out dark:border-slate-800"
        :class="panelExpanded
            ? 'max-w-[min(18.5rem,calc(100vw-5.5rem))] flex-1 border-s opacity-100'
            : 'max-w-0 flex-none border-transparent opacity-0'"
    >
        <div class="flex h-full w-[min(18.5rem,calc(100vw-5.5rem))] min-w-0 flex-1 flex-col bg-white dark:bg-slate-900">
            @foreach ($liderNav as $cat)
                <div
                    x-show="activeSection === @js($cat['id'])"
                    x-transition.opacity.duration.200ms
                    class="flex min-h-0 flex-1 flex-col"
                    x-cloak
                >
                    <div class="shrink-0 border-b border-slate-100 px-4 pb-3 pt-4 dark:border-slate-800 lg:pt-5">
                        <p class="text-[0.65rem] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Área</p>
                        <h2 class="mt-0.5 text-base font-bold tracking-tight text-slate-900 dark:text-white">{{ $cat['label'] }}</h2>
                    </div>

                    <ul class="min-h-0 flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                        @foreach ($cat['entries'] as $entry)
                            @if (($entry['type'] ?? '') === 'link')
                                <li>
                                    <a
                                        href="{{ $entry['href'] }}"
                                        @click="sidebarOpen = false"
                                        @class([$panelLink, $entry['active'] ? $panelLinkOn : ''])
                                        @if (! empty($entry['external'])) target="_blank" rel="noopener noreferrer" @endif
                                        @if ($entry['active']) aria-current="page" @endif
                                    >
                                        {{ $entry['label'] }}
                                    </a>
                                </li>
                            @elseif (($entry['type'] ?? '') === 'group')
                                @php $g = $entry; @endphp
                                <li>
                                    <details class="group" @if (! empty($g['defaultOpen'])) open @endif>
                                        <summary class="{{ $detailsSummary }}">
                                            <span>{{ $g['label'] }}</span>
                                            <x-icon name="chevron-down" class="size-4 shrink-0 text-slate-400 transition duration-300 group-open:-rotate-180 dark:text-slate-500" style="duotone" />
                                        </summary>
                                        <div class="mt-1 space-y-2 border-s border-slate-100 pb-2 pl-2 dark:border-slate-800">
                                            @if (! empty($g['subsections']))
                                                @foreach ($g['subsections'] as $sub)
                                                    <div>
                                                        <p class="px-2 pb-1 pt-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ $sub['label'] }}</p>
                                                        <ul class="space-y-0.5">
                                                            @foreach ($sub['items'] as $item)
                                                                <li>
                                                                    <a href="{{ $item['href'] }}" @click="sidebarOpen = false" @class([$subLink, $item['active'] ? $subLinkOn : '']) @if ($item['active']) aria-current="page" @endif>
                                                                        {{ $item['label'] }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            @elseif (! empty($g['children']))
                                                <ul class="space-y-0.5 pt-1">
                                                    @foreach ($g['children'] as $item)
                                                        <li>
                                                            <a href="{{ $item['href'] }}" @click="sidebarOpen = false" @class([$subLink, $item['active'] ? $subLinkOn : '']) @if ($item['active']) aria-current="page" @endif>
                                                                {{ $item['label'] }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </details>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <div class="shrink-0 border-t border-slate-100 px-4 py-3 dark:border-slate-800">
                        <p class="line-clamp-2 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ \App\Support\SiteBranding::siteTagline() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
