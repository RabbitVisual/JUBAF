@extends($layout)

@section('title', 'Utilizadores')

@section('content')
<div class="space-y-6 max-w-7xl">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-gray-200 dark:border-slate-700">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Utilizadores</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gerir contas e funções JUBAF</p>
        </div>
        <a href="{{ route($routePrefix.'.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
            <x-icon name="plus" class="w-5 h-5" />
            Novo utilizador
        </a>
    </div>

    <form method="get" action="{{ route($routePrefix.'.index') }}" class="flex flex-wrap gap-3 items-end bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-200 dark:border-slate-700">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Pesquisar</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nome, e-mail, telefone ou CPF"
                class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
        </div>
        <div class="min-w-[160px]">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Função</label>
            <select name="role" class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                <option value="">Todas</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(($filters['role'] ?? '') === $role->name)>{{ jubaf_role_label($role->name) }}</option>
                @endforeach
            </select>
        </div>
        <div class="min-w-[120px]">
            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Estado</label>
            <select name="active" class="w-full rounded-lg border border-gray-300 dark:border-slate-600 dark:bg-slate-900 px-3 py-2 text-sm">
                <option value="">Todos</option>
                <option value="1" @selected(($filters['active'] ?? '') === '1' || ($filters['active'] ?? null) === true)>Ativo</option>
                <option value="0" @selected(($filters['active'] ?? '') === '0' || ($filters['active'] ?? null) === false)>Inativo</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-gray-800 dark:bg-gray-700 rounded-lg hover:bg-gray-900">Filtrar</button>
    </form>

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs uppercase bg-gray-50 dark:bg-slate-900/50 text-gray-600 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Utilizador</th>
                        <th class="px-4 py-3">Contacto</th>
                        <th class="px-4 py-3">Funções</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-900/40">
                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            <div>{{ $user->email }}</div>
                            @if($user->phone)<div class="text-xs">{{ $user->phone }}</div>@endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-0.5 text-xs rounded-md bg-indigo-50 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200">{{ jubaf_role_label($role->name) }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->active)
                                <span class="text-emerald-600 dark:text-emerald-400 text-xs font-semibold">Ativo</span>
                            @else
                                <span class="text-red-600 dark:text-red-400 text-xs font-semibold">Inativo</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route($routePrefix.'.show', $user) }}" class="text-indigo-600 dark:text-indigo-400 text-xs font-semibold">Ver</a>
                            <a href="{{ route($routePrefix.'.edit', $user) }}" class="text-amber-600 dark:text-amber-400 text-xs font-semibold">Editar</a>
                            <form action="{{ route($routePrefix.'.toggle-status', $user) }}" method="post" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 dark:text-gray-400 text-xs font-semibold">{{ $user->active ? 'Desativar' : 'Ativar' }}</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhum utilizador encontrado.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-200 dark:border-slate-700">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
