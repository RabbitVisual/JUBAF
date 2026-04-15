@extends('layouts.app')

@section('title', 'Perfil')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-violet-600 dark:text-violet-400">Perfil</span>
@endsection

@section('content')
@php
    $fbBg = 'bg-[#f0f2f5] dark:bg-slate-950';
    $fbCard = 'rounded-xl bg-white dark:bg-slate-900 shadow-[0_1px_2px_rgba(0,0,0,0.06)] dark:shadow-none ring-1 ring-black/[0.06] dark:ring-white/10';
@endphp
<div class="-mx-4 -mt-2 md:-mx-6 md:-mt-2 lg:-mx-8 {{ $fbBg }} min-h-[calc(100vh-6rem)] px-3 py-4 sm:px-4 md:px-6 pb-16">
    @if(session('error'))
        <div class="mx-auto mb-4 max-w-5xl rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/40 dark:bg-red-950/40 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="mx-auto max-w-5xl space-y-3">
        {{-- Formulário único: capa, fotos e todos os campos editáveis (sem aninhar outro <form>) --}}
        <form method="POST" action="{{ route('jovens.profile.update') }}" enctype="multipart/form-data" id="profileForm" class="space-y-3">
            @csrf
            @method('PUT')

            <x-profile.avatar-studio :user="$user" accent="violet" variant="hero" />

            {{-- Barra tipo Facebook: nome + meta --}}
            <div class="flex flex-col gap-4 border-b border-slate-200/80 bg-white px-4 py-4 dark:border-slate-700 dark:bg-slate-900 sm:flex-row sm:items-end sm:justify-between sm:px-6 sm:py-5 {{ $fbCard }} border-0 shadow-none ring-0">
                <div class="min-w-0 pt-1">
                    <h1 class="truncate text-2xl font-bold tracking-tight text-[#050505] dark:text-white sm:text-[28px]">{{ $user->name }}</h1>
                    <p class="mt-0.5 text-[15px] text-[#65676B] dark:text-slate-400">
                        @if($user->church)
                            <span class="font-medium text-slate-700 dark:text-slate-300">{{ $user->church->name }}</span>
                            <span class="mx-1.5 text-slate-300 dark:text-slate-600">·</span>
                        @endif
                        <span>Unijovem · ID {{ $user->id }}</span>
                    </p>
                    @if($user->roles->isNotEmpty())
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($user->roles as $role)
                                <span class="inline-flex rounded-md bg-[#E7F3FF] px-2 py-0.5 text-xs font-semibold text-[#1877F2] dark:bg-violet-950/50 dark:text-violet-300">{{ jubaf_role_label($role->name) }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="flex shrink-0 gap-2">
                    <a href="{{ route('jovens.dashboard') }}" class="inline-flex h-9 items-center justify-center rounded-lg bg-[#E4E6EB] px-4 text-sm font-semibold text-[#050505] transition hover:bg-[#D8DADF] dark:bg-slate-700 dark:text-white dark:hover:bg-slate-600">
                        Voltar ao painel
                    </a>
                </div>
            </div>

            <x-profile.panel-quick-links context="jovens" accent="violet" class="!rounded-xl !border-0 !bg-white !p-4 !shadow-[0_1px_2px_rgba(0,0,0,0.06)] dark:!bg-slate-900 dark:!ring-1 dark:!ring-white/10" />

            <div class="grid grid-cols-1 gap-3 lg:grid-cols-12 lg:gap-4">
                <div class="space-y-3 lg:col-span-8">
                    <section class="{{ $fbCard }} p-4 sm:p-6">
                        <h2 class="mb-1 text-xl font-bold text-[#050505] dark:text-white">Detalhes do perfil</h2>
                        <p class="mb-6 text-[15px] text-[#65676B] dark:text-slate-400">Atualize as informações visíveis na sua conta. A ficha institucional da igreja é gerida no módulo Igrejas.</p>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="first_name">Nome <span class="text-red-500">*</span></label>
                                <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required maxlength="120"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#050505] placeholder:text-slate-400 focus:border-[#1877F2] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1877F2] dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:focus:border-violet-500 dark:focus:ring-violet-500">
                                @error('first_name')
                                    <p class="text-xs font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="last_name">Sobrenome</label>
                                <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" maxlength="120"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#050505] focus:border-[#1877F2] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1877F2] dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:focus:border-violet-500 dark:focus:ring-violet-500">
                                @error('last_name')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400">E-mail de login</label>
                                <div class="rounded-lg border border-dashed border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#050505] dark:border-slate-600 dark:bg-slate-800/80 dark:text-slate-200">
                                    {{ $user->email }}
                                </div>
                                <p class="text-xs text-[#65676B] dark:text-slate-500">Alteração apenas com aprovação da diretoria — use o pedido no final desta página.</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="birth_date">Data de nascimento</label>
                                <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#050505] focus:border-[#1877F2] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1877F2] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('birth_date')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="phone">Telefone pessoal</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#050505] focus:border-[#1877F2] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1877F2] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="church_phone">Telefone na função / igreja</label>
                                <input id="church_phone" type="text" name="church_phone" value="{{ old('church_phone', $user->church_phone) }}" maxlength="32"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#050505] focus:border-[#1877F2] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#1877F2] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('church_phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400">CPF</label>
                                <div class="rounded-lg border border-dashed border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] text-[#65676B] dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                    {{ $user->cpf ? format_cpf_pt($user->cpf) : '—' }}
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="{{ $fbCard }} p-4 sm:p-6">
                        <h2 class="mb-1 text-xl font-bold text-[#050505] dark:text-white">Censo juventude</h2>
                        <p class="mb-6 text-[15px] text-[#65676B] dark:text-slate-400">Dados para o mapeamento da Unijovem (estado civil, profissão e redes). Opcional.</p>
                        @php $jp = $user->jovemPerfil; $sl = $jp?->social_links ?? []; @endphp
                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="marital_status">Estado civil</label>
                                <input id="marital_status" type="text" name="marital_status" value="{{ old('marital_status', $jp?->marital_status) }}" maxlength="48" placeholder="Ex.: solteiro(a)"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('marital_status')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="profession">Profissão / ocupação</label>
                                <input id="profession" type="text" name="profession" value="{{ old('profession', $jp?->profession) }}" maxlength="160"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('profession')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="census_bio">Nota para o censo</label>
                                <textarea id="census_bio" name="census_bio" rows="3" maxlength="2000" placeholder="Breve descrição para a equipa regional (opcional)."
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">{{ old('census_bio', $jp?->census_bio) }}</textarea>
                                @error('census_bio')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="social_instagram">Instagram</label>
                                <input id="social_instagram" type="text" name="social_instagram" value="{{ old('social_instagram', $sl['instagram'] ?? '') }}"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white" placeholder="@utilizador ou URL">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="social_youtube">YouTube</label>
                                <input id="social_youtube" type="text" name="social_youtube" value="{{ old('social_youtube', $sl['youtube'] ?? '') }}"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            </div>
                            <div class="space-y-1.5 md:col-span-2">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="social_outro">Outra rede ou portfólio</label>
                                <input id="social_outro" type="text" name="social_outro" value="{{ old('social_outro', $sl['outro'] ?? '') }}"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                            </div>
                        </div>
                        @if(module_enabled('Talentos') && Route::has('jovens.talentos.profile.edit'))
                            <p class="mt-6 text-sm text-[#65676B] dark:text-slate-400">
                                Competências e disponibilidade para equipas regionais:
                                <a href="{{ route('jovens.talentos.profile.edit') }}" class="font-semibold text-[#1877F2] hover:underline dark:text-violet-400">Banco de talentos</a>
                            </p>
                        @endif
                    </section>

                    <section class="{{ $fbCard }} p-4 sm:p-6">
                        <h2 class="mb-4 text-lg font-bold text-[#050505] dark:text-white">Contacto de emergência</h2>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="emergency_contact_name">Nome</label>
                                <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" maxlength="120"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('emergency_contact_name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="emergency_contact_phone">Telefone</label>
                                <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" maxlength="32"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('emergency_contact_phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div class="space-y-1.5 md:col-span-1">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="emergency_contact_relationship">Parentesco</label>
                                <input id="emergency_contact_relationship" type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $user->emergency_contact_relationship) }}" maxlength="80" placeholder="Ex.: mãe, cônjuge"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('emergency_contact_relationship')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </section>

                    <section class="{{ $fbCard }} p-4 sm:p-6">
                        <h2 class="mb-1 text-lg font-bold text-[#050505] dark:text-white">Segurança</h2>
                        <p class="mb-5 text-[15px] text-[#65676B] dark:text-slate-400">Deixe em branco para manter a palavra-passe atual.</p>
                        <div class="max-w-xl space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="current_password">Palavra-passe atual</label>
                                <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                                    class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                @error('current_password')
                                    <p class="text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="password">Nova palavra-passe</label>
                                    <input type="password" id="password" name="password" autocomplete="new-password"
                                        class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                    @error('password')
                                        <p class="text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[13px] font-semibold text-[#606770] dark:text-slate-400" for="password_confirmation">Confirmar</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                                        class="w-full rounded-lg border border-[#CCD0D5] bg-[#F5F6F7] px-3 py-2.5 text-[15px] dark:border-slate-600 dark:bg-slate-800 dark:text-white">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-3 lg:col-span-4">
                    <div class="{{ $fbCard }} p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[#65676B] dark:text-slate-500">Resumo</p>
                        <p class="mt-2 text-[15px] text-[#050505] dark:text-slate-200">Membro desde <span class="font-semibold">{{ $user->created_at->translatedFormat('F Y') }}</span></p>
                        <p class="mt-3 text-sm text-[#65676B] dark:text-slate-400">Última atividade: {{ $user->updated_at->diffForHumans() }}</p>
                        @if(module_enabled('Igrejas') && Route::has('jovens.igreja.index'))
                            <a href="{{ route('jovens.igreja.index') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#1877F2] hover:underline dark:text-violet-400">
                                <x-icon name="building-columns" class="h-4 w-4" style="duotone" />
                                Ver dados da minha igreja
                            </a>
                        @endif
                    </div>

                    <div class="{{ $fbCard }} sticky top-4 space-y-3 p-4">
                        <button type="submit" class="flex h-11 w-full items-center justify-center rounded-lg bg-[#1877F2] text-[15px] font-semibold text-white shadow-sm transition hover:bg-[#166fe5] active:scale-[0.99] dark:bg-violet-600 dark:hover:bg-violet-500">
                            Guardar alterações
                        </button>
                        <p class="text-center text-xs text-[#65676B] dark:text-slate-500">Inclui capa, fotos e dados acima.</p>
                    </div>
                </aside>
            </div>
        </form>

        {{-- Pedido e-mail/CPF: mesma largura da coluna principal (8/12) que Detalhes / Segurança --}}
        <div class="grid grid-cols-1 pt-2 lg:grid-cols-12 lg:gap-4">
            <div class="min-w-0 lg:col-span-8">
                <x-profile-sensitive-data-request :action="route('jovens.profile.sensitive-data-request.store')" accent="violet" />
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var phone = document.getElementById('phone');
        if (!phone) return;
        phone.addEventListener('input', function (e) {
            var v = e.target.value.replace(/\D/g, '');
            if (v.length > 11) v = v.substring(0, 11);
            if (v.length <= 10) v = v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            else v = v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
            e.target.value = v;
        });

        var form = document.getElementById('profileForm');
        if (form) {
            form.addEventListener('submit', function (e) {
                var pw = document.getElementById('password');
                var pwc = document.getElementById('password_confirmation');
                if (pw && pwc && pw.value && pw.value !== pwc.value) {
                    e.preventDefault();
                    alert('As palavras-passe não coincidem.');
                    return false;
                }
            });
        }
    });
</script>
@endpush
@endsection
