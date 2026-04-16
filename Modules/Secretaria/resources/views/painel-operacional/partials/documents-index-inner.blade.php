@if ($jovensPanel ?? false)
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/80">
        <ul class="divide-y divide-gray-200 dark:divide-gray-800">
            @forelse ($documents as $d)
                <li class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between md:px-6">
                    <span class="min-w-0 font-semibold text-gray-900 dark:text-white">{{ $d->title }}</span>
                    @can('download', $d)
                        <a
                            href="{{ route($namePrefix.'.documentos.download', $d) }}"
                            class="inline-flex shrink-0 items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
                        >
                            <x-icon name="arrow-down-to-line" class="h-4 w-4" style="duotone" />
                            Download
                        </a>
                    @endcan
                </li>
            @empty
                <li class="px-5 py-12 text-center md:px-6">
                    <x-icon name="folder-open" class="mx-auto mb-3 h-10 w-10 text-gray-400" />
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Sem documentos</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quando houver ficheiros, aparecem aqui.</p>
                </li>
            @endforelse
        </ul>
        @if ($documents->hasPages())
            <div class="border-t border-gray-200 bg-gray-50/80 px-5 py-4 dark:border-gray-800 dark:bg-gray-900/50 md:px-6">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
@else
    <div class="max-w-4xl space-y-4">
        <div class="flex justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Documentos</h1>
            <a href="{{ route($namePrefix.'.index') }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Secretaria</a>
        </div>
        <ul class="space-y-2">
            @forelse ($documents as $d)
                <li class="flex justify-between rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                    <span class="text-gray-900 dark:text-gray-100">{{ $d->title }}</span>
                    @can('download', $d)
                        <a href="{{ route($namePrefix.'.documentos.download', $d) }}" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Download</a>
                    @endcan
                </li>
            @empty
                <li class="text-gray-500 dark:text-gray-400">Nenhum documento.</li>
            @endforelse
        </ul>
        {{ $documents->links() }}
    </div>
@endif
