@extends($layout)

@section('title', 'Nova conta gateway')

@section('content')
<div class="mx-auto max-w-3xl space-y-6 p-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Nova conta PSP</h1>

    <form method="post" action="{{ route('admin.gateway.accounts.store') }}" class="space-y-4 rounded-xl border border-gray-200 bg-white p-6 dark:border-slate-600 dark:bg-slate-800">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium">Nome</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-slate-600 dark:bg-slate-900">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Driver</label>
            <select name="driver" class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-slate-600 dark:bg-slate-900">
                @foreach($drivers as $key => $label)
                    <option value="{{ $key }}" @selected(old('driver') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Base URL (opcional)</label>
            <input type="text" name="base_url" value="{{ old('base_url') }}" placeholder="https://api.stage.cora.com.br" class="w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-slate-600 dark:bg-slate-900">
        </div>
        <div class="flex gap-4">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_enabled" value="1" @checked(old('is_enabled', true))> Activo</label>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_default" value="1" @checked(old('is_default'))> Padrão</label>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Credenciais (JSON)</label>
            <textarea name="credentials_json" rows="12" required class="w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-xs dark:border-slate-600 dark:bg-slate-900" placeholder='{"client_id":"...","client_secret":"..."}'>{{ old('credentials_json') }}</textarea>
            <p class="mt-1 text-xs text-gray-500">Ver documentação Cora, Mercado Pago, Stripe e Pagar.me. Nunca partilhes estes dados.</p>
        </div>
        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Guardar</button>
    </form>
</div>
@endsection
