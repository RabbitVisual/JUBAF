@if ($jovensPanel ?? false)
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/80">
        <ul class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse ($minutes as $m)
                <li>
                    <a
                        href="{{ route($namePrefix.'.atas.show', $m) }}"
                        class="flex flex-col gap-1 px-5 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/50 md:px-6"
                    >
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $m->title }}</span>
                        @if ($m->published_at)
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Publicada em {{ $m->published_at->format('d/m/Y') }}
                            </span>
                        @endif
                        <span class="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400">
                            Ler ata
                            <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                        </span>
                    </a>
                </li>
            @empty
                <li class="px-5 py-12 text-center md:px-6">
                    <x-icon name="file-contract" class="mx-auto mb-3 h-10 w-10 text-gray-400" style="duotone" />
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Sem atas publicadas</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quando a secretaria publicar, vês aqui.</p>
                </li>
            @endforelse
        </ul>
        @if ($minutes->hasPages())
            <div class="border-t border-gray-200 bg-gray-50/80 px-5 py-4 dark:border-gray-800 dark:bg-gray-900/50 md:px-6">
                {{ $minutes->links() }}
            </div>
        @endif
    </div>
@else
    <div class="max-w-4xl space-y-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Atas publicadas</h1>
            <a href="{{ route($namePrefix.'.index') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Secretaria</a>
        </div>
        <ul class="space-y-2">
            @foreach ($minutes as $m)
                <li class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                    <a href="{{ route($namePrefix.'.atas.show', $m) }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400">{{ $m->title }}</a>
                    <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">{{ $m->published_at?->format('d/m/Y') }}</span>
                </li>
            @endforeach
        </ul>
        {{ $minutes->links() }}
    </div>
@endif
