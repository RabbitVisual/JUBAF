@extends('paineljovens::layouts.jovens')

@section('title', 'Notificações')


@section('jovens_content')
    <x-ui.jovens::page-shell class="space-y-6 md:space-y-8">
        <x-ui.jovens::hero
            title="Notificações"
            description="Atualizações e avisos enviados pela JUBAF para a tua conta."
            eyebrow="Centro de avisos" />

        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/80">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Título</th>
                            <th scope="col" class="hidden px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 md:table-cell">Mensagem</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Data</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($notifications as $notification)
                            <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-900/50 {{ ($notification->is_read || $notification->read_at) ? '' : 'bg-blue-50/80 dark:bg-blue-950/20' }}">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ ($notification->is_read || $notification->read_at) ? 'bg-gray-100 text-gray-500 dark:bg-gray-800' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300' }}">
                                            <x-icon name="{{ ($notification->data['icon'] ?? null) ?: 'bell' }}" class="h-4 w-4" />
                                        </span>
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $notification->title ?? ($notification->data['title'] ?? 'Notificação') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="hidden px-6 py-4 text-sm text-gray-600 dark:text-gray-400 md:table-cell">
                                    {{ Str::limit($notification->message ?? ($notification->data['message'] ?? ''), 80) }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $notification->created_at->diffForHumans() }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('jovens.notificacoes.show', $notification->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-blue-600 transition-colors hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/40" aria-label="Ver">
                                        <x-icon name="eye" class="h-5 w-5" />
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 md:h-20 md:w-20">
                                        <x-icon name="bell-slash" class="h-8 w-8 text-gray-400 md:h-10 md:w-10" />
                                    </div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Nenhuma notificação</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quando houver avisos novos, aparecem aqui.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($notifications, 'links'))
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900/50">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </x-ui.jovens::page-shell>
@endsection
