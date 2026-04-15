@extends('admin::layouts.admin')

@section('title', 'Carregar documento')

@section('content')
<div class="max-w-xl space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Carregar documento</h1>
    <form action="{{ route($routePrefix.'.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-white dark:bg-slate-800 border rounded-xl p-6">@csrf
        <div><label class="block text-sm font-medium mb-1">Título *</label><input type="text" name="title" required class="w-full rounded-lg border dark:bg-slate-900 px-3 py-2 text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Visibilidade *</label>
            <select name="visibility" class="w-full rounded-lg border dark:bg-slate-900 px-3 py-2 text-sm">
                <option value="directorate">Diretoria</option>
                <option value="leaders">Líderes</option>
                <option value="public">Público</option>
            </select>
        </div>
        <div><label class="block text-sm font-medium mb-1">Igreja (opcional)</label>
            <select name="church_id" class="w-full rounded-lg border dark:bg-slate-900 px-3 py-2 text-sm"><option value="">—</option>@foreach($churches as $ch)<option value="{{ $ch->id }}">{{ $ch->name }}</option>@endforeach</select>
        </div>
        <div><label class="block text-sm font-medium mb-1">Ficheiro *</label><input type="file" name="file" required class="text-sm"></div>
        <button class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-semibold">Enviar</button>
    </form>
</div>
@endsection
