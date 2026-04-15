@extends($layout)

@section('title', 'Editar utilizador')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar {{ $user->name }}</h1>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 font-medium">Voltar</a>
    </div>

    <form action="{{ route($routePrefix.'.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dados básicos</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium mb-1">Nome *</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required maxlength="120"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    @error('first_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium mb-1">Sobrenome</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" maxlength="120"
                        class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    @error('last_name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium mb-1">E-mail *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="cpf" class="block text-sm font-medium mb-1">CPF</label>
                <input type="text" id="cpf" name="cpf" value="{{ old('cpf', $user->cpf) }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('cpf')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium mb-1">Telefone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="church_phone" class="block text-sm font-medium mb-1">Telefone (função / igreja)</label>
                <input type="text" id="church_phone" name="church_phone" value="{{ old('church_phone', $user->church_phone) }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('church_phone')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="birth_date" class="block text-sm font-medium mb-1">Data de nascimento</label>
                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('birth_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="block text-sm font-medium mb-1">Nova palavra-passe (opcional)</label>
                <input type="password" id="password" name="password"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar palavra-passe</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Funções</h2>
            @foreach($roles as $role)
                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-100 dark:border-slate-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-900/50">
                    <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                        @checked(in_array($role->name, old('roles', $user->roles->pluck('name')->all()), true))
                        class="rounded border-gray-300 text-indigo-600">
                    <span class="text-sm text-gray-900 dark:text-white">{{ jubaf_role_label($role->name) }}</span>
                </label>
            @endforeach
            @error('roles')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        @if(isset($jubafSectors) && $jubafSectors->isNotEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-3">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Setor (vice-presidência)</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Para utilizadores com função de Vice-Presidente: ao atribuir um setor, o acesso a igrejas, secretaria e tesouraria no painel da diretoria fica limitado a esse setor. Deixe vazio para visão associacional completa (comportamento anterior).</p>
            <div>
                <label for="jubaf_sector_id" class="block text-sm font-medium mb-1">Setor ERP</label>
                <select name="jubaf_sector_id" id="jubaf_sector_id"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    <option value="">— Não atribuir —</option>
                    @foreach($jubafSectors as $sec)
                        <option value="{{ $sec->id }}" @selected(old('jubaf_sector_id', $user->jubaf_sector_id) == $sec->id)>{{ $sec->name }}</option>
                    @endforeach
                </select>
                @error('jubaf_sector_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        @if(module_enabled('Igrejas') && isset($churches) && $churches->isNotEmpty())
        @php
            $extraChurchIds = old('assigned_church_ids', $user->assignedChurches->pluck('id')->reject(fn ($id) => (int) $id === (int) $user->church_id)->values()->all());
        @endphp
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Igreja(s) e congregações</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">Congregação principal obrigatória para Líder ou Jovem. Líderes e pastores podem vincular congregações adicionais.</p>
            <div>
                <label for="church_id" class="block text-sm font-medium mb-1">Congregação principal</label>
                <select name="church_id" id="church_id"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    <option value="">— Selecionar —</option>
                    @foreach($churches as $ch)
                        <option value="{{ $ch->id }}" @selected(old('church_id', $user->church_id) == $ch->id)>{{ $ch->name }}@if($ch->city) ({{ $ch->city }})@endif</option>
                    @endforeach
                </select>
                @error('church_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="assigned_church_ids" class="block text-sm font-medium mb-1">Congregações adicionais (Ctrl+clique)</label>
                <select name="assigned_church_ids[]" id="assigned_church_ids" multiple size="5"
                    class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                    @foreach($churches as $ch)
                        <option value="{{ $ch->id }}" @selected(in_array($ch->id, $extraChurchIds, true))>{{ $ch->name }}@if($ch->city) ({{ $ch->city }})@endif</option>
                    @endforeach
                </select>
                @error('assigned_church_ids')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                @error('assigned_church_ids.*')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 p-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="active" value="1" @checked(old('active', $user->active)) class="rounded border-gray-300 text-emerald-600">
                <span class="text-sm text-gray-900 dark:text-white">Conta ativa</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route($routePrefix.'.index') }}" class="px-4 py-2 text-sm font-medium border border-gray-300 dark:border-slate-600 rounded-lg">Cancelar</a>
            <button type="submit" class="px-6 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">Guardar</button>
        </div>
    </form>

    <form action="{{ route($routePrefix.'.destroy', $user) }}" method="POST" onsubmit="return confirm('Eliminar este utilizador?');" class="pt-4 border-t border-gray-200 dark:border-slate-700">
        @csrf
        @method('DELETE')
        <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">Eliminar utilizador</button>
    </form>
</div>
@endsection
