@extends('painellider::layouts.lideres')

@section('title', 'Notificações')

@section('lideres_content')
<x-ui.lideres::page-shell class="animate-fade-in space-y-6 pb-8 md:space-y-8">
    <x-ui.lideres::hero
        variant="gradient"
        eyebrow="Painel de líderes"
        title="Notificações"
        description="Avisos e atualizações da JUBAF para a tua conta no painel de líderes.">
        <x-slot name="actions">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/15 shadow-lg">
                <x-icon name="bell" class="h-6 w-6 text-white" />
            </span>
        </x-slot>
    </x-ui.lideres::hero>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/80">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Título</th>
                        <th scope="col" class="hidden px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 md:table-cell">Mensagem</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Data</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($notifications as $notification)
                    <tr class="transition-colors hover:bg-slate-50 dark:hover:bg-slate-800/50 {{ !$notification->is_read ? 'bg-emerald-50/80 dark:bg-emerald-950/20' : '' }}">
                        <td class="whitespace-nowrap px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full {{ $notification->is_read ? 'bg-slate-100 text-slate-500 dark:bg-slate-800' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300' }}">
                                    <x-icon name="{{ ($notification->data['icon'] ?? null) ?: 'bell' }}" class="h-4 w-4" />
                                </span>
                                <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $notification->title ?? ($notification->data['title'] ?? 'Notificação') }}
                                </span>
                            </div>
                        </td>
                        <td class="hidden px-6 py-4 text-sm text-slate-600 dark:text-slate-400 md:table-cell">
                            {{ Str::limit($notification->message ?? ($notification->data['message'] ?? ''), 80) }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </td>
                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('lideres.notificacoes.show', $notification->id) }}" class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-emerald-600 transition-colors hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/40" aria-label="Ver">
                                <x-icon name="eye" class="h-5 w-5" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:h-20 dark:w-20 dark:bg-slate-800 md:h-20 md:w-20">
                                <x-icon name="bell-slash" class="h-8 w-8 text-slate-400 md:h-10 md:w-10" />
                            </div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Nenhuma notificação</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Quando houver avisos novos, aparecem aqui.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($notifications, 'links'))
        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4 dark:border-slate-800 dark:bg-slate-900/50">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</x-ui.lideres::page-shell>
@endsection
