@extends($layout)

@section('title', 'Editar conta gateway')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 p-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar: {{ $account->name }}</h1>

    <form method="post" action="{{ route('admin.gateway.accounts.update', $account) }}" class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 dark:border-slate-600 dark:bg-slate-800">
        @csrf
        @method('PUT')
        <div>
            <label class="mb-1 block text-sm font-medium">Nome</label>
            <input type="text" name="name" value="{{ old('name', $account->name) }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-slate-600 dark:bg-slate-900">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Driver</label>
            <select name="driver" class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-slate-600 dark:bg-slate-900">
                @foreach($drivers as $key => $label)
                    <option value="{{ $key }}" @selected(old('driver', $account->driver) === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Base URL (opcional)</label>
            <input type="text" name="base_url" value="{{ old('base_url', $account->base_url) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-slate-600 dark:bg-slate-900">
        </div>
        <div class="flex gap-4">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_enabled" value="1" @checked(old('is_enabled', $account->is_enabled))> Activo</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_default" value="1" @checked(old('is_default', $account->is_default))> Padrão</label>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Credenciais (JSON)</label>
            <textarea name="credentials_json" rows="12" required class="w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-xs dark:border-slate-600 dark:bg-slate-900">{{ old('credentials_json', $credentials_json) }}</textarea>
        </div>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Actualizar</button>
    </form>

    @can('delete', $account)
        <form method="post" action="{{ route('admin.gateway.accounts.destroy', $account) }}" onsubmit="return confirm('Remover esta conta?');" class="pt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm font-semibold text-red-600 hover:underline">Eliminar conta</button>
        </form>
    @endcan
</div>
@endsection
