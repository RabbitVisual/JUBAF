@extends($layout)

@section('title', 'Pedido #'.$req->id)

@section('content')
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('igrejas::paineldiretoria.partials.subnav', ['active' => 'requests'])

    <a href="{{ route($routePrefix.'.requests.index') }}" class="inline-flex items-center gap-1 text-sm font-semibold text-cyan-700 hover:underline dark:text-cyan-400">
        <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
        Voltar à lista
    </a>

    <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">Pedido #{{ $req->id }}</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $req->type }} · <span class="font-mono">{{ $req->status }}</span></p>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-200">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Payload</h2>
        <pre class="mt-3 max-h-96 overflow-auto rounded-xl bg-slate-50 p-4 text-xs dark:bg-slate-900">{{ json_encode($req->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>

    @if($req->review_notes)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-slate-700 dark:bg-slate-800">
            <h2 class="text-sm font-bold text-gray-900 dark:text-white">Notas da análise</h2>
            <p class="mt-2 whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300">{{ $req->review_notes }}</p>
        </div>
    @endif

    @if($canReview && $req->status === \Modules\Igrejas\App\Models\ChurchChangeRequest::STATUS_SUBMITTED)
        <div class="grid gap-6 md:grid-cols-2">
            <form method="post" action="{{ route($routePrefix.'.requests.approve', $req) }}" class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-6 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                @csrf
                <h3 class="font-bold text-emerald-900 dark:text-emerald-200">Aprovar</h3>
                <textarea name="review_notes" rows="3" class="mt-3 w-full rounded-xl border border-emerald-200 bg-white px-3 py-2 text-sm dark:border-emerald-900 dark:bg-slate-900 dark:text-white" placeholder="Notas (opcional)"></textarea>
                <button type="submit" class="mt-4 w-full rounded-xl bg-emerald-600 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Aprovar e aplicar</button>
            </form>
            <form method="post" action="{{ route($routePrefix.'.requests.reject', $req) }}" class="rounded-2xl border border-rose-200 bg-rose-50/40 p-6 dark:border-rose-900/50 dark:bg-rose-950/20">
                @csrf
                <h3 class="font-bold text-rose-900 dark:text-rose-200">Recusar</h3>
                <textarea name="review_notes" rows="3" class="mt-3 w-full rounded-xl border border-rose-200 bg-white px-3 py-2 text-sm dark:border-rose-900 dark:bg-slate-900 dark:text-white" placeholder="Motivo (opcional)"></textarea>
                <button type="submit" class="mt-4 w-full rounded-xl bg-rose-600 py-2.5 text-sm font-bold text-white hover:bg-rose-700">Recusar pedido</button>
            </form>
        </div>
    @endif
</div>
@endsection
