@extends('homepage::layouts.homepage')

@section('title')
    {{ $configs['contato_page_title'] ?? 'Contato' }} — {{ \App\Support\SiteBranding::siteName() }}
@endsection

@section('content')
    @include('homepage::layouts.navbar-homepage')

    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10">
                    <div
                        class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <x-icon name="map-location-dot" style="duotone" class="w-4 h-4" />
                        JUBAF · Feira de Santana e região
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-poppins text-gray-900 dark:text-white mb-4">
                        {{ $configs['contato_page_title'] ?? 'Fale com a JUBAF' }}
                    </h1>
                    <p class="text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
                        {{ $configs['contato_page_lead'] ?? '' }}
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-8 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-800 dark:bg-green-900/30 dark:text-green-200"
                        role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('newsletter_status'))
                    <div class="mb-8 rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-blue-900 dark:border-blue-800 dark:bg-blue-900/30 dark:text-blue-100"
                        role="status">
                        {{ session('newsletter_status') }}
                    </div>
                @endif

                <div class="grid gap-6 md:grid-cols-3 mb-12">
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-lg dark:border-slate-600 dark:bg-slate-800">
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500">
                            <x-icon name="phone" style="duotone" class="h-7 w-7 text-white" />
                        </div>
                        <h2 class="font-bold text-gray-900 dark:text-white">Telefone</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $configs['telefone'] !== '' ? $configs['telefone'] : '—' }}</p>
                    </div>
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-lg dark:border-slate-600 dark:bg-slate-800">
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-800">
                            <x-icon name="envelope" style="duotone" class="h-7 w-7 text-white" />
                        </div>
                        <h2 class="font-bold text-gray-900 dark:text-white">E-mail</h2>
                        <p class="mt-2 break-all text-sm text-gray-600 dark:text-gray-400">
                            {{ $configs['email'] !== '' ? $configs['email'] : '—' }}</p>
                    </div>
                    <div
                        class="rounded-2xl border border-gray-200 bg-white p-6 text-center shadow-lg dark:border-slate-600 dark:bg-slate-800">
                        <div
                            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-slate-600 to-slate-800">
                            <x-icon name="map-location-dot" style="duotone" class="h-7 w-7 text-white" />
                        </div>
                        <h2 class="font-bold text-gray-900 dark:text-white">Endereço</h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ $configs['endereco'] !== '' ? $configs['endereco'] : 'Feira de Santana — BA e região' }}</p>
                    </div>
                </div>

                <div class="grid gap-8 lg:grid-cols-2">
                    @if ($configs['contato_form_enabled'] ?? true)
                        <div
                            class="rounded-2xl border border-gray-200 bg-white p-6 shadow-xl dark:border-slate-600 dark:bg-slate-800 md:p-8">
                            <h2 class="mb-2 flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white">
                                <x-icon name="paper-plane" style="duotone" class="h-6 w-6 text-blue-600" />
                                Enviar mensagem
                            </h2>
                            <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">A sua mensagem chega à equipe que gere
                                o site e a comunicação regional da JUBAF.</p>

                            @if ($errors->any())
                                <div
                                    class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800 dark:border-red-900 dark:bg-red-900/20 dark:text-red-200">
                                    <ul class="list-inside list-disc space-y-1">
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('contato.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="c_name"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                    <input type="text" id="c_name" name="name" value="{{ old('name') }}"
                                        required maxlength="120"
                                        class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white" />
                                </div>
                                <div>
                                    <label for="c_email"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                                    <input type="email" id="c_email" name="email" value="{{ old('email') }}"
                                        required
                                        class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white" />
                                </div>
                                <div>
                                    <label for="c_phone"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone
                                        (opcional)</label>
                                    <input type="text" id="c_phone" name="phone" value="{{ old('phone') }}"
                                        maxlength="40"
                                        class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white" />
                                </div>
                                <div>
                                    <label for="c_subject"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Assunto
                                        (opcional)</label>
                                    <input type="text" id="c_subject" name="subject" value="{{ old('subject') }}"
                                        maxlength="255"
                                        class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white" />
                                </div>
                                <div>
                                    <label for="c_message"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Mensagem</label>
                                    <textarea id="c_message" name="message" rows="5" required maxlength="5000"
                                        class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 px-4 py-3 text-gray-900 focus:border-blue-600 focus:ring-blue-600 dark:border-slate-600 dark:bg-slate-700/50 dark:text-white">{{ old('message') }}</textarea>
                                </div>
                                <button type="submit"
                                    class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 py-3 font-semibold text-white shadow-md transition hover:from-blue-700 hover:to-blue-900">
                                    Enviar mensagem
                                </button>
                            </form>
                        </div>
                    @endif

                    @if ($configs['newsletter_public_enabled'] ?? true)
                        <div
                            class="flex flex-col justify-center rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-white p-6 shadow-xl dark:border-indigo-900/50 dark:from-indigo-950/40 dark:to-slate-800 md:p-8">
                            <h2 class="mb-2 flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white">
                                <x-icon name="envelope-open-text" style="duotone"
                                    class="h-6 w-6 text-indigo-600 dark:text-indigo-400" />
                                {{ $configs['newsletter_box_title'] ?? 'Newsletter JUBAF' }}
                            </h2>
                            <p class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                                {{ $configs['newsletter_box_lead'] ?? '' }}</p>
                            <form action="{{ route('homepage.newsletter.subscribe') }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="n_email"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">E-mail</label>
                                    <input type="email" id="n_email" name="email" value="{{ old('email') }}"
                                        required
                                        class="w-full rounded-xl border-2 border-indigo-200 bg-white px-4 py-3 text-gray-900 focus:border-indigo-600 focus:ring-indigo-600 dark:border-indigo-800 dark:bg-slate-900 dark:text-white" />
                                </div>
                                <div>
                                    <label for="n_name"
                                        class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nome
                                        (opcional)</label>
                                    <input type="text" id="n_name" name="name" value="{{ old('name') }}"
                                        maxlength="120"
                                        class="w-full rounded-xl border-2 border-indigo-200 bg-white px-4 py-3 text-gray-900 focus:border-indigo-600 focus:ring-indigo-600 dark:border-indigo-800 dark:bg-slate-900 dark:text-white" />
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Ao inscrever-se, aceita receber
                                    comunicações regionais da JUBAF. Pode solicitar remoção contactando a diretoria.</p>
                                <button type="submit"
                                    class="w-full rounded-xl bg-indigo-600 py-3 font-semibold text-white shadow-md transition hover:bg-indigo-700">
                                    Subscrever newsletter
                                </button>
                            </form>
                        </div>
                    @elseif (!($configs['contato_form_enabled'] ?? true) && !($configs['newsletter_public_enabled'] ?? true))
                        <div
                            class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-gray-500 dark:border-slate-600 dark:text-gray-400 lg:col-span-2">
                            O formulário e a newsletter estão desativados nas configurações. Utilize telefone ou e-mail
                            acima para contactar a JUBAF.
                        </div>
                    @endif
                </div>

                <p class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    <a href="{{ route('homepage') }}"
                        class="font-medium text-blue-600 hover:underline dark:text-blue-400">← Voltar à página inicial</a>
                    ·
                    <a href="{{ route('privacidade') }}" class="hover:underline">Privacidade</a>
                </p>
            </div>
        </div>
    </div>

    @include('homepage::layouts.footer-homepage')
@endsection
