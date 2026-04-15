@extends('layouts.app')

@section('title', 'Adicionar jovem')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('lideres.congregacao.index') }}" class="text-emerald-700 dark:text-emerald-300 hover:underline">Congregação</a>
    <span class="text-slate-400">/</span>
    <span class="text-emerald-700 dark:text-emerald-300">Novo jovem</span>
@endsection

@section('content')
<div class="mx-auto max-w-2xl space-y-8">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Adicionar jovem à congregação</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
            Será criada uma conta com o papel <strong>Jovem JUBAF</strong> na mesma igreja que a tua conta. O e-mail deve ser único no sistema.
        </p>
    </div>

    <form method="post" action="{{ route('lideres.congregacao.jovens.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800 space-y-5">
        @csrf

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="first_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nome</label>
                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                @error('first_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Apelido</label>
                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                @error('last_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">E-mail</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Telemóvel (opcional)</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
            @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-600 dark:bg-slate-900/50">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="set_password_now" value="1" class="mt-1 rounded border-slate-300 text-emerald-600"
                    @checked(old('set_password_now')) id="set_password_now">
                <span>
                    <span class="font-medium text-slate-900 dark:text-white">Definir palavra-passe agora</span>
                    <span class="block text-sm text-slate-600 dark:text-slate-400">Se não assinalhar, enviamos um e-mail para a pessoa definir a palavra-passe (recomendado).</span>
                </span>
            </label>
            <div id="password-fields" class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 {{ old('set_password_now') ? '' : 'hidden' }}">
                <div>
                    <label for="password" class="block text-xs font-medium text-slate-600 dark:text-slate-400">Palavra-passe</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-slate-600 dark:text-slate-400">Confirmar</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                        class="mt-1 w-full rounded-xl border border-slate-300 px-3 py-2 text-sm dark:border-slate-600 dark:bg-slate-900">
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-emerald-700">
                Guardar
            </button>
            <a href="{{ route('lideres.congregacao.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('set_password_now')?.addEventListener('change', function () {
    document.getElementById('password-fields').classList.toggle('hidden', !this.checked);
});
</script>
@endpush
@endsection
