@extends('paineljovens::layouts.jovens')

@section('title', 'Perfil')

@section('jovens_content')
    @php
        $jp = $user->jovemPerfil;
        $sl = $jp?->social_links ?? [];
        $card = 'rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800';
        $stepperSteps = [
            ['key' => 'perfil', 'label' => 'Perfil'],
            ['key' => 'censo', 'label' => 'Censo e redes'],
            ['key' => 'seguranca', 'label' => 'Emergência e segurança'],
        ];
        $completenessItems = [
            ['label' => 'Telefone pessoal', 'done' => filled($user->phone)],
            ['label' => 'Data de nascimento', 'done' => (bool) $user->birth_date],
            ['label' => 'Contacto de emergência', 'done' => filled($user->emergency_contact_name) && filled($user->emergency_contact_phone)],
            ['label' => 'Censo ou redes (opcional)', 'done' => filled($jp?->profession) || filled($jp?->marital_status) || filled($jp?->census_bio) || filled($sl['instagram'] ?? null) || filled($sl['youtube'] ?? null) || filled($sl['outro'] ?? null)],
        ];
        $completenessDone = collect($completenessItems)->where('done')->count();
        $completenessPct = (int) round(100 * $completenessDone / max(1, count($completenessItems)));
        $fieldBase = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:placeholder:text-gray-500 dark:focus:border-blue-400 dark:focus:ring-blue-400/20';
    @endphp

    <x-ui.jovens::page-shell class="space-y-6 md:space-y-8 pb-28 lg:pb-10">
        <header
            class="relative overflow-hidden rounded-[2rem] border border-gray-200/90 dark:border-gray-800 bg-gradient-to-br from-blue-700 via-blue-800 to-gray-900 text-white shadow-xl">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
            <div class="relative flex flex-col gap-6 px-6 py-8 md:flex-row md:items-end md:justify-between md:px-10 md:py-10">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-200/90">Unijovem · Conta</p>
                    <h1 class="mt-2 text-3xl font-bold tracking-tight md:text-4xl">Perfil e dados pessoais</h1>
                    <p class="mt-3 text-sm leading-relaxed text-blue-100/95 md:text-base">
                        Capa, fotos, dados visíveis, censo da juventude e segurança — organize por etapas e guarde quando terminar.
                    </p>
                </div>
                <div class="flex w-full shrink-0 flex-col gap-2 sm:flex-row sm:justify-end md:w-auto">
                    <a href="{{ route('jovens.dashboard') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-sm transition hover:bg-white/20">
                        <x-icon name="grid-2-plus" class="h-4 w-4" style="duotone" />
                        Painel
                    </a>
                    @if (module_enabled('Calendario') && Route::has('jovens.wallet.index') && auth()->user()?->can('calendario.participate'))
                        <a href="{{ route('jovens.wallet.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-blue-900 shadow-lg transition hover:bg-blue-50">
                            <x-icon name="ticket" class="h-4 w-4" />
                            Carteira
                        </a>
                    @endif
                </div>
            </div>
        </header>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-950/40 dark:text-red-200"
                role="alert" aria-live="polite">
                <p class="font-semibold">Corrija os campos assinalados antes de guardar.</p>
                <ul class="mt-2 list-inside list-disc space-y-0.5 text-xs">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('jovens.profile.update') }}" enctype="multipart/form-data" id="profileForm"
            class="space-y-6 md:space-y-8">
            @csrf
            @method('PUT')

            <x-profile.avatar-studio :user="$user" accent="blue" variant="hero" show-identity />

            <x-profile.panel-quick-links context="jovens" accent="blue"
                class="rounded-lg border border-gray-200/90 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-900" />

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-8">
                <div class="min-w-0 space-y-6 lg:col-span-8">
                    <div class="{{ $card }} p-4 sm:p-6">
                        <x-ui.jovens::stepper :steps="$stepperSteps" :initial="0">
                            <div x-show="step === 0" x-cloak class="space-y-5">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Detalhes do perfil</h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Informações visíveis na sua conta. A ficha institucional da igreja é gerida no módulo Igrejas.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <x-ui.input label="Nome *" name="first_name" :value="old('first_name', $user->first_name)" required maxlength="120" />
                                    <x-ui.input label="Sobrenome" name="last_name" :value="old('last_name', $user->last_name)" maxlength="120" />
                                    <div class="space-y-1.5 md:col-span-2">
                                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">E-mail de login</span>
                                        <div
                                            class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-800 dark:border-gray-600 dark:bg-gray-900/80 dark:text-gray-200">
                                            {{ $user->email }}
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">Alteração apenas com aprovação da diretoria — use o pedido no final desta página.</p>
                                    </div>
                                    <x-ui.input label="Data de nascimento" name="birth_date" type="date" :value="old('birth_date', $user->birth_date?->format('Y-m-d'))" />
                                    <x-ui.input label="Telefone pessoal" name="phone" id="phone" :value="old('phone', $user->phone)" />
                                    <x-ui.input class="md:col-span-2" label="Telefone na função / igreja" name="church_phone" :value="old('church_phone', $user->church_phone)" maxlength="32" />
                                    <div class="space-y-1.5 md:col-span-2">
                                        <span class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">CPF</span>
                                        <div
                                            class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-600 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300">
                                            {{ $user->cpf ? format_cpf_pt($user->cpf) : '—' }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div x-show="step === 1" x-cloak class="space-y-5">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Censo juventude</h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        Dados para o mapeamento da Unijovem (estado civil, profissão e redes). Opcional.
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                    <x-ui.input label="Estado civil" name="marital_status" id="marital_status" :value="old('marital_status', $jp?->marital_status)" maxlength="48" hint="Ex.: solteiro(a)" />
                                    <x-ui.input label="Profissão / ocupação" name="profession" id="profession" :value="old('profession', $jp?->profession)" maxlength="160" />
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label for="census_bio" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nota para o censo</label>
                                        <textarea id="census_bio" name="census_bio" rows="3" maxlength="2000"
                                            placeholder="Breve descrição para a equipa regional (opcional)."
                                            class="{{ $fieldBase }}">{{ old('census_bio', $jp?->census_bio) }}</textarea>
                                        @error('census_bio')
                                            <p class="text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <x-ui.jovens::social-link-input class="md:col-span-1" label="Instagram" name="social_instagram" icon="hashtag"
                                        placeholder="@utilizador ou URL" :value="old('social_instagram', $sl['instagram'] ?? '')" />
                                    <x-ui.jovens::social-link-input class="md:col-span-1" label="YouTube" name="social_youtube" icon="video"
                                        :value="old('social_youtube', $sl['youtube'] ?? '')" />
                                    <x-ui.jovens::social-link-input class="md:col-span-2" label="Outra rede ou portfólio" name="social_outro" icon="link"
                                        :value="old('social_outro', $sl['outro'] ?? '')" />
                                </div>
                                @if (module_enabled('Talentos') && Route::has('jovens.talentos.profile.edit'))
                                    <p class="rounded-lg border border-blue-100 bg-blue-50/80 p-4 text-sm text-blue-900 dark:border-blue-900/40 dark:bg-blue-950/30 dark:text-blue-100">
                                        <x-icon name="star" class="mr-1 inline h-4 w-4 opacity-80" style="duotone" />
                                        Competências e disponibilidade para equipas regionais:
                                        <a href="{{ route('jovens.talentos.profile.edit') }}" class="font-semibold underline decoration-blue-600/30 hover:decoration-blue-600">Banco de talentos</a>
                                    </p>
                                @endif
                            </div>

                            <div x-show="step === 2" x-cloak class="space-y-6">
                                <section class="rounded-lg border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-900/40 sm:p-5">
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Contacto de emergência</h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Para eventos e situações urgentes da organização.</p>
                                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                                        <x-ui.input label="Nome" name="emergency_contact_name" id="emergency_contact_name" :value="old('emergency_contact_name', $user->emergency_contact_name)" maxlength="120" />
                                        <x-ui.input label="Telefone" name="emergency_contact_phone" id="emergency_contact_phone" :value="old('emergency_contact_phone', $user->emergency_contact_phone)" maxlength="32" />
                                        <x-ui.input label="Parentesco" name="emergency_contact_relationship" id="emergency_contact_relationship" :value="old('emergency_contact_relationship', $user->emergency_contact_relationship)" maxlength="80" hint="Ex.: mãe, cônjuge" />
                                    </div>
                                </section>

                                <section class="rounded-lg border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-700 dark:bg-gray-900/40 sm:p-5">
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Palavra-passe</h2>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Deixe em branco para manter a palavra-passe actual.</p>
                                    <div class="mt-4 max-w-xl space-y-4">
                                        <div class="space-y-1.5">
                                            <label for="current_password" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Palavra-passe atual</label>
                                            <input type="password" id="current_password" name="current_password" autocomplete="current-password" class="{{ $fieldBase }}" />
                                            @error('current_password')
                                                <p class="text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                            <div class="space-y-1.5">
                                                <label for="password" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nova palavra-passe</label>
                                                <input type="password" id="password" name="password" autocomplete="new-password" class="{{ $fieldBase }}" />
                                                @error('password')
                                                    <p class="text-xs font-medium text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="space-y-1.5">
                                                <label for="password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">Confirmar</label>
                                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="{{ $fieldBase }}" />
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </x-ui.jovens::stepper>
                    </div>
                </div>

                <aside class="space-y-6 lg:col-span-4">
                    <div class="{{ $card }} p-5 sm:p-6">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Completude do perfil</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Sugestões para manter a conta útil para a equipa.</p>
                        <div class="mt-4" role="progressbar" aria-valuenow="{{ $completenessPct }}" aria-valuemin="0" aria-valuemax="100"
                            aria-label="Percentagem de campos sugeridos preenchidos">
                            <div class="h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-full rounded-full bg-blue-600 transition-all dark:bg-blue-500" style="width: {{ $completenessPct }}%"></div>
                            </div>
                            <p class="mt-2 text-xs font-medium text-gray-600 dark:text-gray-300">{{ $completenessPct }}% · {{ $completenessDone }}/{{ count($completenessItems) }} itens</p>
                        </div>
                        <ul class="mt-4 space-y-2 text-sm">
                            @foreach ($completenessItems as $row)
                                <li class="flex items-start gap-2">
                                    @if ($row['done'])
                                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300" aria-hidden="true">
                                            <x-icon name="check" class="h-3 w-3" style="solid" />
                                        </span>
                                    @else
                                        <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-400 dark:border-gray-600 dark:bg-gray-800" aria-hidden="true">
                                            <span class="sr-only">Por fazer</span>
                                        </span>
                                    @endif
                                    <span @class([
                                        'text-gray-900 dark:text-gray-100' => $row['done'],
                                        'text-gray-600 dark:text-gray-400' => ! $row['done'],
                                    ])>{{ $row['label'] }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="{{ $card }} hidden p-4 sm:p-5 lg:block lg:sticky lg:top-4">
                        <button type="submit"
                            class="flex h-11 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 active:scale-[0.99] dark:focus-visible:ring-offset-gray-900">
                            <x-icon name="floppy-disk" class="h-4 w-4" style="duotone" />
                            Guardar alterações
                        </button>
                        <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400">Inclui capa, fotos da galeria e todos os dados do formulário.</p>
                        <p class="mt-4 border-t border-gray-100 pt-4 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            Membro desde <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $user->created_at->translatedFormat('F Y') }}</span>
                        </p>
                        @if (module_enabled('Igrejas') && Route::has('jovens.igreja.index'))
                            <a href="{{ route('jovens.igreja.index') }}"
                                class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">
                                <x-icon name="building-columns" class="h-4 w-4" style="duotone" />
                                Dados da minha igreja
                            </a>
                        @endif
                    </div>
                </aside>
            </div>
        </form>

        <div class="lg:hidden fixed inset-x-0 z-30 border-t border-gray-200 bg-white/95 px-4 py-3 shadow-[0_-8px_30px_rgba(0,0,0,0.08)] backdrop-blur-md dark:border-gray-700 dark:bg-gray-900/95"
            style="bottom: calc(5rem + env(safe-area-inset-bottom, 0px))">
            <button type="submit" form="profileForm"
                class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-blue-600 text-sm font-bold text-white shadow-md transition hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-gray-900">
                <x-icon name="floppy-disk" class="h-4 w-4" style="duotone" />
                Guardar alterações
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 lg:gap-8">
            <div class="min-w-0 lg:col-span-8">
                <x-profile-sensitive-data-request :action="route('jovens.profile.sensitive-data-request.store')" accent="blue" />
            </div>
        </div>
    </x-ui.jovens::page-shell>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var phone = document.getElementById('phone');
                if (phone) {
                    phone.addEventListener('input', function(e) {
                        var v = e.target.value.replace(/\D/g, '');
                        if (v.length > 11) v = v.substring(0, 11);
                        if (v.length <= 10) v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                        else v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
                        e.target.value = v;
                    });
                }

                var form = document.getElementById('profileForm');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        var pw = document.getElementById('password');
                        var pwc = document.getElementById('password_confirmation');
                        if (pw && pwc && pw.value && pw.value !== pwc.value) {
                            e.preventDefault();
                            alert('As palavras-passe não coincidem.');
                            pwc.focus();
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
