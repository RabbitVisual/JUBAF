@php
    use Modules\Admin\App\Support\AdminNavTone;
    $type = $item['type'] ?? 'link';
    $tone = $item['tone'] ?? 'emerald';
    $activePatterns = $item['active'] ?? [];
    $isActive = false;
    foreach ($activePatterns as $p) {
        if (request()->routeIs($p)) {
            $isActive = true;
            break;
        }
    }
    $rowTone = AdminNavTone::row($tone, $isActive);
    $iconWrapTone = AdminNavTone::iconWrap($tone, $isActive);
    $iconTextTone = AdminNavTone::iconText($tone, $isActive);
@endphp

@if ($type === 'link')
    @php
        $indent = ! empty($item['indent']);
        $routeName = $item['route'] ?? '';
        $routeParams = $item['route_params'] ?? [];
        $simpleSub = in_array($tone, ['orange_sub', 'slate_sub'], true);
    @endphp
    @if ($simpleSub && ! $indent)
        <a href="{{ Route::has($routeName) ? route($routeName, $routeParams) : '#' }}"
            class="block px-3 py-2 rounded-lg text-sm {{ $rowTone }}">{{ $item['label'] ?? '' }}</a>
    @else
        <a href="{{ Route::has($routeName) ? route($routeName, $routeParams) : '#' }}"
            class="{{ $indent ? 'flex items-center gap-2 ml-4 pl-3 border-l-2 border-emerald-200 dark:border-emerald-900 py-1.5 text-xs font-medium rounded-r-lg transition-colors ' : 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 ' }}{{ $rowTone }}">
            @if ($indent)
                @if (! empty($item['icon']['name']))
                    <x-icon :name="$item['icon']['name']" class="w-4 h-4 shrink-0 opacity-90" style="{{ $item['icon']['style'] ?? 'duotone' }}" />
                @endif
            @else
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $iconWrapTone }}">
                    @if (! empty($item['module_icon']))
                        <x-module-icon :module="$item['module_icon']" class="w-5 h-5 {{ $iconTextTone }}" style="duotone" />
                    @elseif (! empty($item['icon']['name']))
                        <x-icon :name="$item['icon']['name']" class="w-5 h-5 {{ $iconTextTone }}"
                            style="{{ $item['icon']['style'] ?? 'duotone' }}" />
                    @endif
                </div>
            @endif
            <span class="{{ $indent ? '' : 'flex-1' }}">{{ $item['label'] ?? '' }}</span>
        </a>
    @endif
@elseif ($type === 'group')
    <div class="space-y-0.5">
        @foreach ($item['items'] ?? [] as $sub)
            @include('admin::components.sidebar.item', ['item' => $sub])
        @endforeach
    </div>
@elseif ($type === 'accordion')
    <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }" class="space-y-1">
        <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 {{ $rowTone }}">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $iconWrapTone }}">
                    @if (! empty($item['module_icon']))
                        <x-module-icon :module="$item['module_icon']" class="w-5 h-5 {{ $iconTextTone }}" style="duotone" />
                    @elseif (! empty($item['icon']['name']))
                        <x-icon :name="$item['icon']['name']" class="w-5 h-5 {{ $iconTextTone }}"
                            style="{{ $item['icon']['style'] ?? 'duotone' }}" />
                    @endif
                </div>
                <span class="flex-1 text-left">{{ $item['label'] ?? '' }}</span>
            </div>
            <x-icon name="chevron-down" class="w-4 h-4 transition-transform" x-bind:class="{ 'rotate-180': open }" style="duotone" />
        </button>
        <div x-show="open" x-collapse class="pl-14 space-y-1">
            @foreach ($item['children'] ?? [] as $child)
                @include('admin::components.sidebar.item', ['item' => $child])
            @endforeach
        </div>
    </div>
@elseif ($type === 'bible')
    <div x-data="{ open: {{ request()->routeIs('admin.bible.*') ? 'true' : 'false' }} }" class="space-y-1">
        <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-sm transition-all duration-200 {{ $rowTone }}">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $iconWrapTone }}">
                    <x-module-icon :module="$item['module_icon'] ?? 'Bible'" class="w-5 h-5 {{ $iconTextTone }}" style="duotone" />
                </div>
                <span class="flex-1 text-left truncate">{{ $item['label'] ?? 'Bíblia digital' }}</span>
            </div>
            <x-icon name="chevron-down" class="w-4 h-4 shrink-0 transition-transform" x-bind:class="{ 'rotate-180': open }" style="duotone" />
        </button>
        <div x-show="open" x-collapse
            class="rounded-lg border border-gray-100 bg-gray-50/80 p-2 dark:border-slate-700 dark:bg-slate-900/40">
            @include('bible::components.admin.nav')
        </div>
    </div>
@endif
