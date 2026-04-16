@extends('paineljovens::layouts.jovens')

@section('title', $aviso->titulo)


@section('jovens_content')
    <x-ui.jovens::page-shell class="max-w-3xl space-y-6">
        <a href="{{ route('jovens.avisos.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">
            <x-icon name="arrow-left" class="h-4 w-4" /> Voltar aos avisos
        </a>

        <article class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="space-y-6 p-6 md:p-8">
                <header>
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">{{ $aviso->tipo_texto }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $aviso->created_at->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white md:text-3xl">{{ $aviso->titulo }}</h1>
                    @include('avisos::partials.aviso-author', ['aviso' => $aviso, 'variant' => 'card'])
                </header>

                @if ($aviso->descricao)
                    <p class="text-lg leading-relaxed text-gray-700 dark:text-gray-300">{{ $aviso->descricao }}</p>
                @endif

                @if ($aviso->conteudo)
                    <div class="prose prose-blue max-w-none dark:prose-invert">
                        {!! $aviso->conteudo !!}
                    </div>
                @endif

                @if ($aviso->imagem)
                    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600">
                        <img src="{{ asset('storage/'.$aviso->imagem) }}" alt="" class="max-h-96 w-full object-cover" loading="lazy" />
                    </div>
                @endif

                @if ($aviso->botao_exibir && $aviso->url_acao)
                    <a href="{{ $aviso->url_acao }}" target="_blank" rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700">
                        {{ $aviso->texto_botao ?? 'Saiba mais' }}
                        <x-icon name="arrow-up-right-from-square" class="h-4 w-4" />
                    </a>
                @endif
            </div>
        </article>
    </x-ui.jovens::page-shell>
@endsection
