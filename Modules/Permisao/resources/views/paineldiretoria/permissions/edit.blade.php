@extends($layout)

@section('title', 'Editar permissão')

@section('content')
<div class="mx-auto max-w-2xl space-y-8 pb-10">
    @include('permisao::paineldiretoria.partials.subnav', ['active' => 'permissions'])

    @include('permisao::paineldiretoria.partials.rbac-context', ['step' => 'permissions'])

    <div class="flex flex-col gap-2 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Painel diretoria</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Editar permissão custom</h1>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Voltar à lista</a>
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
