<x-layouts.app title="Manutenção — {{ \App\Support\SiteBranding::siteName() }}">
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-slate-50 dark:bg-slate-900 px-4 py-12">
        <div class="text-center max-w-lg">
            <x-icon name="screwdriver-wrench" class="h-24 w-24 text-amber-500 mx-auto mb-6 opacity-90" style="duotone" />
            <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-2">503</h1>
            <p class="text-2xl font-light text-gray-600 dark:text-gray-400 mb-4">Serviço indisponível</p>
            <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                Estamos em manutenção ou sob carga elevada. Tente novamente dentro de alguns minutos.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                <button type="button" onclick="window.location.reload()"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    <x-icon name="rotate-right" class="mr-2 h-5 w-5" />
                    Tentar novamente
                </button>
                <a href="{{ url('/') }}"
                    class="inline-flex items-center justify-center px-6 py-3 rounded-md border border-gray-300 dark:border-slate-600 text-base font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                    <x-icon name="house" class="mr-2 h-5 w-5" />
                    Página inicial
                </a>
            </div>
            <p class="mt-8 text-sm text-gray-500 dark:text-gray-500">
                Obrigado pela paciência — equipa JUBAF.
            </p>
        </div>
    </div>
</x-layouts.app>
