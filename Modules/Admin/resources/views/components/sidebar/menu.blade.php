<nav class="flex flex-1 flex-col gap-y-1" aria-label="Navegação principal">
    @foreach ($sections as $section)
        <div class="{{ ! $loop->first ? 'pt-4 mt-4 border-t border-gray-200 dark:border-slate-700' : '' }}">
            @if (! empty($section['heading']))
                <div class="px-3 mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-2">
                        @if (! empty($section['heading']['icon']['name']))
                            <x-icon :name="$section['heading']['icon']['name']" class="w-3.5 h-3.5" style="{{ $section['heading']['icon']['style'] ?? 'duotone' }}" />
                        @endif
                        {{ $section['heading']['label'] ?? '' }}
                    </h3>
                </div>
            @endif
            <div class="space-y-1">
                @foreach ($section['items'] ?? [] as $item)
                    @include('admin::components.sidebar.item', ['item' => $item])
                @endforeach
            </div>
        </div>
    @endforeach
</nav>
