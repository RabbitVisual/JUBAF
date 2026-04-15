@extends('layouts.app')

@section('title', 'Enviar Atualização')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-16 font-sans animate-fade-in">
    <header class="overflow-hidden rounded-3xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/90 via-white to-white p-6 shadow-sm dark:border-indigo-900/25 dark:from-indigo-950/35 dark:via-slate-900 dark:to-slate-900 md:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold uppercase tracking-widest text-indigo-600 dark:text-indigo-400">Upload · Pacote</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white md:text-3xl">Nova actualização</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-slate-400">
                    Envie um ficheiro <strong class="font-semibold text-gray-800 dark:text-slate-200">.zip</strong> assinado, defina backup e instalação automática. O sistema valida a assinatura antes de guardar.
                </p>
                <nav class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-500 dark:text-slate-500" aria-label="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Admin</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <a href="{{ route('admin.updates.index') }}" class="transition hover:text-indigo-600 dark:hover:text-indigo-400">Atualizações</a>
                    <x-icon name="chevron-right" class="h-3 w-3 shrink-0 opacity-70" style="duotone" />
                    <span class="font-medium text-gray-800 dark:text-slate-300">Upload</span>
                </nav>
            </div>
            <div class="shrink-0">
                <a href="{{ route('admin.updates.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                    <x-icon name="arrow-left" class="h-4 w-4" style="solid" />
                    Voltar à lista
                </a>
            </div>
        </div>
    </header>

    <div class="flex gap-4 rounded-2xl border border-violet-200/80 bg-violet-50/90 p-4 dark:border-violet-900/40 dark:bg-violet-950/30 md:items-center md:p-5">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-200/80 text-violet-900 dark:bg-violet-900/60 dark:text-violet-200">
            <x-icon name="shield-halved" class="h-5 w-5" style="duotone" />
        </span>
        <p class="min-w-0 text-sm leading-relaxed text-violet-950/95 dark:text-violet-100/90">
            <span class="font-semibold text-violet-900 dark:text-violet-100">Pacotes de confiança</span>
            — use apenas ZIPs da sua equipa ou fornecedor oficial. Ficheiros corrompidos ou maliciosos podem comprometer o ambiente.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        <div class="space-y-8 lg:col-span-2">
            <div class="overflow-hidden rounded-3xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50/80 to-white px-5 py-4 dark:border-slate-700 dark:from-slate-900/50 dark:to-slate-800/80 sm:px-6">
                    <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                        <x-icon name="upload" style="duotone" class="h-5 w-5 text-indigo-500" />
                        Pacote de actualização
                    </h2>
                    <p class="mt-0.5 text-sm text-gray-500 dark:text-slate-400">Arraste ou seleccione o ficheiro e configure as opções de instalação</p>
                </div>
                <div class="p-6 md:p-8">
                    <form action="{{ route('admin.updates.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Arquivo ZIP (.zip) <span class="text-red-500">*</span>
                            </label>
                            <div class="group relative mt-1 flex cursor-pointer justify-center rounded-2xl border-2 border-dashed border-slate-200 px-6 pb-10 pt-10 transition-all hover:border-indigo-400 hover:bg-indigo-50/30 dark:border-slate-600 dark:hover:border-indigo-500 dark:hover:bg-indigo-900/10">
                                <input id="update_file" name="update_file" type="file" accept=".zip" required class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" onchange="updateFileName(this)">
                                <div class="pointer-events-none space-y-2 text-center">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-500 transition-transform group-hover:scale-110 dark:bg-indigo-900/20">
                                        <x-icon name="cloud-arrow-up" style="duotone" class="h-8 w-8" />
                                    </div>
                                    <div class="flex flex-col items-center text-sm text-gray-600 dark:text-gray-400">
                                        <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">Clique para upload</span>
                                        <span class="text-slate-500">ou arraste e solte o arquivo aqui</span>
                                    </div>
                                    <p class="pt-2 text-xs text-slate-400" id="file-name">Nenhum arquivo selecionado</p>
                                    <p class="text-[10px] uppercase tracking-wide text-slate-400">Tamanho máximo: 100MB</p>
                                </div>
                            </div>
                            @error('update_file')
                                <p class="mt-2 flex items-center gap-1 text-sm font-medium text-red-600 dark:text-red-400">
                                    <x-icon name="circle-exclamation" class="h-4 w-4" />
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-4 pt-4">
                            <h3 class="mb-4 border-b border-gray-100 pb-2 text-sm font-bold uppercase tracking-wider text-gray-900 dark:border-slate-700 dark:text-white">Opções de instalação</h3>

                            <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4 transition-colors hover:border-indigo-300 dark:border-slate-700 dark:bg-slate-900/30 dark:hover:border-indigo-700">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" name="create_backup" id="create_backup" value="1" checked class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-slate-600 dark:bg-slate-700" onchange="updateBackupStatus(this)">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">Criar backup de segurança</span>
                                        <span id="backup-badge" class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Recomendado</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500" id="backup-description">
                                        Cria um ponto de restauração completo antes de aplicar as mudanças.
                                    </p>
                                </div>
                            </label>

                            <label class="relative flex cursor-pointer items-start gap-4 rounded-xl border border-gray-200 bg-gray-50/50 p-4 transition-colors hover:border-indigo-300 dark:border-slate-700 dark:bg-slate-900/30 dark:hover:border-indigo-700">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" name="auto_apply" id="auto_apply" value="1" class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600 dark:border-slate-600 dark:bg-slate-700" onchange="updateAutoApplyStatus(this)">
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">Instalação automática</span>
                                        <span id="auto-apply-badge" class="inline-flex items-center rounded px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">Manual</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500" id="auto-apply-description">
                                        Se marcado, a atualização será aplicada imediatamente após o upload.
                                    </p>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center gap-3 border-t border-gray-100 pt-6 dark:border-slate-700">
                            <a href="{{ route('admin.updates.index') }}" class="px-6 py-3.5 text-sm font-bold uppercase tracking-wider text-slate-500 transition-colors hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">
                                Cancelar
                            </a>
                            <button type="submit" class="group inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-white shadow-lg shadow-indigo-500/30 transition-all hover:bg-indigo-700 active:scale-95 sm:flex-none">
                                <x-icon name="cloud-arrow-up" class="h-5 w-5 transition-transform group-hover:-translate-y-1" />
                                Iniciar upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="space-y-6 lg:col-span-1">
            <div class="rounded-3xl border border-indigo-100 bg-indigo-50/90 p-6 dark:border-indigo-900/30 dark:bg-indigo-950/25">
                <div class="mb-4 flex items-center gap-3">
                    <div class="rounded-lg bg-indigo-100 p-2 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                        <x-icon name="circle-info" class="h-5 w-5" />
                    </div>
                    <h3 class="font-bold text-indigo-900 dark:text-indigo-200">Recomendações</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-indigo-100 bg-white text-xs font-bold text-indigo-600 dark:border-indigo-900 dark:bg-slate-800">1</div>
                        <p class="text-sm text-indigo-800 dark:text-indigo-300">Verifique se o arquivo possui a extensão <strong>.zip</strong> oficial.</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-indigo-100 bg-white text-xs font-bold text-indigo-600 dark:border-indigo-900 dark:bg-slate-800">2</div>
                        <p class="text-sm text-indigo-800 dark:text-indigo-300">Mantenha o <strong>backup</strong> activado para poder reverter.</p>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full border border-indigo-100 bg-white text-xs font-bold text-indigo-600 dark:border-indigo-900 dark:bg-slate-800">3</div>
                        <p class="text-sm text-indigo-800 dark:text-indigo-300">Evite actualizar em horários de pico de utilização.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80">
                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Versão actual (app)</h3>
                <p class="flex items-center gap-2 text-2xl font-bold text-gray-900 dark:text-white">
                    v{{ config('app.version', '1.0.0') }}
                    <span class="h-2 w-2 animate-pulse rounded-full bg-emerald-500"></span>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name || 'Nenhum arquivo selecionado';
    document.getElementById('file-name').textContent = fileName;
    document.getElementById('file-name').classList.add('text-indigo-600', 'font-medium');
}

function updateBackupStatus(checkbox) {
    const description = document.getElementById('backup-description');
    const badge = document.getElementById('backup-badge');

    if (checkbox.checked) {
        description.textContent = 'Cria um ponto de restauração completo antes de aplicar as mudanças.';
        badge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
        badge.textContent = 'Recomendado';
    } else {
        description.textContent = 'Atenção: Você não poderá reverter as alterações automaticamente.';
        badge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
        badge.textContent = 'Não Recomendado';
    }
}

function updateAutoApplyStatus(checkbox) {
    const description = document.getElementById('auto-apply-description');
    const badge = document.getElementById('auto-apply-badge');

    if (checkbox.checked) {
        description.textContent = 'O sistema aplicará as mudanças imediatamente. Certifique-se da integridade do arquivo.';
        badge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400';
        badge.textContent = 'Automático';
    } else {
        description.textContent = 'Se marcado, a atualização será aplicada imediatamente após o upload.';
        badge.className = 'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400';
        badge.textContent = 'Manual';
    }
}
</script>
@endpush
@endsection
