@extends('painellider::components.layouts.app')

@section('title', 'Notificações')

@section('content')
<div class="space-y-6 md:space-y-8 animate-fade-in pb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 md:pb-6 border-b border-slate-200 dark:border-slate-800">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3 mb-2">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <x-icon name="bell" class="w-5 h-5 md:w-6 md:h-6 text-white" />
                </div>
                Notificações
            </h1>
            <p class="text-sm md:text-base text-slate-600 dark:text-slate-400">
                Avisos e atualizações da JUBAF para a tua conta no painel de líderes.
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                <thead class="bg-slate-50 dark:bg-slate-900/80">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Título</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Mensagem</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Data</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($notifications as $notification)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors {{ !$notification->is_read ? 'bg-emerald-50/80 dark:bg-emerald-950/20' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full {{ $notification->is_read ? 'bg-slate-100 dark:bg-slate-800 text-slate-500' : 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300' }}">
                                    <x-icon name="{{ ($notification->data['icon'] ?? null) ?: 'bell' }}" class="w-4 h-4" />
                                </span>
                                <span class="text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $notification->title ?? ($notification->data['title'] ?? 'Notificação') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400 hidden md:table-cell">
                            {{ Str::limit($notification->message ?? ($notification->data['message'] ?? ''), 80) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                            {{ $notification->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('lideres.notificacoes.show', $notification->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-950/40 transition-colors" aria-label="Ver">
                                <x-icon name="eye" class="w-5 h-5" />
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                <x-icon name="bell-slash" class="w-8 h-8 md:w-10 md:h-10 text-slate-400" />
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
        <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-t border-slate-200 dark:border-slate-800">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
