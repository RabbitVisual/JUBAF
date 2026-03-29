@extends('memberpanel::components.layouts.master')

@section('page-title', 'Acessos ao painel')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Delegar acessos</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Autorize outros utilizadores a ver secções específicas do painel de membros.</p>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <form method="post" action="{{ route('memberpanel.panel-access.store') }}" class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6 space-y-4">
            @csrf
            <h2 class="font-semibold text-gray-900 dark:text-white">Conceder ou renovar</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Utilizador</label>
                    <select name="user_id" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        <option value="">—</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Módulo</label>
                    <select name="module_key" required class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                        @foreach(\App\Services\MemberPanelAccess::moduleKeys() as $key)
                            <option value="{{ $key }}">{{ \App\Services\MemberPanelAccess::label($key) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Expira em (opcional)</label>
                    <input type="datetime-local" name="expires_at" class="w-full rounded-xl border-gray-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                </div>
            </div>
            <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium">Guardar</button>
        </form>

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 overflow-hidden">
            <h2 class="font-semibold text-gray-900 dark:text-white px-6 py-4 border-b border-gray-100 dark:border-slate-800">Concessões atuais</h2>
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-800/50 text-left">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Utilizador</th>
                        <th class="px-4 py-3 font-semibold">Módulo</th>
                        <th class="px-4 py-3 font-semibold">Concedido por</th>
                        <th class="px-4 py-3 font-semibold">Expira</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse($grants as $g)
                        <tr>
                            <td class="px-4 py-3">{{ $g->user?->name }}</td>
                            <td class="px-4 py-3">{{ \App\Services\MemberPanelAccess::label($g->module_key) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $g->grantedBy?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $g->expires_at?->format('d/m/Y H:i') ?: '—' }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="post" action="{{ route('memberpanel.panel-access.destroy', $g) }}" class="inline" onsubmit="return confirm('Revogar?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 text-xs hover:underline">Revogar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhuma concessão registada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div>{{ $grants->links() }}</div>
    </div>
@endsection
