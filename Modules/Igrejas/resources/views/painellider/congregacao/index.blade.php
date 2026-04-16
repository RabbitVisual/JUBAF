@extends('painellider::layouts.lideres')

@section('title', 'A minha congregação')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <span class="text-emerald-700 dark:text-emerald-300">Congregação</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="max-w-6xl space-y-8">
    <x-ui.lideres::hero
        variant="gradient"
        eyebrow="JUBAF · Módulo Igrejas"
        title="A tua congregação na associação"
        description="Dados oficiais da igreja local vinculada à JUBAF/ASBAF, contactos institucionais e lista de jovens Unijovem da mesma congregação.">
        <x-slot name="actions">
            <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row sm:flex-wrap sm:items-center sm:justify-end">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/15">
                    <x-module-icon module="Igrejas" class="h-7 w-7 text-white" />
                </span>
                @can('create', \Modules\Igrejas\App\Models\ChurchChangeRequest::class)
                    @if(Route::has('lideres.igrejas.requests.index'))
                        <a href="{{ route('lideres.igrejas.requests.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/15 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/25">
                            <x-icon name="inbox" class="h-4 w-4" />
                            Pedidos à diretoria
                        </a>
                    @endif
                @endcan
                @if($church && Route::has('igrejas.public.index'))
                    <a href="{{ route('igrejas.public.index') }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/20 bg-white/15 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/25">
                        <x-icon name="globe" class="h-4 w-4" />
                        Igrejas públicas JUBAF
                    </a>
                @endif
                @can('igrejasProvisionYouth')
                    @if(Route::has('lideres.congregacao.jovens.create'))
                        <a href="{{ route('lideres.congregacao.jovens.create') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-bold text-emerald-800 shadow-md shadow-black/10 transition hover:bg-emerald-50">
                            <x-icon name="user-plus" class="h-4 w-4" />
                            Cadastrar jovem
                        </a>
                    @endif
                @endcan
            </div>
        </x-slot>
    </x-ui.lideres::hero>

    @if($church)
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 md:p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Jovens</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->jovens_members_count }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 md:p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Líderes</p>
                <p class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white mt-1 tabular-nums">{{ $church->leaders_count }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 md:p-5 shadow-sm col-span-2 md:col-span-1">
                <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Estado</p>
                <p class="mt-2">
                    @if($church->is_active)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-200">Ativa na JUBAF</span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-900 dark:bg-amber-900/40 dark:text-amber-200">Inativa — contactar secretaria</span>
                    @endif
                </p>
            </div>
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 md:p-5 shadow-sm col-span-2 md:col-span-1">
                <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">Filiação</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-2">{{ $church->joined_at ? $church->joined_at->format('d/m/Y') : '—' }}</p>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-900/50">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $church->name }}</h2>
                @if($church->city)<p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $church->city }}</p>@endif
            </div>
            <div class="p-6 md:p-8 grid md:grid-cols-2 gap-8 text-sm">
                <dl class="space-y-4">
                    @if($church->address)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Endereço</dt>
                            <dd class="mt-1 text-slate-800 dark:text-slate-100 leading-relaxed">{{ $church->address }}</dd>
                        </div>
                    @endif
                    @if($church->phone)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Telefone</dt>
                            <dd class="mt-1"><a href="tel:{{ preg_replace('/\s+/', '', $church->phone) }}" class="font-semibold text-emerald-700 dark:text-emerald-400 hover:underline">{{ $church->phone }}</a></dd>
                        </div>
                    @endif
                    @if($church->email)
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">E-mail</dt>
                            <dd class="mt-1"><a href="mailto:{{ $church->email }}" class="font-semibold text-emerald-700 dark:text-emerald-400 hover:underline break-all">{{ $church->email }}</a></dd>
                        </div>
                    @endif
                </dl>
                @if($church->asbaf_notes)
                    <div class="rounded-2xl bg-slate-50 dark:bg-slate-900/60 border border-slate-100 dark:border-slate-700 p-5">
                        <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400 mb-2">Notas institucionais</p>
                        <p class="text-slate-700 dark:text-slate-300 whitespace-pre-wrap leading-relaxed">{{ $church->asbaf_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($coLeaders->isNotEmpty())
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3">Outros líderes na mesma congregação</h3>
                <ul class="flex flex-wrap gap-2">
                    @foreach($coLeaders as $l)
                        <li class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-sm">
                            <x-icon name="user" class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                            <span class="font-medium text-slate-800 dark:text-slate-100">{{ $l->name }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @else
        <div class="rounded-2xl border-2 border-dashed border-amber-300 dark:border-amber-800 bg-amber-50/90 dark:bg-amber-950/30 p-8 text-center">
            <x-icon name="triangle-exclamation" class="w-10 h-10 text-amber-600 dark:text-amber-400 mx-auto mb-3" />
            <h2 class="text-lg font-bold text-amber-900 dark:text-amber-100">Sem congregação associada</h2>
            <p class="text-sm text-amber-900/85 dark:text-amber-100/80 mt-2 max-w-md mx-auto">
                O teu utilizador ainda não tem <code class="px-1 rounded bg-amber-200/50 dark:bg-amber-900/50">church_id</code> definido. Pedido à secretaria JUBAF para vincular a tua conta à igreja local.
            </p>
        </div>
    @endif

    <div>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Jovens da congregação</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">A diretoria associa o teu utilizador à igreja; aqui crias as contas <strong>Jovem JUBAF</strong> desta congregação e envias o convite (e-mail para definir palavra-passe). Trata os dados em conformidade com o RGPD.</p>
            </div>
            @can('igrejasProvisionYouth')
                <div class="flex flex-col gap-2 sm:flex-row sm:flex-wrap sm:justify-end">
                    @if(Route::has('lideres.congregacao.jovens.create'))
                        <a href="{{ route('lideres.congregacao.jovens.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow hover:bg-emerald-700 shrink-0">
                            <x-icon name="user-plus" class="h-4 w-4" />
                            Adicionar jovem
                        </a>
                    @endif
                    @if(Route::has('lideres.congregacao.jovens.export'))
                        <a href="{{ route('lideres.congregacao.jovens.export') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-800 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700 shrink-0">
                            <x-icon name="download" class="h-4 w-4" style="duotone" />
                            Exportar CSV
                        </a>
                    @endif
                </div>
            @else
                @if(auth()->user()?->hasRole('lider') && ! auth()->user()?->church_id)
                    <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-800 dark:bg-amber-950/40 dark:text-amber-100">
                        Sem <strong>igreja associada</strong> ao teu perfil. Pede à diretoria/secretaria JUBAF para vincular a tua conta à congregação — depois poderás cadastrar jovens aqui.
                    </p>
                @endif
            @endcan
        </div>
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 dark:bg-slate-900/80 text-left text-xs font-bold uppercase text-slate-500 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Acesso</th>
                        <th class="px-4 py-3">Conta</th>
                        <th class="px-4 py-3 hidden lg:table-cell">Censo</th>
                        <th class="px-4 py-3 text-right">Acções</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($jovens as $j)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-900/40">
                            <td class="px-4 py-3 font-semibold text-slate-900 dark:text-white">{{ $j->name }}</td>
                            <td class="px-4 py-3 text-slate-600 dark:text-slate-400">{{ $j->email }}</td>
                            <td class="px-4 py-3">
                                @if($j->provisioned_at)
                                    @if($j->hasPendingInvitedAccess())
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-900 dark:bg-amber-900/40 dark:text-amber-100" title="Aguarda definição da palavra-passe (convite por e-mail)">Convite pendente</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-900 dark:bg-emerald-900/40 dark:text-emerald-100">Acesso definido</span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400" title="Conta não criada pelo fluxo de provisão do painel">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($j->active ?? true)
                                    <span class="text-emerald-600 dark:text-emerald-400 font-medium">Ativo</span>
                                @else
                                    <span class="text-slate-500">Inativo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell text-slate-600 dark:text-slate-400 text-xs">
                                @if($j->provisioned_at)
                                    <span class="block">{{ $j->provisioned_at->format('d/m/Y H:i') }}</span>
                                    @if($j->provisionedBy)
                                        <span class="text-slate-500 dark:text-slate-500">por {{ $j->provisionedBy->name }}</span>
                                    @endif
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @can('igrejasManageChurchYouth', $j)
                                    <div class="inline-flex flex-wrap items-center justify-end gap-2">
                                        <a href="{{ route('lideres.congregacao.jovens.edit', $j) }}" class="text-sm font-semibold text-emerald-700 hover:underline dark:text-emerald-400">Editar</a>
                                        <form method="post" action="{{ route('lideres.congregacao.jovens.send-password-reset', $j) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-sm font-semibold text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">Link palavra-passe</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">—</span>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <p class="text-slate-600 dark:text-slate-400 mb-4">Ainda não há jovens com o papel <strong>Jovem JUBAF</strong> nesta igreja na plataforma.</p>
                                @can('igrejasProvisionYouth')
                                    @if(Route::has('lideres.congregacao.jovens.create'))
                                        <a href="{{ route('lideres.congregacao.jovens.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-emerald-700">
                                            <x-icon name="user-plus" class="h-4 w-4" />
                                            Cadastrar primeiro jovem
                                        </a>
                                    @endif
                                @else
                                    <p class="text-sm text-amber-800 dark:text-amber-200">Associa primeiro a tua conta a uma congregação para poder cadastrar jovens.</p>
                                @endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $jovens->links() }}</div>
    </div>
</x-ui.lideres::page-shell>
@endsection
