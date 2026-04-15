@extends('layouts.app')

@section('title', 'Planos de Leitura')

@section('content')
    <x-bible::admin.layout
        title="Planos de leitura"
        subtitle="Cronogramas devocionais e leitura guiada para a comunidade.">
        <x-slot name="actions">
            <a href="{{ bible_admin_route('plans.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-amber-50 bg-linear-to-r from-amber-800 to-stone-900 hover:from-amber-900 hover:to-stone-950 rounded-xl shadow-md transition-all">
                <x-icon name="plus" class="w-5 h-5 mr-2" />
                Novo plano
            </a>
        </x-slot>

        @if($plans->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-amber-200/70 dark:border-amber-900/45 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duração</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($plans as $plan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $plan->title }}</span>
                                    <span class="text-xs text-gray-500">{{ $plan->days_count }} dias gerados</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ ucfirst($plan->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-1">
                                    <x-icon name="clock" style="duotone" class="w-4 h-4" />
                                    {{ $plan->duration_days }} dias
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 inline-flex text-xs font-semibold rounded-full {{ $plan->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                    {{ $plan->is_active ? 'Publicado' : 'Rascunho' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    @if($plan->days_count == 0 && $plan->type == 'sequential')
                                        <a href="{{ bible_admin_route('plans.generate', $plan->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center" title="Gerar Cronograma">
                                            <x-icon name="flask" class="w-4 h-4 mr-1" />
                                            Gerar
                                        </a>
                                    @endif

                                    <a href="{{ bible_admin_route('plans.show', $plan->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                                        <x-icon name="eye" style="duotone" class="w-4 h-4 mr-1" />
                                        Gerenciar
                                    </a>

                                    <form action="{{ bible_admin_route('plans.destroy', $plan->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 flex items-center" onclick="return confirm('Excluir este plano?')">
                                            <x-icon name="trash-can" style="duotone" class="w-4 h-4 mr-1" />
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $plans->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="flex flex-col items-center justify-center py-16 px-4 bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-amber-300/70 dark:border-amber-800/60">
                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <x-icon name="clipboard-list" class="w-10 h-10 text-gray-400 dark:text-gray-500" />
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Nenhum plano de leitura</h3>
                <p class="text-gray-600 dark:text-gray-400 text-center max-w-md mb-6">Crie planos devocionais para guiar os membros em seus estudos bíblicos.</p>
                <a href="{{ bible_admin_route('plans.create') }}" class="inline-flex items-center px-6 py-3 text-sm font-semibold text-amber-50 bg-linear-to-r from-amber-800 to-stone-900 hover:from-amber-900 hover:to-stone-950 rounded-xl shadow-md transition-all duration-200">
                    <x-icon name="plus" class="w-5 h-5 mr-2" />
                    Criar Primeiro Plano
                </a>
            </div>
        @endif
    </x-bible::admin.layout>
@endsection

