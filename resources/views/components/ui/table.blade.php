@props([])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            @isset($head)
                <thead>
                    <tr class="bg-gray-50/90 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/90 dark:text-gray-400">
                        {{ $head }}
                    </tr>
                </thead>
            @endisset
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
