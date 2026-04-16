@props([
    /** @var array<int, array{key:string,label:string}> */
    'steps' => [],
    'initial' => 0,
])

<div x-data="{ step: {{ (int) $initial }} }" class="space-y-6">
    <ol class="flex flex-wrap items-center gap-2 border-b border-gray-200 pb-4 dark:border-gray-700" role="tablist">
        @foreach ($steps as $idx => $s)
            <li class="flex items-center gap-2">
                <button type="button" role="tab" :aria-selected="(step === {{ $idx }}) ? 'true' : 'false'"
                    @click="step = {{ $idx }}"
                    class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900"
                    :class="step === {{ $idx }} ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800'">
                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md text-xs font-bold"
                        :class="step === {{ $idx }} ? 'bg-white/20' : 'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-200'">{{ $idx + 1 }}</span>
                    <span>{{ $s['label'] }}</span>
                </button>
                @if (! $loop->last)
                    <span class="text-gray-300 dark:text-gray-600" aria-hidden="true">/</span>
                @endif
            </li>
        @endforeach
    </ol>
    <div class="relative min-h-[8rem]">
        {{ $slot }}
    </div>
    <div class="flex flex-wrap justify-between gap-3 border-t border-gray-200 pt-4 dark:border-gray-700">
        <button type="button" @click="if (step > 0) step--" x-show="step > 0" x-cloak
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-800 shadow-sm transition hover:bg-gray-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700">
            <x-icon name="chevron-left" class="h-4 w-4" style="duotone" />
            Anterior
        </button>
        <button type="button" @click="if (step < {{ count($steps) - 1 }}) step++" x-show="step < {{ count($steps) - 1 }}" x-cloak
            class="ml-auto inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
            Seguinte
            <x-icon name="chevron-right" class="h-4 w-4" style="duotone" />
        </button>
    </div>
</div>
