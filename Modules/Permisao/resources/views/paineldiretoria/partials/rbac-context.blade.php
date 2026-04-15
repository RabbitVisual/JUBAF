{{--
    Faixa contextual: onde estou no fluxo Utilizadores → Funções → Permissões.
    @var string $step users|roles|permissions
--}}
@php
    $step = $step ?? 'users';
    $steps = [
        [
            'key' => 'users',
            'label' => 'Utilizadores',
            'hint' => 'Contas e quem pode entrar',
            'route' => 'diretoria.users.index',
            'icon' => 'users',
        ],
        [
            'key' => 'roles',
            'label' => 'Funções',
            'hint' => 'Perfis que agrupam permissões',
            'route' => 'diretoria.roles.index',
            'icon' => 'user-shield',
        ],
        [
            'key' => 'permissions',
            'label' => 'Permissões',
            'hint' => 'Ações por módulo (o “o quê”)',
            'route' => 'diretoria.permissions.index',
            'icon' => 'key',
        ],
    ];
@endphp
<section class="rounded-2xl border border-indigo-100/90 bg-gradient-to-br from-indigo-50/80 via-white to-slate-50/90 dark:border-indigo-900/35 dark:from-indigo-950/25 dark:via-slate-900/50 dark:to-slate-900/80 px-3 py-3 sm:px-4 sm:py-4 shadow-sm" aria-labelledby="rbac-flow-heading">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
        <div class="min-w-0">
            <h2 id="rbac-flow-heading" class="text-xs font-bold uppercase tracking-[0.14em] text-indigo-700 dark:text-indigo-400">Fluxo de acesso</h2>
            <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                <strong class="font-semibold text-gray-900 dark:text-white">Utilizador</strong> recebe uma ou mais
                <strong class="font-semibold text-gray-900 dark:text-white">funções</strong>; cada função inclui
                <strong class="font-semibold text-gray-900 dark:text-white">permissões</strong> (ações no sistema). Siga a ordem abaixo para configurar com segurança.
            </p>
        </div>
        <a href="{{ route('diretoria.seguranca.hub') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border border-indigo-200/80 bg-white px-3 py-2 text-xs font-semibold text-indigo-800 shadow-sm transition hover:border-indigo-300 hover:bg-indigo-50 dark:border-indigo-800/60 dark:bg-slate-800 dark:text-indigo-200 dark:hover:bg-indigo-950/40">
            <x-icon name="shield-halved" class="h-4 w-4" style="duotone" />
            Abrir hub de segurança
        </a>
    </div>
    <ol class="mt-4 grid gap-2 sm:grid-cols-3" role="list">
        @foreach($steps as $i => $s)
            @php $isCurrent = $step === $s['key']; @endphp
            <li class="relative">
                <a href="{{ route($s['route']) }}"
                    class="flex h-full flex-col gap-1 rounded-xl border px-3 py-3 text-left transition sm:min-h-[5.5rem] {{ $isCurrent
                        ? 'border-indigo-400 bg-indigo-600 text-white shadow-md shadow-indigo-600/20 ring-1 ring-indigo-500/30 dark:border-indigo-500 dark:bg-indigo-600'
                        : 'border-gray-200/90 bg-white/80 hover:border-indigo-300 hover:bg-white dark:border-slate-600 dark:bg-slate-800/80 dark:hover:border-indigo-600' }}"
                    @if($isCurrent) aria-current="page" @endif>
                    <span class="flex items-center gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold {{ $isCurrent ? 'bg-white/20 text-white' : 'bg-indigo-100 text-indigo-800 dark:bg-indigo-950/50 dark:text-indigo-200' }}">{{ $i + 1 }}</span>
                        <x-icon :name="$s['icon']" class="h-4 w-4 shrink-0 {{ $isCurrent ? 'text-white opacity-95' : 'text-indigo-600 dark:text-indigo-400' }}" style="duotone" />
                        <span class="text-sm font-bold {{ $isCurrent ? 'text-white' : 'text-gray-900 dark:text-white' }}">{{ $s['label'] }}</span>
                    </span>
                    <span class="pl-9 text-xs leading-snug {{ $isCurrent ? 'text-indigo-100' : 'text-gray-600 dark:text-gray-400' }}">{{ $s['hint'] }}</span>
                </a>
            </li>
        @endforeach
    </ol>
</section>
