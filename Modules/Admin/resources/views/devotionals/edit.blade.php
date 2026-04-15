@extends($layout)

@section('title', 'Editar devocional')

@section('content')
<div class="space-y-6 pb-12 max-w-6xl">
    <div class="pb-4 border-b border-gray-200 dark:border-slate-700">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar devocional</h1>
        <a href="{{ route($routePrefix.'.index') }}" class="text-sm text-amber-700 hover:underline mt-2 inline-block">← Voltar</a>
    </div>

    <form action="{{ route($routePrefix.'.update', $devotional) }}" method="post" enctype="multipart/form-data" class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-6 md:p-8 space-y-8">
        @csrf
        @method('PUT')
        @include('admin::devotionals._form', ['devotional' => $devotional, 'users' => $users, 'boardMembers' => $boardMembers, 'routePrefix' => $routePrefix])

        <div class="flex gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 text-white font-semibold hover:bg-amber-700">Atualizar</button>
            <a href="{{ route($routePrefix.'.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 dark:border-slate-600 font-medium">Cancelar</a>
        </div>
    </form>
</div>
@endsection
