{{-- Layout de página institucional no /painel (alinhado à Tesouraria: fundo cinza, cabeçalho 3xl, badge de área). --}}
<div class="min-h-screen bg-gray-50 dark:bg-slate-950 transition-colors duration-200 pb-12">
    <div class="max-w-7xl mx-auto space-y-8 px-6 pt-8">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $title }}</h1>
                @if (! empty($subtitle))
                    <p class="text-gray-500 dark:text-slate-400 mt-1 max-w-lg">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex items-center gap-3 flex-wrap justify-end md:justify-start">
                @isset($actions)
                    {{ $actions }}
                @endisset
                <div
                    class="px-4 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl shadow-sm flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span
                        class="text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider">{{ $badge ?? 'Painel' }}</span>
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
</div>
