@extends('memberpanel::components.layouts.master')

@section('page-title', 'Ata — '.$assembly->title)

@section('content')
    @component('memberpanel::components.institutional-page', [
        'title' => 'Ata',
        'subtitle' => $assembly->title.' · '.$assembly->scheduled_at?->translatedFormat('d/m/Y H:i'),
        'badge' => 'Governança',
    ])
        <a href="{{ route('memberpanel.governance.assemblies.show', $assembly) }}"
            class="inline-flex items-center text-sm font-semibold text-violet-600 dark:text-violet-400 hover:underline mb-2">
            <x-icon name="arrow-left" class="w-4 h-4 mr-1" /> Voltar à assembleia
        </a>

        <div
            class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 md:p-8 shadow-sm space-y-4">
            <form method="post" action="{{ route('memberpanel.governance.assemblies.minute.update', $assembly) }}">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Estado</label>
                    <select name="status"
                        class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        <option value="draft" @selected(old('status', $minute->status ?? 'draft') === 'draft')>Rascunho</option>
                        <option value="approved" @selected(old('status', $minute->status) === 'approved')>Aprovada</option>
                        <option value="published" @selected(old('status', $minute->status) === 'published')>Publicada</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Texto da ata</label>
                    <textarea name="body" rows="18" required
                        class="w-full rounded-xl border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white font-mono text-sm">{{ old('body', $minute->body) }}</textarea>
                </div>
                @if ($minute->exists && $minute->slug)
                    <p class="text-xs text-gray-500">Slug público: {{ $minute->slug }}</p>
                @endif
                <div class="pt-2">
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-700 text-white text-sm font-bold shadow-lg shadow-violet-500/20">
                        Guardar ata
                    </button>
                </div>
            </form>

            @if ($minute->exists && $minute->status !== 'published')
                <form method="post" action="{{ route('memberpanel.governance.assemblies.minute.publish', $assembly) }}" class="pt-4 border-t border-gray-100 dark:border-slate-800"
                    onsubmit="return confirm('Publicar esta ata no site?');">
                    @csrf
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-bold hover:bg-emerald-50 dark:hover:bg-emerald-900/20">
                        Publicar no site (rápido)
                    </button>
                </form>
            @endif
        </div>
    @endcomponent
@endsection
