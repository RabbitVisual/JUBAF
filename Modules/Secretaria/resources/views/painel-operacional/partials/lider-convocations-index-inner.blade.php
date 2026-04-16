<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/80">
    <ul class="divide-y divide-gray-200 dark:divide-gray-800">
        @forelse ($convocations as $c)
            <li>
                <a href="{{ route($namePrefix.'.convocatorias.show', $c) }}" class="flex flex-col gap-1 px-5 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/50 md:px-6">
                    <span class="font-semibold text-gray-900 dark:text-white">{{ $c->title }}</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $c->assembly_at->format('d/m/Y · H:i') }}</span>
                    <span class="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">Abrir <x-icon name="arrow-right" class="h-3.5 w-3.5" /></span>
                </a>
            </li>
        @empty
            <li class="px-5 py-12 text-center md:px-6">
                <x-icon name="bullhorn" class="mx-auto mb-3 h-10 w-10 text-gray-400" style="duotone" />
                <p class="text-sm font-medium text-gray-900 dark:text-white">Sem convocatórias</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ainda não há assembleias publicadas.</p>
            </li>
        @endforelse
    </ul>
    @if ($convocations->hasPages())
        <div class="border-t border-gray-200 bg-gray-50/80 px-5 py-4 dark:border-gray-800 dark:bg-gray-900/50 md:px-6">{{ $convocations->links() }}</div>
    @endif
</div>