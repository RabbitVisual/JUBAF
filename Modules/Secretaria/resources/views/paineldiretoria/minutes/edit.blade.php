@extends($layout)

@section('title', 'Editar ata')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10">
    @include('secretaria::paineldiretoria.partials.subnav', ['active' => 'atas'])

    <div class="border-b border-gray-200 pb-6 dark:border-slate-700">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-400">Secretaria · Atas</p>
        <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Editar ata</h1>
    </div>

    <form action="{{ route($routePrefix.'.update', $minute) }}" method="POST" enctype="multipart/form-data" class="mx-auto max-w-3xl space-y-6 rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        @csrf @method('PUT')
        @include('secretaria::paineldiretoria.minutes._form')
        <div class="rounded-xl border border-dashed border-gray-300 p-4 dark:border-slate-600">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Anexos e arquivo</p>
            <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">PDF, Word ou imagens até 15 MB por ficheiro. Inclua atas anteriores ou ofícios quando necessário.</p>
            <div class="mt-3">
                <label class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300">Tipo de anexo</label>
                <select name="attachment_kind" class="w-full max-w-md rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                    <option value="attachment" @selected(old('attachment_kind') === 'attachment')>Anexo geral</option>
                    <option value="ata_anterior" @selected(old('attachment_kind') === 'ata_anterior')>Ata ou documento anterior</option>
                    <option value="oficio" @selected(old('attachment_kind') === 'oficio')>Ofício</option>
                </select>
            </div>
            <div class="mt-3">
                <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-emerald-700 dark:text-gray-400">
                @error('attachments')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                @error('attachments.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/25 transition hover:bg-emerald-700">Guardar</button>
    </form>
    <div class="mx-auto flex max-w-3xl flex-wrap gap-2">
        @can('submit', $minute)<form action="{{ route($routePrefix.'.submit', $minute) }}" method="POST">@csrf<button type="submit" class="rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-amber-700">Solicitar assinaturas</button></form>@endcan
        @can('sign', $minute)
            <form action="{{ route($routePrefix.'.sign', $minute) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="password" name="password" placeholder="Senha atual" class="rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600">Assinar ata</button>
            </form>
        @endcan
        @can('downloadPdf', $minute)<a href="{{ route($routePrefix.'.pdf', $minute) }}" class="inline-flex items-center rounded-xl border border-emerald-200 bg-emerald-50/80 px-4 py-2.5 text-sm font-bold text-emerald-900 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-100 dark:hover:bg-emerald-900/40">PDF</a>@endcan
    </div>
</div>
@endsection
