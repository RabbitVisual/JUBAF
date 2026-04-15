@props(['aviso' => null])

@if($aviso instanceof \Modules\Avisos\App\Models\Aviso)
    @php
        $cienteRoute = match (true) {
            request()->routeIs('diretoria.*') && \Illuminate\Support\Facades\Route::has('diretoria.avisos.ciente') => route('diretoria.avisos.ciente', $aviso),
            request()->routeIs('lideres.*') && \Illuminate\Support\Facades\Route::has('lideres.avisos.ciente') => route('lideres.avisos.ciente', $aviso),
            request()->routeIs('pastor.*') && \Illuminate\Support\Facades\Route::has('pastor.avisos.ciente') => route('pastor.avisos.ciente', $aviso),
            request()->routeIs('jovens.*') && \Illuminate\Support\Facades\Route::has('jovens.avisos.ciente') => route('jovens.avisos.ciente', $aviso),
            default => null,
        };
    @endphp

    @if($cienteRoute)
        <div id="aviso-institucional-banner" class="mb-4 md:mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm dark:border-amber-900/50 dark:bg-amber-950/40" role="alert">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-200">
                        <x-icon name="triangle-exclamation" class="h-5 w-5" style="solid" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-950 dark:text-amber-100">{{ $aviso->titulo }}</p>
                        @if($aviso->descricao)
                            <p class="mt-1 text-sm text-amber-900/90 dark:text-amber-200/90">{{ \Illuminate\Support\Str::limit(strip_tags($aviso->descricao), 220) }}</p>
                        @endif
                    </div>
                </div>
                <form action="{{ $cienteRoute }}" method="post" class="shrink-0">
                    @csrf
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-amber-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-800 dark:bg-amber-600 dark:hover:bg-amber-500 sm:w-auto">
                        Ciente
                    </button>
                </form>
            </div>
        </div>
    @endif
@endif
