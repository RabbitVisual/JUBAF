{{-- Barra de ações: mesmo alinhamento e estilo dos cartões do formulário --}}
{{-- @var string $submitLabel --}}
<div class="flex flex-col-reverse gap-3 rounded-2xl border border-gray-200/90 bg-white p-4 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:px-6 sm:py-5">
    <a
        href="{{ route('diretoria.carousel.index') }}"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-900 dark:text-gray-200 dark:hover:bg-slate-700 sm:w-auto sm:border-0 sm:bg-transparent sm:px-4 sm:shadow-none dark:sm:bg-transparent"
    >
        <x-icon name="xmark" class="h-5 w-5 shrink-0" style="solid" />
        Cancelar
    </a>
    <button
        type="submit"
        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-pink-600 to-pink-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-pink-500/25 transition hover:from-pink-700 hover:to-pink-800 focus:outline-none focus:ring-4 focus:ring-pink-300/50 sm:w-auto sm:min-w-[11rem] dark:focus:ring-pink-900/50"
    >
        <x-icon name="check" class="h-5 w-5 shrink-0" style="solid" />
        {{ $submitLabel }}
    </button>
</div>
