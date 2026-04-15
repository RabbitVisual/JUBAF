@extends('layouts.app')

@section('title', 'Pedidos de alteração de dados')

@section('content')
<div class="mx-auto max-w-7xl space-y-6 md:space-y-8 animate-fade-in pb-12 font-sans">
    @include('paineldiretoria::partials.profile-subnav', ['active' => 'pedidos'])

    <div class="relative overflow-hidden rounded-3xl border border-amber-200/60 bg-gradient-to-br from-amber-50/90 via-white to-orange-50/40 p-6 shadow-md dark:border-amber-900/30 dark:from-amber-950/25 dark:via-slate-900 dark:to-slate-900 sm:p-8">
        <div class="relative">
            <nav aria-label="breadcrumb" class="mb-3 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                <a href="{{ route('diretoria.dashboard') }}" class="font-medium text-amber-800 hover:underline dark:text-amber-400">Painel</a>
                <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                <a href="{{ route('diretoria.profile') }}" class="font-medium text-amber-800 hover:underline dark:text-amber-400">Perfil</a>
                <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" style="duotone" />
                <span class="font-semibold text-gray-900 dark:text-white">Pedidos sensíveis</span>
            </nav>
            <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg">
                    <x-icon name="envelope-open-text" class="h-7 w-7" style="duotone" />
                </span>
                Alterações de e-mail e CPF
            </h1>
            <p class="mt-2 max-w-3xl text-sm text-gray-600 dark:text-gray-400">
                Utilizadores pedem alteração de dados sensíveis; aqui a diretoria aprova ou rejeita antes de aplicar no sistema.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700 bg-amber-50/50 dark:bg-amber-900/10">
            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider flex items-center gap-2">
                <x-icon name="clock" style="duotone" class="w-5 h-5 text-amber-600" />
                Pendentes ({{ $pending->count() }})
            </h2>
        </div>
        <div class="p-6 md:p-8">
            @forelse($pending as $req)
                @php
                    $u = $req->user;
                @endphp
                <div class="mb-8 last:mb-0 pb-8 last:pb-0 border-b border-gray-100 dark:border-slate-700 last:border-0">
                    <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                        <div class="flex items-start gap-4 min-w-0 flex-1">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-slate-200 dark:border-slate-600 shrink-0 bg-slate-100 dark:bg-slate-700">
                                @if($u && user_photo_url($u))
                                    <img src="{{ user_photo_url($u) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-lg font-bold text-indigo-600">{{ $u ? strtoupper(mb_substr($u->name, 0, 1)) : '?' }}</div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 dark:text-white">{{ $u?->name ?? 'Utilizador' }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    @foreach($u?->roles ?? [] as $role)
                                        <span class="inline-flex mr-1">{{ jubaf_role_label($role->name) }}</span>@if(!$loop->last)·@endif
                                    @endforeach
                                </p>
                                @if($u?->church)
                                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-2"><span class="font-medium">Igreja:</span> {{ $u->church->name }}</p>
                                @endif
                                <p class="text-sm mt-2">
                                    <span class="font-semibold text-slate-700 dark:text-slate-200">{{ \App\Models\ProfileSensitiveDataRequest::fieldLabel($req->field) }}</span>
                                    <span class="text-slate-500">de</span>
                                    <code class="text-xs bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded">{{ $req->field === 'cpf' ? format_cpf_pt($req->previous_value) : ($req->previous_value ?? '—') }}</code>
                                    <span class="text-slate-500">→</span>
                                    <code class="text-xs bg-indigo-50 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-200 px-2 py-0.5 rounded">{{ $req->field === 'cpf' ? format_cpf_pt($req->requested_value) : $req->requested_value }}</code>
                                </p>
                                @if($req->reason)
                                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 italic">“{{ $req->reason }}”</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-2">Pedido em {{ $req->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3 w-full lg:w-72 shrink-0">
                            <form method="POST" action="{{ route('diretoria.profile-data-requests.approve', $req) }}" class="space-y-2">
                                @csrf
                                <label class="text-xs font-semibold text-slate-500 uppercase">Nota (opcional)</label>
                                <input type="text" name="reviewer_note" maxlength="500" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm dark:text-white" placeholder="Visível no histórico">
                                <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                                    Aprovar e aplicar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('diretoria.profile-data-requests.reject', $req) }}" class="space-y-2">
                                @csrf
                                <input type="text" name="reviewer_note" maxlength="500" class="w-full px-3 py-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm dark:text-white" placeholder="Motivo da recusa (opcional)">
                                <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-semibold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-900/20 hover:bg-rose-100 dark:hover:bg-rose-900/40 border border-rose-200 dark:border-rose-900/30 transition-colors">
                                    Recusar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-slate-500 dark:text-slate-400 text-center py-8">Nenhum pedido pendente.</p>
            @endforelse
        </div>
    </div>

    @if($history->isNotEmpty())
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-slate-700">
            <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Histórico recente</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/50 text-left text-xs font-bold text-slate-500 uppercase">
                    <tr>
                        <th class="px-6 py-3">Utilizador</th>
                        <th class="px-6 py-3">Campo</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Data</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach($history as $h)
                        <tr class="text-slate-700 dark:text-slate-300">
                            <td class="px-6 py-3">{{ $h->user?->name }}</td>
                            <td class="px-6 py-3">{{ \App\Models\ProfileSensitiveDataRequest::fieldLabel($h->field) }}</td>
                            <td class="px-6 py-3">
                                @if($h->status === \App\Models\ProfileSensitiveDataRequest::STATUS_APPROVED)
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Aprovado</span>
                                @else
                                    <span class="text-rose-600 dark:text-rose-400 font-medium">Recusado</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-xs text-slate-500">{{ $h->reviewed_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
