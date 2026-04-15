@extends($layout)

@section('title', 'Criar utilizador')

@section('content')
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('permisao::paineldiretoria.partials.subnav', ['active' => 'users'])

    @include('permisao::paineldiretoria.partials.rbac-context', ['step' => 'users'])

    <div class="flex flex-col gap-2 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Painel diretoria</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Criar utilizador</h1>
        </div>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline">Voltar à lista</a>
    </div>

    <form action="{{ route($routePrefix.'.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dados básicos</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium mb-1">Nome *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required maxlength="120"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    @error('first_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium mb-1">Sobrenome</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" maxlength="120"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    @error('last_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium mb-1">E-mail *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="cpf" class="block text-sm font-medium mb-1">CPF</label>
                <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('cpf')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium mb-1">Telefone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="church_phone" class="block text-sm font-medium mb-1">Telefone (função / igreja)</label>
                <input type="text" id="church_phone" name="church_phone" value="{{ old('church_phone') }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Contacto profissional ou da congregação, se aplicável.</p>
                @error('church_phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="birth_date" class="block text-sm font-medium mb-1">Data de nascimento</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('birth_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium mb-1">Palavra-passe *</label>
                <input type="password" id="password" name="password" required
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar palavra-passe *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Funções</h2>
            @foreach($roles as $role)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 dark:border-slate-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-900/50">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        @checked(in_array($role->name, old('roles', []), true))
                        class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-900 dark:text-white">{{ jubaf_role_label($role->name) }}</span>
                </label>
            @endforeach
            @error('roles')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        @if(module_enabled('Igrejas') && isset($churches) && $churches->isNotEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Igreja(s) e congregações</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Congregação principal obrigatória para Líder ou Jovem. Líderes e pastores podem vincular congregações adicionais (multi-igreja).</p>
            <div>
                <label for="church_id" class="block text-sm font-medium mb-1">Congregação principal</label>
                <select name="church_id" id="church_id"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    <option value="">— Selecionar —</option>
                    @foreach($churches as $ch)
                        <option value="{{ $ch->id }}" @selected(old('church_id') == $ch->id)>{{ $ch->name }}@if($ch->city) ({{ $ch->city }})@endif</option>
                    @endforeach
                </select>
                @error('church_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="assigned_church_ids" class="block text-sm font-medium mb-1">Congregações adicionais (Ctrl+clique)</label>
                <select name="assigned_church_ids[]" id="assigned_church_ids" multiple size="5"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    @foreach($churches as $ch)
                        <option value="{{ $ch->id }}" @selected(in_array($ch->id, old('assigned_church_ids', []), true))>{{ $ch->name }}@if($ch->city) ({{ $ch->city }})@endif</option>
                    @endforeach
                </select>
                @error('assigned_church_ids')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                @error('assigned_church_ids.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="active" value="1" @checked(old('active', true)) class="rounded border-gray-300 text-emerald-600">
                <span class="text-sm text-gray-900 dark:text-white">Conta ativa (pode iniciar sessão)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route($routePrefix.'.index') }}" class="px-4 py-2 text-sm font-medium border border-gray-300 dark:border-slate-600 rounded-lg">Cancelar</a>
            <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Criar</button>
        </div>
    </form>
</div>
@endsection
