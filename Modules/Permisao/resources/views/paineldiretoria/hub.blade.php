@extends('layouts.app')

@section('title', 'Segurança e acesso')

@section('content')
<div class="mx-auto max-w-7xl space-y-8 pb-10 animate-fade-in font-sans">
    @include('permisao::paineldiretoria.partials.subnav', ['active' => 'hub'])

    <header class="flex flex-col gap-6 border-b border-gray-200 pb-8 dark:border-slate-700 lg:flex-row lg:items-start lg:justify-between">
        <div class="min-w-0 flex-1">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 dark:text-indigo-400">Painel diretoria</p>
            <div class="mt-2 flex flex-wrap items-start gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-violet-700 shadow-lg shadow-indigo-500/25 ring-1 ring-white/25 sm:h-16 sm:w-16">
                    <x-icon name="shield-halved" class="h-8 w-8 text-white sm:h-9 sm:w-9" style="duotone" />
                </div>
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl md:text-4xl">
                        Segurança e <span class="text-indigo-600 dark:text-indigo-400">acesso</span>
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        Configure <strong class="font-medium text-gray-800 dark:text-gray-200">quem</strong> usa o JUBAF,
                        <strong class="font-medium text-gray-800 dark:text-gray-200">com que perfil</strong> entra em cada área e
                        <strong class="font-medium text-gray-800 dark:text-gray-200">que ações</strong> pode realizar. Tudo nesta zona está ligado: cada passo prepara o seguinte.
                    </p>
                    <nav aria-label="breadcrumb" class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <a href="{{ route('diretoria.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Diretoria</a>
                        <x-icon name="chevron-right" class="h-3 w-3 shrink-0 text-slate-400" />
                        <span class="font-medium text-gray-900 dark:text-white">Segurança e acesso</span>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50/90 p-4 text-sm text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-100">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl border border-red-200/80 bg-red-50/90 p-4 text-sm text-red-900 dark:border-red-900/40 dark:bg-red-950/40 dark:text-red-100">{{ session('error') }}</div>
    @endif

    <section class="rounded-3xl border border-gray-200/90 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800/80 md:p-8" aria-labelledby="how-it-works-title">
        <h2 id="how-it-works-title" class="text-lg font-bold text-gray-900 dark:text-white">Como as permissões funcionam (em linguagem simples)</h2>
        <p class="mt-2 max-w-3xl text-sm text-gray-600 dark:text-gray-400">
            Imagine três camadas empilhadas. A de baixo são as <strong class="text-gray-800 dark:text-gray-200">permissões</strong> (regras técnicas).
            No meio, as <strong class="text-gray-800 dark:text-gray-200">funções</strong> escolhem quais regras um perfil leva.
            No topo, cada <strong class="text-gray-800 dark:text-gray-200">utilizador</strong> recebe uma ou mais funções — e passa a poder fazer só o que essas funções permitem.
        </p>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <div class="relative rounded-2xl border border-indigo-100 bg-indigo-50/50 p-5 dark:border-indigo-900/40 dark:bg-indigo-950/20">
                <span class="absolute -top-2.5 left-4 inline-flex rounded-full bg-indigo-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Passo 1</span>
                <div class="mt-2 flex items-center gap-2 text-indigo-800 dark:text-indigo-200">
                    <x-icon name="users" class="h-5 w-5" style="duotone" />
                    <span class="font-semibold">Utilizadores</span>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-gray-700 dark:text-gray-300">Crie contas, defina estado ativo e associe <strong class="font-medium">funções</strong> a cada pessoa. Sem função adequada, a pessoa não vê áreas sensíveis.</p>
            </div>
            <div class="relative rounded-2xl border border-violet-100 bg-violet-50/50 p-5 dark:border-violet-900/40 dark:bg-violet-950/20">
                <span class="absolute -top-2.5 left-4 inline-flex rounded-full bg-violet-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Passo 2</span>
                <div class="mt-2 flex items-center gap-2 text-violet-800 dark:text-violet-200">
                    <x-icon name="user-shield" class="h-5 w-5" style="duotone" />
                    <span class="font-semibold">Funções (perfis)</span>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-gray-700 dark:text-gray-300">Cada função é um “pacote” de acesso: diretoria, super administração, operacional ou personalizado. Ao editar uma função, escolhe-se quais <strong class="font-medium">permissões</strong> ela inclui.</p>
            </div>
            <div class="relative rounded-2xl border border-slate-200 bg-slate-50/80 p-5 dark:border-slate-600 dark:bg-slate-900/40">
                <span class="absolute -top-2.5 left-4 inline-flex rounded-full bg-slate-700 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white dark:bg-slate-600">Passo 3</span>
                <div class="mt-2 flex items-center gap-2 text-slate-800 dark:text-slate-200">
                    <x-icon name="key" class="h-5 w-5" style="duotone" />
                    <span class="font-semibold">Permissões</span>
                </div>
                <p class="mt-2 text-sm leading-relaxed text-gray-700 dark:text-gray-300">Lista de ações por módulo (formato <code class="rounded bg-white/80 px-1 font-mono text-xs dark:bg-slate-800">modulo.acao</code>). São a base: as funções apenas combinam estas peças.</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="profiles-title">
        <h2 id="profiles-title" class="mb-4 text-sm font-bold uppercase tracking-[0.12em] text-gray-500 dark:text-gray-400">O que costuma diferir entre perfis</h2>
        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-indigo-100 bg-white p-4 dark:border-indigo-900/30 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">Super administração</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Acesso ao painel <code class="rounded bg-slate-100 px-1 text-xs dark:bg-slate-900">/admin</code> — utilizadores globais, módulos, backups e auditoria. Perfil mais elevado.</p>
            </div>
            <div class="rounded-2xl border border-violet-100 bg-white p-4 dark:border-violet-900/30 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase tracking-wide text-violet-600 dark:text-violet-400">Diretoria executiva</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Este painel <code class="rounded bg-slate-100 px-1 text-xs dark:bg-slate-900">/diretoria</code> — homepage, carousel, equipa, e esta área de segurança (quem tem permissão).</p>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-4 dark:border-emerald-900/30 dark:bg-slate-800">
                <p class="text-xs font-bold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">Operacional</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Líderes de igreja local, Unijovem, etc. — acessos focados no dia a dia, sem ferramentas de administração global.</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="quick-links-title">
        <h2 id="quick-links-title" class="sr-only">Atalhos para gestão</h2>
        <div class="grid gap-4 md:grid-cols-3">
            <a href="{{ route('diretoria.users.index') }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-indigo-500">
                <span class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300">
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" style="solid" />
                </span>
                <span class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 text-white shadow-md">
                    <x-icon name="users" class="h-6 w-6" style="duotone" />
                </span>
                <span class="text-xs font-bold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">1 · Utilizadores</span>
                <span class="mt-1 text-lg font-bold text-gray-900 dark:text-white">Gerir contas</span>
                <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">Criar utilizadores, ativar ou desativar e atribuir funções.</span>
            </a>
            <a href="{{ route('diretoria.roles.index') }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm transition hover:border-violet-300 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-violet-500">
                <span class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-300">
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" style="solid" />
                </span>
                <span class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 text-white shadow-md">
                    <x-icon name="user-shield" class="h-6 w-6" style="duotone" />
                </span>
                <span class="text-xs font-bold uppercase tracking-wide text-violet-600 dark:text-violet-400">2 · Funções</span>
                <span class="mt-1 text-lg font-bold text-gray-900 dark:text-white">Perfis e pacotes de acesso</span>
                <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">Ver e editar que permissões cada função inclui (RBAC).</span>
            </a>
            <a href="{{ route('diretoria.permissions.index') }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200/90 bg-white p-6 shadow-sm transition hover:border-slate-400 hover:shadow-md dark:border-slate-700 dark:bg-slate-800 dark:hover:border-indigo-500">
                <span class="absolute right-4 top-4 flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                    <x-icon name="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-0.5" style="solid" />
                </span>
                <span class="mb-3 inline-flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-slate-600 to-slate-800 text-white shadow-md dark:from-slate-500 dark:to-slate-700">
                    <x-icon name="key" class="h-6 w-6" style="duotone" />
                </span>
                <span class="text-xs font-bold uppercase tracking-wide text-slate-600 dark:text-slate-400">3 · Permissões</span>
                <span class="mt-1 text-lg font-bold text-gray-900 dark:text-white">Regras por módulo</span>
                <span class="mt-2 text-sm text-gray-600 dark:text-gray-400">Consultar ações do sistema e criar permissões customizadas quando necessário.</span>
            </a>
        </div>
    </section>
</div>
@endsection
