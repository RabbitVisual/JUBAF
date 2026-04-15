@php
    $links = [];
    if (module_enabled('Calendario') && \Illuminate\Support\Facades\Route::has('eventos.index')) {
        $links[] = ['label' => 'Eventos', 'url' => route('eventos.index'), 'icon' => 'calendar-days'];
    }
    if (module_enabled('Avisos') && \Illuminate\Support\Facades\Route::has('avisos.index')) {
        $links[] = ['label' => 'Avisos', 'url' => route('avisos.index'), 'icon' => 'bullhorn'];
    }
    if (module_enabled('Igrejas') && \Illuminate\Support\Facades\Route::has('igrejas.public.index')) {
        $links[] = ['label' => 'Congregações', 'url' => route('igrejas.public.index'), 'icon' => 'church'];
    }
    if (module_enabled('Bible') && \Illuminate\Support\Facades\Route::has('bible.public.index')) {
        $links[] = ['label' => 'Bíblia', 'url' => route('bible.public.index'), 'icon' => 'book-bible'];
    }
    if (module_enabled('Homepage') && \Illuminate\Support\Facades\Route::has('homepage')) {
        $links[] = ['label' => 'Site institucional', 'url' => route('homepage'), 'icon' => 'house'];
    }
@endphp
@if(count($links) > 0)
    <div class="rounded-2xl border border-slate-200/90 dark:border-slate-700 bg-white dark:bg-slate-900 p-5 shadow-sm">
        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-4">Recursos JUBAF</h3>
        <ul class="space-y-2">
            @foreach($links as $link)
                <li>
                    <a href="{{ $link['url'] }}" class="flex items-center gap-2 rounded-lg px-2 py-2 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                        <x-icon :name="$link['icon']" class="w-4 h-4 shrink-0 text-blue-600 dark:text-blue-400" />
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endif
