@extends('layouts.app')

@section('title', 'Nova notificação')

@section('content')
    @php
        $oldRecipient = old('recipient_type', 'all');
    @endphp
    <div class="mx-auto max-w-7xl space-y-6 pb-12 font-sans md:space-y-8 animate-fade-in">
        @include('notificacoes::paineldiretoria.partials.subnav', ['active' => 'nova'])

        <div
            class="relative overflow-hidden rounded-3xl border border-indigo-200/60 bg-gradient-to-br from-white via-indigo-50/40 to-violet-50/20 shadow-md dark:border-indigo-900/30 dark:from-slate-900 dark:via-indigo-950/15 dark:to-slate-900">
            <div class="pointer-events-none absolute -left-16 top-0 h-48 w-48 rounded-full bg-indigo-400/10 blur-3xl" aria-hidden="true"></div>
            <div class="relative p-6 sm:p-8">
                <nav aria-label="breadcrumb" class="mb-4 flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                    <a href="{{ route('diretoria.dashboard') }}"
                        class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Painel da diretoria</a>
                    <x-icon name="chevron-right" class="h-3 w-3 text-slate-400" style="duotone" />
                    <a href="{{ route('diretoria.notificacoes.index') }}"
                        class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">Notificações</a>
                    <x-icon name="chevron-right" class="h-3 w-3 text-slate-400" style="duotone" />
                    <span class="font-semibold text-gray-900 dark:text-white">Nova</span>
                </nav>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h1 class="flex flex-wrap items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/25">
                                <x-icon module="notificacoes" class="h-7 w-7" style="duotone" />
                            </span>
                            Enviar uma notificação
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                            Escolha o tipo de aviso, escreva o texto e diga a quem deve chegar. A mensagem aparece em tempo real aos utilizadores ligados e fica guardada para quem entrar mais tarde.
                        </p>
                    </div>
                    <a href="{{ route('diretoria.notificacoes.index') }}"
                        class="inline-flex shrink-0 items-center gap-2 self-start rounded-xl border border-gray-200 bg-white/90 px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-200 dark:hover:bg-slate-700">
                        <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
                        Voltar à lista
                    </a>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/20 dark:text-red-200"
                role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div
            x-data="{
                recipientType: @js($oldRecipient),
                setRecipient(v) { this.recipientType = v; }
            }"
            class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-slate-700/50 dark:bg-slate-800">
            <form action="{{ route('diretoria.notificacoes.store') }}" method="POST" class="divide-y divide-gray-100 dark:divide-slate-700/80">
                @csrf

                {{-- Secção 1: conteúdo --}}
                <div class="p-6 md:p-8">
                    <div class="mb-6 flex items-start gap-3">
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">1</span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">O que enviar</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tipo, título e mensagem visíveis no painel do utilizador.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8">
                        <div>
                            <label for="type"
                                class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Tipo <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <x-icon name="tag" class="h-4 w-4 text-gray-400" />
                                </div>
                                <select id="type" name="type" required
                                    class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                    <option value="info" @selected(old('type') == 'info')>Informação</option>
                                    <option value="success" @selected(old('type') == 'success')>Sucesso</option>
                                    <option value="warning" @selected(old('type') == 'warning')>Aviso</option>
                                    <option value="error" @selected(old('type') == 'error')>Erro</option>
                                    <option value="alert" @selected(old('type') == 'alert')>Alerta</option>
                                    <option value="system" @selected(old('type') == 'system')>Sistema</option>
                                </select>
                            </div>
                            @error('type')
                                <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="title"
                                class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Título <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <x-icon name="heading" class="h-4 w-4 text-gray-400" />
                                </div>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                    placeholder="Ex.: Reunião de equipa amanhã"
                                    class="block w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            </div>
                            @error('title')
                                <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="message"
                                class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Mensagem <span
                                    class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="5" required
                                placeholder="Escreva aqui o texto principal da notificação. Seja claro e objetivo."
                                class="block w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm leading-relaxed text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Secção 2: destinatários --}}
                <div class="p-6 md:p-8">
                    <div class="mb-6 flex items-start gap-3">
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">2</span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Quem recebe</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Uma pessoa, um grupo com uma função no sistema, ou todos os utilizadores.</p>
                        </div>
                    </div>

                    <input type="hidden" name="recipient_type" :value="recipientType">

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <button type="button" @click="setRecipient('user')"
                            :class="recipientType === 'user' ? 'ring-2 ring-indigo-500 dark:ring-indigo-400 border-indigo-500 bg-indigo-50 dark:bg-indigo-950/40' : 'border-gray-200 dark:border-slate-600 hover:border-indigo-300 dark:hover:border-indigo-700'"
                            class="flex flex-col items-start gap-2 rounded-2xl border bg-white p-4 text-left transition dark:bg-slate-900/50">
                            <x-icon name="user" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            <span class="font-semibold text-gray-900 dark:text-white">Uma pessoa</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Escolhe um utilizador na lista abaixo.</span>
                        </button>
                        <button type="button" @click="setRecipient('role')"
                            :class="recipientType === 'role' ? 'ring-2 ring-indigo-500 dark:ring-indigo-400 border-indigo-500 bg-indigo-50 dark:bg-indigo-950/40' : 'border-gray-200 dark:border-slate-600 hover:border-indigo-300 dark:hover:border-indigo-700'"
                            class="flex flex-col items-start gap-2 rounded-2xl border bg-white p-4 text-left transition dark:bg-slate-900/50">
                            <x-icon name="user-shield" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            <span class="font-semibold text-gray-900 dark:text-white">Um grupo (função)</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Todos com a mesma role (ex.: líderes).</span>
                        </button>
                        <button type="button" @click="setRecipient('all')"
                            :class="recipientType === 'all' ? 'ring-2 ring-indigo-500 dark:ring-indigo-400 border-indigo-500 bg-indigo-50 dark:bg-indigo-950/40' : 'border-gray-200 dark:border-slate-600 hover:border-indigo-300 dark:hover:border-indigo-700'"
                            class="flex flex-col items-start gap-2 rounded-2xl border bg-white p-4 text-left transition dark:bg-slate-900/50">
                            <x-icon name="users" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            <span class="font-semibold text-gray-900 dark:text-white">Toda a gente</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Envio geral a todos os utilizadores.</span>
                        </button>
                    </div>

                    <div x-show="recipientType === 'user'" x-transition
                        class="mt-6">
                        <label for="user_id" class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Utilizador</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="user" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select id="user_id" name="user_id"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                :required="recipientType === 'user'">
                                <option value="">Escolha um utilizador…</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('user_id')
                            <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="recipientType === 'role'" x-transition
                        class="mt-6">
                        <label for="role" class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Função (role)</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <x-icon name="shield" class="h-4 w-4 text-gray-400" />
                            </div>
                            <select id="role" name="role"
                                class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white"
                                :required="recipientType === 'role'">
                                <option value="">Escolha uma função…</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected(old('role') == $role->name)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role')
                            <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    @error('recipient_type')
                        <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Secção 3: opcionais --}}
                <div class="p-6 md:p-8">
                    <div class="mb-6 flex items-start gap-3">
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-200">3</span>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Onde encaixa (opcional)</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Liga a notificação a um módulo e, se quiser, a um link quando o utilizador tocar em “Ação”.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 md:gap-8">
                        <div>
                            <label for="module_source" class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Módulo de origem</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <x-icon name="layer-group" class="h-4 w-4 text-gray-400" />
                                </div>
                                <select id="module_source" name="module_source"
                                    class="block w-full appearance-none rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                                    <option value="">Nenhum (geral)</option>
                                    @foreach ($modules as $key => $label)
                                        <option value="{{ $key }}" @selected(old('module_source') == $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="action_url" class="mb-2 block text-xs font-semibold text-gray-700 dark:text-gray-300">Link de ação (opcional)</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <x-icon name="link" class="h-4 w-4 text-gray-400" />
                                </div>
                                <input type="url" id="action_url" name="action_url" value="{{ old('action_url') }}"
                                    placeholder="https://…"
                                    class="block w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-11 pr-4 text-sm text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            </div>
                            @error('action_url')
                                <p class="mt-1 text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Rodapé --}}
                <div class="space-y-6 bg-gray-50/80 p-6 dark:bg-slate-900/40 md:p-8">
                    <div class="flex gap-4 rounded-2xl border border-indigo-100 bg-indigo-50/80 p-4 dark:border-indigo-800/30 dark:bg-indigo-950/30">
                        <div class="shrink-0 rounded-xl bg-indigo-100 p-2 dark:bg-indigo-900/50">
                            <x-icon name="lightbulb" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" style="duotone" />
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-indigo-900 dark:text-indigo-100">Como isto funciona?</h3>
                            <p class="mt-1 text-xs leading-relaxed text-indigo-900/85 dark:text-indigo-200/90">
                                A mensagem é enviada em tempo real a quem estiver online. Quem não estiver ligado verá a notificação na próxima vez que abrir o painel. Pode segmentar por pessoa, por função de acesso ou enviar a todos.
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-end gap-3">
                        <a href="{{ route('diretoria.notificacoes.index') }}"
                            class="rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-gray-300 dark:hover:bg-slate-700">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/25 transition hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800">
                            <x-icon name="paper-plane-top" class="h-5 w-5" style="duotone" />
                            Enviar notificação
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
