<x-avisos::layouts.master>
    <div class="bg-white dark:bg-slate-800 shadow-sm border-b border-gray-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <x-icon module="avisos" name="bell" class="w-8 h-8 text-indigo-600 dark:text-indigo-400" />
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Central de Avisos</span>
                </div>
                <nav class="flex gap-4">
                    <a href="{{ route('avisos.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Todos os avisos</a>
                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Home</a>
                </nav>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="rounded-2xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                <div class="p-6 md:p-8 space-y-6">
                    <header>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">
                                {{ $aviso->tipo_texto }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $aviso->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $aviso->titulo }}</h1>
                        @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                    </header>

                    @if($aviso->descricao)
                        <p class="text-lg text-gray-700 dark:text-gray-300 leading-relaxed">{{ $aviso->descricao }}</p>
                    @endif

                    @if($aviso->conteudo)
                        <div class="prose prose-indigo dark:prose-invert max-w-none">
                            {!! $aviso->conteudo !!}
                        </div>
                    @endif

                    @if($aviso->imagem)
                        <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-slate-600">
                            <img src="{{ asset('storage/'.$aviso->imagem) }}" alt="" class="w-full object-cover max-h-96" loading="lazy" />
                        </div>
                    @endif

                    @if($aviso->botao_exibir && $aviso->url_acao)
                        <div>
                            <a href="{{ $aviso->url_acao }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition-colors">
                                {{ $aviso->texto_botao ?? 'Saiba mais' }}
                                <x-icon name="arrow-up-right-from-square" class="w-4 h-4" />
                            </a>
                        </div>
                    @endif
                </div>
            </article>
        </div>
    </div>
</x-avisos::layouts.master>
