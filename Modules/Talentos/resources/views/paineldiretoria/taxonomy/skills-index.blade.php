@extends($layout)

@section('title', 'Competências — talentos')

@section('content')
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('talentos::paineldiretoria.partials.subnav', ['active' => 'taxonomy'])

    <div class="flex flex-col gap-4 border-b border-gray-200 pb-6 dark:border-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div class="min-w-0">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-800 dark:text-violet-400">Taxonomia · Talentos</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Competências</h1>
            <p class="mt-2 max-w-xl text-sm text-gray-600 dark:text-gray-400">Lista usada nas fichas de inscrição e nos filtros do diretório.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('diretoria.talentos.areas-servico.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-800 shadow-sm transition hover:border-violet-200 dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                Áreas de serviço
            </a>
            @can('create', \Modules\Talentos\App\Models\TalentSkill::class)
                <a href="{{ route('diretoria.talentos.competencias.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-violet-600/25 transition hover:bg-violet-700">
                    <x-icon name="plus" class="h-4 w-4" style="solid" />
                    Nova competência
                </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200">{{ session('error') }}</div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500 dark:bg-slate-900/80 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-3.5">Nome</th>
                    <th class="px-4 py-3.5"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                @forelse($skills as $s)
                    <tr class="hover:bg-violet-50/40 dark:hover:bg-slate-900/50">
                        <td class="px-4 py-3.5 font-medium text-gray-900 dark:text-white">{{ $s->name }}</td>
                        <td class="px-4 py-3.5 text-right">
                            @can('update', $s)
                                <a href="{{ route('diretoria.talentos.competencias.edit', $s) }}" class="text-sm font-bold text-violet-700 hover:underline dark:text-violet-400">Editar</a>
                            @endcan
                            @can('delete', $s)
                                <form action="{{ route('diretoria.talentos.competencias.destroy', $s) }}" method="post" class="inline" onsubmit="return confirm('Remover esta competência?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-sm font-bold text-red-700 hover:underline dark:text-red-400">Remover</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">Nenhuma competência. Crie a primeira ou execute o seeder do módulo.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
