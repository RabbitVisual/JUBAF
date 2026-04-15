@extends($layout)

@section('title', 'Editar permissão')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar permissão custom</h1>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Voltar</a>
    </div>

    <form action="{{ route($routePrefix.'.update', $permission) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium mb-1">Nome técnico *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" required maxlength="255"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm font-mono">
                @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <p class="text-xs text-slate-500">Alterar o nome pode exigir atualizar funções (roles) que a referenciam.</p>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Guardar</button>
            <a href="{{ route($routePrefix.'.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-slate-600 rounded-lg">Cancelar</a>
        </div>
    </form>
</div>
@endsection
