@extends('layouts.app')

@section('title', 'Página não encontrada — ' . \App\Support\SiteBranding::siteName())

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-900 px-4">
        <div class="text-center">
            <x-icon name="map-location-dot" class="h-24 w-24 text-indigo-500 mx-auto mb-6 opacity-80" />
            <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">404</h1>
            <p class="text-2xl font-light text-gray-600 dark:text-gray-400 mb-8">Página não encontrada</p>
            <p class="text-gray-500 dark:text-gray-500 mb-8 max-w-md mx-auto">
                Desculpe, não conseguimos encontrar a página que você está procurando. Ela pode ter sido removida ou o link pode estar incorreto.
            </p>
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <x-icon name="house" class="mr-2 h-5 w-5" />
                Voltar para o Início
            </a>
        </div>
    </div>
@endsection
