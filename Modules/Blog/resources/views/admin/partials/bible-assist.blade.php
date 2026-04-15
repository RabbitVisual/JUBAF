@if(module_enabled('Bible') && \Illuminate\Support\Facades\Route::has('api.v1.bible.find'))
    <div id="blog-bible-assist"
         class="mb-4 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900/60 p-4"
         data-find-url="{{ route('api.v1.bible.find') }}">
        <div class="flex flex-wrap items-center gap-2 mb-2">
            <x-icon name="book-bible" class="w-4 h-4 text-blue-600 dark:text-blue-400 shrink-0" />
            <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Consultar Bíblia</h4>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">
            Busque por referência (ex.: João 3:16, Sl 23:1-3) e insira o trecho no editor.
        </p>
        <div class="flex flex-col sm:flex-row flex-wrap gap-2">
            <input type="text"
                   id="blog-bible-ref-input"
                   autocomplete="off"
                   class="flex-1 min-w-[12rem] rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 py-2 text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-blue-500"
                   placeholder="Referência bíblica" />
            <button type="button"
                    id="blog-bible-ref-search"
                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Buscar
            </button>
        </div>
        <p id="blog-bible-ref-status" class="mt-2 text-xs text-slate-500 dark:text-slate-400 hidden" role="status"></p>
        <div id="blog-bible-ref-preview" class="mt-3 hidden max-h-48 overflow-y-auto rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 p-3 text-sm text-slate-700 dark:text-slate-200"></div>
        <div class="mt-3 flex flex-wrap gap-2">
            <button type="button"
                    id="blog-bible-insert"
                    class="hidden inline-flex items-center justify-center rounded-lg border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/40 px-4 py-2 text-sm font-semibold text-blue-800 dark:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-900/50">
                Inserir no editor
            </button>
        </div>
    </div>
@endif
