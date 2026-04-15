<x-avisos::layouts.master>
    <div class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700" data-avisos-feed-meta="{{ route('avisos.api.feed-meta') }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <x-module-icon module="Avisos" class="w-9 h-9 text-indigo-600 dark:text-indigo-400" />
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Central de Avisos JUBAF</span>
                </div>
                <nav class="flex gap-4">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Home</a>
                </nav>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                    Comunicados e novidades
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Avisos oficiais da direção JUBAF e atualizações importantes para a família e o evento.
                </p>
            </div>

            @if(isset($avisos) && $avisos->count() > 0)
                <div id="avisos-public-list" class="space-y-8" data-updated-at="{{ $feedUpdatedAt }}">
                    @foreach($avisos as $aviso)
                        <article class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-6 md:p-8">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">
                                        {{ $aviso->tipo_texto }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $aviso->created_at->diffForHumans() }}</span>
                                </div>
                                <h2 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-3">
                                    <a href="{{ route('avisos.show', $aviso) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        {{ $aviso->titulo }}
                                    </a>
                                </h2>
                                @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                                @if($aviso->descricao)
                                    <p class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">{{ \Illuminate\Support\Str::limit(strip_tags($aviso->descricao), 220) }}</p>
                                @endif
                                <div class="mt-6">
                                    <a href="{{ route('avisos.show', $aviso) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Ler comunicado completo
                                        <x-icon name="arrow-right" class="w-4 h-4" />
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $avisos->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700">
                    <div class="bg-gray-100 dark:bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-icon name="bell-slash" class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhum aviso encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Não há avisos ativos no momento. Volte mais tarde!
                    </p>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            const metaUrl = document.querySelector('[data-avisos-feed-meta]')?.getAttribute('data-avisos-feed-meta');
            if (!metaUrl || !document.getElementById('avisos-public-list')) return;
            var last = document.getElementById('avisos-public-list').getAttribute('data-updated-at');
            setInterval(function () {
                fetch(metaUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        if (data.updated_at && data.updated_at !== last) {
                            window.location.reload();
                        }
                    })
                    .catch(function () {});
            }, 90000);
        })();
    </script>
</x-avisos::layouts.master>
