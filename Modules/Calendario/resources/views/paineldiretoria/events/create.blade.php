@extends($layout)

@section('title', 'Novo evento')

@section('content')
<div class="mx-auto max-w-4xl space-y-8 pb-10">
    @include('calendario::paineldiretoria.partials.subnav', ['active' => 'events'])

    <div>
        <a href="{{ route('diretoria.calendario.events.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:gap-2 dark:text-emerald-400">
            <x-icon name="arrow-left" class="h-3.5 w-3.5" style="duotone" />
            Voltar aos eventos
        </a>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl">Novo evento</h1>
        <p class="mt-2 max-w-2xl text-sm text-gray-600 dark:text-gray-400">
            Assistente em <strong>4 passos</strong> — sem JSON obrigatório. Use <em>Anterior</em> / <em>Seguinte</em> entre passos e <strong>Guardar</strong> no fim.
        </p>
    </div>

    <form action="{{ route('diretoria.calendario.events.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <div class="overflow-hidden rounded-2xl border border-gray-200/90 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Dados do evento</h2>
                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">Passo 1: o quê e quando · Passo 2: inscrições e valores · Passo 3: imagens e tema · Passo 4: programa e contactos</p>
            </div>
            <div class="p-6">
                @include('calendario::paineldiretoria.events._form', ['event' => $event, 'churches' => $churches, 'discountRule' => null])
            </div>
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 bg-gray-50/80 px-6 py-4 dark:border-slate-700 dark:bg-slate-900/40">
                <a href="{{ route('diretoria.calendario.events.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200/80 dark:text-gray-300 dark:hover:bg-slate-700">Cancelar</a>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-md shadow-emerald-600/20 transition hover:bg-emerald-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900">
                    <x-icon name="check" class="h-4 w-4" style="solid" />
                    Guardar evento
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
