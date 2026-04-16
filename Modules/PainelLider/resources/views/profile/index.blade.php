@extends('painellider::layouts.lideres')

@section('title', 'Perfil')

@section('breadcrumbs')
    <x-icon name="chevron-right" class="w-3 h-3 shrink-0 opacity-50" />
    <span class="text-emerald-600 dark:text-emerald-400">Identidade e segurança</span>
@endsection

@section('lideres_content')
<x-ui.lideres::page-shell class="space-y-6 md:space-y-10 pb-12">
    @if(session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('lideres.profile.update') }}" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <x-profile.avatar-studio :user="$user" accent="emerald" variant="hero" />

        <x-profile.panel-quick-links context="lider" accent="emerald" class="mb-8" />

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/80 md:p-8 mb-8">
            <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-950/40 dark:text-emerald-200 mb-3">
                <span class="h-2 w-2 shrink-0 animate-pulse rounded-full bg-emerald-500"></span>
                Líder local JUBAF
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white md:text-3xl">{{ $user->name }}</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">ID #{{ $user->id }} · conta de líder</p>
            @if($user->church)
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Igreja principal: <span class="font-semibold">{{ $user->church->name }}</span></p>
            @endif
            @if($user->assignedChurches->isNotEmpty())
                <div class="mt-4 max-w-lg">
                    <p class="mb-1.5 text-xs font-semibold uppercase tracking-wider text-emerald-800 dark:text-emerald-300/90">Outras unidades / congregações</p>
                    <ul class="space-y-1 text-sm text-slate-700 dark:text-slate-300">
                        @foreach($user->assignedChurches as $c)
                            <li class="flex items-start gap-2">
                                <x-icon name="map-pin" class="mt-0.5 h-3.5 w-3.5 shrink-0 opacity-80" />
                                <span>{{ $c->name }}@if($c->pivot->role_on_church) <span class="text-slate-500 dark:text-slate-400">· {{ $c->pivot->role_on_church }}</span>@endif</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if($user->roles->isNotEmpty())
                <p class="mt-3 flex flex-wrap gap-2 text-xs text-slate-600 dark:text-slate-400">
                    @foreach($user->roles as $role)
                        <span class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-0.5 dark:border-slate-700 dark:bg-slate-800">{{ jubaf_role_label($role->name) }}</span>
                    @endforeach
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <div class="lg:col-span-8 space-y-10">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 md:p-10 shadow-sm">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-8 flex items-center gap-3">
                        <x-icon name="address-card" style="duotone" class="w-5 h-5 text-indigo-500 shrink-0" />
                        Dados da conta
                    </h3>

                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Estes são os seus <strong class="text-slate-700 dark:text-slate-300">dados pessoais</strong>. Dados da igreja (CNPJ, sede) ficam no módulo Igrejas; aqui apenas o vínculo e o seu contacto na função.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="first_name">Nome *</label>
                            <input id="first_name" type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required maxlength="120" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 dark:text-white transition-all">
                            @error('first_name')
                                <p class="text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="last_name">Sobrenome</label>
                            <input id="last_name" type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" maxlength="120" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 dark:text-white transition-all">
                            @error('last_name')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">E-mail de login</label>
                            <div class="w-full px-4 py-3.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-base text-slate-700 dark:text-slate-200 break-all">
                                {{ $user->email }}
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Alteração com aprovação da diretoria — formulário abaixo.</p>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="birth_date">Data de nascimento</label>
                            <input id="birth_date" type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 dark:text-white">
                            @error('birth_date')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="phone">Telefone pessoal</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 dark:text-white transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="church_phone">Telefone na função / igreja</label>
                            <input id="church_phone" type="text" name="church_phone" value="{{ old('church_phone', $user->church_phone) }}" maxlength="32" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 dark:text-white">
                            @error('church_phone')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1">CPF</label>
                            <div class="w-full px-4 py-3.5 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-base text-slate-600 dark:text-slate-300">
                                {{ $user->cpf ? format_cpf_pt($user->cpf) : '—' }}
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Correções via pedido à diretoria.</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 md:p-10 shadow-sm">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-3">
                        <x-icon name="heart-pulse" style="duotone" class="w-5 h-5 text-rose-500 shrink-0" />
                        Contacto de emergência
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="emergency_contact_name">Nome</label>
                            <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->emergency_contact_name) }}" maxlength="120" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base dark:text-white">
                            @error('emergency_contact_name')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="emergency_contact_phone">Telefone</label>
                            <input id="emergency_contact_phone" type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->emergency_contact_phone) }}" maxlength="32" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base dark:text-white">
                            @error('emergency_contact_phone')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="emergency_contact_relationship">Parentesco / relação</label>
                            <input id="emergency_contact_relationship" type="text" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $user->emergency_contact_relationship) }}" maxlength="80" placeholder="Ex.: mãe, cônjuge" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base dark:text-white">
                            @error('emergency_contact_relationship')<p class="text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-8 md:p-10 shadow-sm">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-8 flex items-center gap-3">
                        <x-icon name="shield-halved" style="duotone" class="w-5 h-5 text-rose-500 shrink-0" />
                        Palavra-passe
                    </h3>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="current_password">Palavra-passe atual</label>
                            <input type="password" id="current_password" name="current_password" autocomplete="current-password" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 dark:text-white transition-all">
                            @error('current_password')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="password">Nova palavra-passe</label>
                                <input type="password" id="password" name="password" autocomplete="new-password" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 dark:text-white transition-all">
                                @error('password')
                                    <p class="text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-600 dark:text-slate-400 mb-1" for="password_confirmation">Confirmar nova palavra-passe</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="w-full px-4 py-3.5 bg-gray-50 dark:bg-slate-950 border border-gray-200 dark:border-slate-800 rounded-xl text-base text-slate-900 focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500 dark:text-white transition-all">
                            </div>
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Deixa em branco para manter a palavra-passe atual. Se alterares, indica a palavra-passe atual.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-8">
                <div class="rounded-2xl border border-indigo-200/50 dark:border-indigo-900/40 bg-indigo-600 text-white p-8 md:p-10 shadow-xl shadow-indigo-600/20 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                        <x-icon name="signature" class="w-48 h-48" />
                    </div>
                    <h4 class="text-sm font-semibold opacity-95 mb-8">Resumo</h4>
                    <div class="space-y-6 relative">
                        <div>
                            <p class="text-xs font-medium opacity-80 mb-1">Membro desde</p>
                            <p class="text-xl font-bold">{{ $user->created_at->translatedFormat('M Y') }}</p>
                        </div>
                        <div class="pt-6 border-t border-white/10">
                            <p class="text-xs font-medium opacity-80 mb-1">Última atualização</p>
                            <p class="text-sm font-semibold">{{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                        @if(module_enabled('Igrejas') && Route::has('lideres.congregacao.index'))
                        <div class="pt-6 border-t border-white/10">
                            <a href="{{ route('lideres.congregacao.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-white/95 hover:text-white underline decoration-white/30 hover:decoration-white">
                                <x-icon name="building-columns" class="w-4 h-4" style="duotone" />
                                Área da congregação
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/80 p-8">
                    <div class="flex flex-col gap-4">
                        <button type="submit" class="h-14 bg-emerald-600 text-white rounded-2xl text-base font-semibold hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-500/20 active:scale-95 flex items-center justify-center gap-3">
                            <x-icon name="check-to-slot" style="duotone" class="w-5 h-5" />
                            Guardar alterações
                        </button>
                        <a href="{{ route('lideres.dashboard') }}" class="h-14 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl text-base font-semibold hover:text-rose-600 dark:hover:text-rose-400 transition-all border border-gray-200 dark:border-slate-700 active:scale-95 flex items-center justify-center gap-3">
                            <x-icon name="xmark" class="w-4 h-4" />
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="mt-8 max-w-6xl mx-auto px-0">
        <x-profile-sensitive-data-request :action="route('lideres.profile.sensitive-data-request.store')" accent="emerald" />
    </div>
</x-ui.lideres::page-shell>

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
    });
</script>
@endpush
@endsection
