{{-- Modal Alpine: use slot trigger opcional; @click em botões externos pode fazer $dispatch('open-{{ $id }}') com listener extra se necessário. --}}
@props([
    'id' => 'ui-modal',
    'title' => '',
])

<div x-data="{ open: false }" {{ $attributes->class('relative') }}>
    @isset($trigger)
        <div @click.stop="open = true">{{ $trigger }}</div>
    @endisset

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[60] flex items-center justify-center p-4"
        aria-modal="true"
        role="dialog"
        id="{{ $id }}"
        @keydown.escape.window="open = false"
    >
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
            @click="open = false"
        ></div>

        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative z-10 w-full max-w-lg rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-slate-700 dark:bg-slate-800"
            @click.stop
        >
            @if ($title !== '')
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-slate-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
                    <button type="button" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-slate-700 dark:hover:text-white" @click="open = false" aria-label="Fechar">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            <div class="px-5 py-4 text-sm text-gray-700 dark:text-gray-200">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex justify-end gap-2 border-t border-gray-100 px-5 py-4 dark:border-slate-700">
                    {{ $footer }}
                </div>
            @endisset
        </div>
    </div>
</div>
