@extends('paineljovens::layouts.jovens')

@section('title', 'Notificação')


@section('jovens_content')
    <x-ui.jovens::page-shell class="max-w-3xl space-y-6">
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-8 dark:border-gray-700 dark:bg-gray-900/50 md:px-10 md:py-10">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-md">
                        <x-icon name="{{ ($notification->data['icon'] ?? null) ?: 'bell' }}" class="h-7 w-7" />
                    </div>
                    <div class="min-w-0">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-blue-700 dark:text-blue-400">Aviso JUBAF</p>
                        <h1 class="text-xl font-bold leading-tight text-gray-900 dark:text-white md:text-2xl">
                            {{ $notification->title ?? ($notification->data['title'] ?? 'Notificação') }}
                        </h1>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            {{ $notification->created_at->translatedFormat('d \d\e F \d\e Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="max-w-none px-6 py-8 prose prose-gray md:px-10 md:py-10 dark:prose-invert">
                <p class="whitespace-pre-wrap leading-relaxed text-gray-700 dark:text-gray-300">{{ $notification->message ?? ($notification->data['message'] ?? 'Sem conteúdo adicional.') }}</p>
                @if (! empty($notification->action_url))
                    <p class="mt-8">
                        <a href="{{ $notification->action_url }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-blue-700">
                            <x-icon name="arrow-up-right-from-square" class="h-4 w-4" />
                            Abrir link
                        </a>
                    </p>
                @endif
            </div>
        </div>

        <a href="{{ route('jovens.notificacoes.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">
            <x-icon name="arrow-left" class="h-4 w-4" />
            Voltar à lista
        </a>
    </x-ui.jovens::page-shell>
@endsection
