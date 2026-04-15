@extends('layouts.app')

@section('title', 'Acesso negado — ' . \App\Support\SiteBranding::siteName())

@section('content')
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-slate-50 dark:bg-slate-900 px-4 py-12">
        <div class="text-center max-w-lg">
            <x-icon name="lock" class="h-24 w-24 text-amber-500 mx-auto mb-6 opacity-90" style="duotone" />
            <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">403</h1>
            <p class="text-2xl font-light text-gray-600 dark:text-gray-400 mb-4">Acesso negado</p>
            <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                {{ isset($exception) && $exception?->getMessage()
                    ? $exception->getMessage()
                    : 'Não tem permissão para aceder a este recurso.' }}
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <button type="button" onclick="window.history.back()"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-md border border-gray-300 dark:border-slate-600 text-base font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                    <x-icon name="arrow-left" class="mr-2 h-5 w-5" />
                    Voltar
                </button>
                @auth
                    @php
                        $dash = get_dashboard_route();
                    @endphp
                    <a href="{{ \Illuminate\Support\Facades\Route::has($dash) ? route($dash) : url('/') }}"
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <x-icon name="house" class="mr-2 h-5 w-5" />
                        Painel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <x-icon name="right-to-bracket" class="mr-2 h-5 w-5" />
                        Iniciar sessão
                    </a>
                @endauth
            </div>
            <p class="mt-8 text-sm text-gray-500 dark:text-gray-500">
                Se precisar de acesso, contacte a direção ou o suporte JUBAF.
            </p>
        </div>
    </div>
@endsection
