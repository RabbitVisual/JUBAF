@if ($jovensPanel ?? false)
    <div class="space-y-8 md:space-y-10">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <a
                href="{{ route($namePrefix.'.atas.index') }}"
                class="group flex flex-col gap-4 overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-200 hover:border-blue-300/80 hover:shadow-md dark:border-gray-800 dark:bg-gray-900/80 dark:hover:border-blue-800/40"
            >
                <span
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300"
                >
                    <x-icon name="file-contract" class="h-6 w-6" style="duotone" />
                </span>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Atas publicadas</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Atas aprovadas e publicadas pela secretaria.</p>
                </div>
                <span
                    class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400"
                >
                    Abrir
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                </span>
            </a>
            <a
                href="{{ route($namePrefix.'.convocatorias.index') }}"
                class="group flex flex-col gap-4 overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-200 hover:border-blue-300/80 hover:shadow-md dark:border-gray-800 dark:bg-gray-900/80 dark:hover:border-blue-800/40"
            >
                <span
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300"
                >
                    <x-icon name="bullhorn" class="h-6 w-6" style="duotone" />
                </span>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Convocatórias</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Assembleias e convocatórias publicadas.</p>
                </div>
                <span
                    class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400"
                >
                    Abrir
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                </span>
            </a>
            <a
                href="{{ route($namePrefix.'.documentos.index') }}"
                class="group flex flex-col gap-4 overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition-all duration-200 hover:border-blue-300/80 hover:shadow-md dark:border-gray-800 dark:bg-gray-900/80 dark:hover:border-blue-800/40 sm:col-span-2 lg:col-span-1"
            >
                <span
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300"
                >
                    <x-icon name="folder-open" class="h-6 w-6" style="duotone" />
                </span>
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Documentos</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ficheiros públicos para consulta ou download.</p>
                </div>
                <span
                    class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400"
                >
                    Abrir
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                </span>
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/80">
            <div class="border-b border-gray-100 bg-gray-50/80 px-5 py-4 dark:border-gray-800 dark:bg-gray-900/50 md:px-6">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Últimas atas</h2>
                <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">Entradas recentes na publicação oficial.</p>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($minutes as $m)
                    <li>
                        <a
                            href="{{ route($namePrefix.'.atas.show', $m) }}"
                            class="flex flex-col gap-1 px-5 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/50 md:px-6"
                        >
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $m->title }}</span>
                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400">
                                Ver ata
                                <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400 md:px-6">
                        Nenhuma ata publicada por agora.
                    </li>
                @endforelse
            </ul>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/80">
            <div class="border-b border-gray-100 bg-gray-50/80 px-5 py-4 dark:border-gray-800 dark:bg-gray-900/50 md:px-6">
                <h2 class="text-base font-bold text-gray-900 dark:text-white">Próximas assembleias</h2>
                <p class="mt-0.5 text-sm text-gray-600 dark:text-gray-400">Convocatórias com data agendada.</p>
            </div>
            <ul class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse ($convocations as $c)
                    <li>
                        <a
                            href="{{ route($namePrefix.'.convocatorias.show', $c) }}"
                            class="flex flex-col gap-1 px-5 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/50 md:px-6"
                        >
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $c->title }}</span>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                {{ $c->assembly_at->format('d/m/Y · H:i') }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-sm font-semibold text-blue-600 dark:text-blue-400">
                                Ver convocatória
                                <x-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </span>
                        </a>
                    </li>
                @empty
                    <li class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400 md:px-6">
                        Nenhuma assembleia agendada.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@else
    <div class="max-w-4xl space-y-8">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Documentação JUBAF</h1>
            <a href="{{ route($homeRoute) }}" class="text-sm text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400">Início</a>
        </div>
        <div class="grid gap-3 sm:grid-cols-3">
            <a href="{{ route($namePrefix.'.atas.index') }}" class="rounded-xl border border-gray-200 p-4 text-sm font-medium text-gray-800 transition hover:border-blue-400 dark:border-gray-700 dark:text-gray-100 dark:hover:border-blue-500">Atas publicadas</a>
            <a href="{{ route($namePrefix.'.convocatorias.index') }}" class="rounded-xl border border-gray-200 p-4 text-sm font-medium text-gray-800 transition hover:border-blue-400 dark:border-gray-700 dark:text-gray-100 dark:hover:border-blue-500">Convocatórias</a>
            <a href="{{ route($namePrefix.'.documentos.index') }}" class="rounded-xl border border-gray-200 p-4 text-sm font-medium text-gray-800 transition hover:border-blue-400 dark:border-gray-700 dark:text-gray-100 dark:hover:border-blue-500">Documentos</a>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-2 font-semibold text-gray-900 dark:text-white">Últimas atas</h2>
            <ul class="space-y-1 text-sm">
                @forelse ($minutes as $m)
                    <li><a href="{{ route($namePrefix.'.atas.show', $m) }}" class="text-blue-600 hover:underline dark:text-blue-400">{{ $m->title }}</a></li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">Nenhuma.</li>
                @endforelse
            </ul>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-2 font-semibold text-gray-900 dark:text-white">Próximas assembleias</h2>
            <ul class="space-y-1 text-sm">
                @forelse ($convocations as $c)
                    <li>
                        <a href="{{ route($namePrefix.'.convocatorias.show', $c) }}" class="text-blue-600 hover:underline dark:text-blue-400">{{ $c->title }}</a>
                        — {{ $c->assembly_at->format('d/m/Y H:i') }}
                    </li>
                @empty
                    <li class="text-gray-500 dark:text-gray-400">Nenhuma.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endif
