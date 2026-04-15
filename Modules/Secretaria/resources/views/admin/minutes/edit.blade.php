@extends('admin::layouts.admin')

@section('title', 'Editar ata')

@section('content')
<div class="max-w-3xl space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar ata</h1>
    <form action="{{ route($routePrefix.'.update', $minute) }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-slate-800 border rounded-xl p-6">@csrf @method('PUT')
        @include('secretaria::paineldiretoria.minutes._form')
        <div class="rounded-lg border border-dashed border-gray-300 p-4 dark:border-slate-600">
            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300">Anexos (PDF, Word, imagens até 15 MB)</p>
            <select name="attachment_kind" class="mt-2 w-full max-w-md rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900 dark:text-white">
                <option value="attachment">Anexo geral</option>
                <option value="ata_anterior">Ata ou documento anterior</option>
                <option value="oficio">Ofício</option>
            </select>
            <input type="file" name="attachments[]" multiple class="mt-2 block w-full text-sm">
            @error('attachments')<p class="text-red-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold">Guardar</button>
    </form>
    <div class="flex flex-wrap gap-2">
        @can('submit', $minute)<form action="{{ route($routePrefix.'.submit', $minute) }}" method="POST">@csrf<button class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm">Enviar aprovação</button></form>@endcan
        @can('approve', $minute)<form action="{{ route($routePrefix.'.approve', $minute) }}" method="POST">@csrf<button class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm">Aprovar</button></form>@endcan
        @can('publish', $minute)<form action="{{ route($routePrefix.'.publish', $minute) }}" method="POST" onsubmit="return confirm('Publicar e bloquear edição?');">@csrf<button class="px-4 py-2 bg-violet-600 text-white rounded-lg text-sm">Publicar</button></form>@endcan
        @can('downloadPdf', $minute)<a href="{{ route($routePrefix.'.pdf', $minute) }}" class="px-4 py-2 border rounded-lg text-sm">PDF</a>@endcan
    </div>
</div>
@endsection
