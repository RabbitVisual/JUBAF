@extends('homepage::components.layouts.master')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">
        <header class="text-center space-y-2">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Transparência</h1>
            <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Atas publicadas e comunicados oficiais da JUBAF.</p>
        </header>

        <section aria-labelledby="sec-atas">
            <h2 id="sec-atas" class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Atas de assembleia</h2>
            @if($minutes->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">Ainda não há atas publicadas.</p>
            @else
                <ul class="space-y-4">
                    @foreach($minutes as $minute)
                        <li class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ optional($minute->published_at)->translatedFormat('d M Y') }}</p>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $minute->assembly?->title ?? 'Ata' }}</h3>
                                </div>
                                <a href="{{ route('public.transparency.minute', $minute) }}" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline shrink-0">Ler mais</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6">{{ $minutes->links() }}</div>
            @endif
        </section>

        <section aria-labelledby="sec-comms">
            <h2 id="sec-comms" class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Comunicados</h2>
            @if($communications->isEmpty())
                <p class="text-gray-500 dark:text-gray-400">Ainda não há comunicados publicados.</p>
            @else
                <ul class="space-y-4">
                    @foreach($communications as $comm)
                        <li class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ optional($comm->published_at)->translatedFormat('d M Y') }}</p>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $comm->title }}</h3>
                                    @if($comm->summary)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $comm->summary }}</p>
                                    @endif
                                </div>
                                <a href="{{ route('public.transparency.communication', $comm) }}" class="inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline shrink-0">Ler mais</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6">{{ $communications->links() }}</div>
            @endif
        </section>
    </div>
@endsection
