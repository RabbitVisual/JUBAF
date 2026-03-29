@extends('admin::components.layouts.master')

@section('title', $assembly->title)

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-200 px-4 py-3 text-sm">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $assembly->title }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $assembly->scheduled_at?->format('d/m/Y H:i') }} · {{ $assembly->type === 'extraordinaria' ? 'Extraordinária' : 'Ordinária' }}</p>
                @if($assembly->location)
                    <p class="text-sm text-gray-500 mt-1">{{ $assembly->location }}</p>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                @if(auth()->user()->canAccess('governance_manage'))
                    <a href="{{ route('admin.governance.assemblies.edit', $assembly) }}" class="px-3 py-2 rounded-xl border border-gray-300 dark:border-slate-600 text-sm font-medium">Editar</a>
                    <a href="{{ route('admin.governance.assemblies.minute.edit', $assembly) }}" class="px-3 py-2 rounded-xl bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">Ata</a>
                    @if($assembly->minute && $assembly->minute->status !== 'published')
                        <form method="post" action="{{ route('admin.governance.assemblies.minute.publish', $assembly) }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700">Publicar ata</button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        @if($assembly->convocation_notes)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-2">Convocação</h2>
                <div class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-wrap">{{ $assembly->convocation_notes }}</div>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6">
            <h2 class="font-semibold text-gray-900 dark:text-white mb-3">Pauta</h2>
            @if($assembly->agendaItems->isEmpty())
                <p class="text-gray-500 text-sm">Sem itens. Edite a assembleia para adicionar.</p>
            @else
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    @foreach($assembly->agendaItems as $item)
                        <li>
                            <span class="font-medium">{{ $item->title }}</span>
                            @if($item->description)
                                <p class="ml-6 text-gray-500">{{ $item->description }}</p>
                            @endif
                        </li>
                    @endforeach
                </ol>
            @endif
        </div>

        @if($assembly->minute)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 p-6">
                <h2 class="font-semibold text-gray-900 dark:text-white mb-2">Estado da ata</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Estado: <strong>{{ $assembly->minute->status }}</strong>
                    @if($assembly->minute->published_at)
                        · Publicada em {{ $assembly->minute->published_at->format('d/m/Y H:i') }}
                    @endif
                </p>
                @if($assembly->minute->isPublished())
                    <a href="{{ route('public.transparency.minute', $assembly->minute) }}" target="_blank" class="inline-block mt-2 text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver página pública</a>
                @endif
            </div>
        @endif

        @if(auth()->user()->canAccess('governance_manage'))
            <form method="post" action="{{ route('admin.governance.assemblies.destroy', $assembly) }}" onsubmit="return confirm('Remover esta assembleia?');" class="pt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:underline">Remover assembleia</button>
            </form>
        @endif
    </div>
@endsection
