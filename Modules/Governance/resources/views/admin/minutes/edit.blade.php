@extends('admin::components.layouts.master')

@section('title', 'Ata — '.$assembly->title)

@section('content')
    <div class="max-w-4xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ata</h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $assembly->title }} · {{ $assembly->scheduled_at?->format('d/m/Y') }}</p>
            <a href="{{ route('admin.governance.assemblies.show', $assembly) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">← Voltar à assembleia</a>
        </div>

        <form method="post" action="{{ route('admin.governance.assemblies.minute.update', $assembly) }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4 shadow-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select name="status" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                    <option value="draft" @selected(old('status', $minute->status ?? 'draft') === 'draft')>Rascunho</option>
                    <option value="approved" @selected(old('status', $minute->status) === 'approved')>Aprovada</option>
                    <option value="published" @selected(old('status', $minute->status) === 'published')>Publicada</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Texto da ata</label>
                <textarea name="body" rows="18" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white font-mono text-sm">{{ old('body', $minute->body) }}</textarea>
            </div>
            @if($minute->exists && $minute->slug)
                <p class="text-xs text-gray-500">Slug público: {{ $minute->slug }}</p>
            @endif
            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Guardar ata</button>
        </form>
    </div>
@endsection
