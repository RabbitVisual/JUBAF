{{--
    Inclui o seu próprio <form> POST. Não coloque este componente dentro de outro <form>
    (ex.: formulário do perfil com capa/fotos) — o HTML não permite formulários aninhados e o browser
    parte o envio (capa, palavra-passe, etc.).
--}}
@props([
    'action',
    'accent' => 'violet',
])

@php
    use App\Models\ProfileSensitiveDataRequest;
    $pendingEmail = auth()->user()->hasPendingProfileSensitiveRequest(ProfileSensitiveDataRequest::FIELD_EMAIL);
    $pendingCpf = auth()->user()->hasPendingProfileSensitiveRequest(ProfileSensitiveDataRequest::FIELD_CPF);
    $defaultField = ProfileSensitiveDataRequest::FIELD_EMAIL;
    if ($pendingEmail && ! $pendingCpf) {
        $defaultField = ProfileSensitiveDataRequest::FIELD_CPF;
    } elseif ($pendingCpf && ! $pendingEmail) {
        $defaultField = ProfileSensitiveDataRequest::FIELD_EMAIL;
    }
    $selectedField = old('field', $defaultField);
    $ring = match ($accent) {
        'emerald' => 'ring-emerald-500/20 border-emerald-200/80 dark:border-emerald-900/40',
        default => 'ring-violet-500/20 border-violet-200/80 dark:border-violet-900/40',
    };
    $btn = match ($accent) {
        'emerald' => 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20',
        default => 'bg-violet-600 hover:bg-violet-700 shadow-violet-500/20',
    };
@endphp

<div class="rounded-2xl border {{ $ring }} bg-white dark:bg-slate-900 p-6 md:p-8 shadow-sm ring-1">
    <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-2 flex items-center gap-2">
        <x-icon name="paper-plane" style="duotone" class="w-5 h-5 text-amber-500 shrink-0" />
        Alteração de e-mail ou CPF
    </h3>
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
        Estes dados só podem ser alterados pela <strong class="text-slate-700 dark:text-slate-300">diretoria JUBAF</strong> após análise. Envie um pedido com o novo valor; receberá atualização quando for aprovado.
    </p>

    @if($pendingEmail || $pendingCpf)
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-100">
            <p class="font-semibold mb-1">Pedido em análise</p>
            <ul class="list-disc list-inside text-xs space-y-0.5 opacity-95">
                @if($pendingEmail)<li>E-mail</li>@endif
                @if($pendingCpf)<li>CPF</li>@endif
            </ul>
        </div>
    @endif

    @if($pendingEmail && $pendingCpf)
        {{-- ambos pendentes: sem opções no select --}}
    @else
    <form method="POST" action="{{ $action }}" class="space-y-5">
        @csrf
        <div class="space-y-2">
            <label for="sensitive_field" class="block text-sm font-medium text-slate-600 dark:text-slate-400">Campo a alterar</label>
            <select name="field" id="sensitive_field" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-slate-900 dark:text-white text-sm">
                <option value="{{ ProfileSensitiveDataRequest::FIELD_EMAIL }}" @selected($selectedField === ProfileSensitiveDataRequest::FIELD_EMAIL) @disabled($pendingEmail)>E-mail de login @if($pendingEmail) (pedido pendente) @endif</option>
                <option value="{{ ProfileSensitiveDataRequest::FIELD_CPF }}" @selected($selectedField === ProfileSensitiveDataRequest::FIELD_CPF) @disabled($pendingCpf)>CPF @if($pendingCpf) (pedido pendente) @endif</option>
            </select>
        </div>
        <div class="space-y-2">
            <label for="requested_value" class="block text-sm font-medium text-slate-600 dark:text-slate-400">Novo valor</label>
            <input type="text" name="requested_value" id="requested_value" value="{{ old('requested_value') }}" required maxlength="255" autocomplete="off"
                class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-slate-900 dark:text-white text-sm"
                placeholder="Novo e-mail ou CPF (com ou sem pontuação)">
            @error('requested_value')
                <p class="text-xs text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>
        <div class="space-y-2">
            <label for="sensitive_reason" class="block text-sm font-medium text-slate-600 dark:text-slate-400">Motivo (opcional)</label>
            <textarea name="reason" id="sensitive_reason" rows="2" maxlength="500" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-gray-50 dark:bg-slate-950 text-slate-900 dark:text-white text-sm resize-y" placeholder="Ex.: correção de erro no cadastro">{{ old('reason') }}</textarea>
        </div>
        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-semibold text-white {{ $btn }} shadow-lg transition-all active:scale-[0.98]">
            <x-icon name="paper-plane" class="w-4 h-4" />
            Enviar pedido à diretoria
        </button>
    </form>
    @endif
</div>
