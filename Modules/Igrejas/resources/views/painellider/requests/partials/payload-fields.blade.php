@php
    use Modules\Igrejas\App\Models\Church;
    use Modules\Igrejas\App\Models\ChurchChangeRequest;

    $formMode = $formMode ?? 'create';
    $changeRequest = $changeRequest ?? null;
    $isCreateForm = $formMode === 'create';

    $initialType = $isCreateForm
        ? old('type', ChurchChangeRequest::TYPE_CREATE)
        : ($changeRequest?->type ?? ChurchChangeRequest::TYPE_CREATE);

    $p = old('payload', $changeRequest?->payload ?? []);

    $typeLabels = [
        ChurchChangeRequest::TYPE_CREATE => 'Nova congregação',
        ChurchChangeRequest::TYPE_UPDATE_PROFILE => 'Atualizar ficha institucional',
        ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE => 'Alteração de liderança (pastor / Unijovem)',
        ChurchChangeRequest::TYPE_DEACTIVATE => 'Desactivar congregação',
    ];

    $fieldClass = 'w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400';
    $labelClass = 'mb-1 block text-sm font-semibold text-slate-800 dark:text-slate-200';

    $pf = function (string $key, string $default = '') use ($p): string {
        $v = old('payload.'.$key, $p[$key] ?? $default);
        if ($v === null) {
            return $default;
        }
        if (in_array($key, ['foundation_date', 'joined_at'], true)) {
            if ($v instanceof \Carbon\CarbonInterface) {
                return $v->format('Y-m-d');
            }
            if (is_string($v) && strlen($v) >= 10) {
                return substr($v, 0, 10);
            }
        }

        return is_scalar($v) ? (string) $v : $default;
    };
@endphp

<div class="space-y-6" x-data="{ requestType: @js($initialType) }">
    @if($isCreateForm)
        <div>
            <label class="{{ $labelClass }}">Tipo de pedido</label>
            <select name="type" x-model="requestType" required class="{{ $fieldClass }}">
                @foreach($types as $t)
                    <option value="{{ $t }}">{{ $typeLabels[$t] ?? $t }}</option>
                @endforeach
            </select>
            @error('type')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div x-show="requestType !== @js(ChurchChangeRequest::TYPE_CREATE)" x-cloak>
            <label class="{{ $labelClass }}">Congregação</label>
            <select
                name="church_id"
                class="{{ $fieldClass }}"
                :required="requestType !== @js(ChurchChangeRequest::TYPE_CREATE)"
                :disabled="requestType === @js(ChurchChangeRequest::TYPE_CREATE)"
            >
                <option value="">— Escolher —</option>
                @foreach($churches as $c)
                    <option value="{{ $c->id }}" @selected((string) old('church_id') === (string) $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Obrigatório para pedidos sobre uma congregação existente.</p>
            @error('church_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
    @else
        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm dark:border-slate-600 dark:bg-slate-900/50">
            <p class="font-semibold text-slate-800 dark:text-slate-100">{{ $typeLabels[$changeRequest->type] ?? $changeRequest->type }}</p>
            @if($changeRequest->church)
                <p class="mt-1 text-slate-600 dark:text-slate-300">{{ $changeRequest->church->name }}</p>
            @endif
        </div>
    @endif

    {{-- Nova congregação / ficha (fieldset desactivado evita enviar campos ocultos) --}}
    <fieldset
        class="min-w-0 space-y-4 border-0 p-0 m-0"
        x-show="requestType === @js(ChurchChangeRequest::TYPE_CREATE) || requestType === @js(ChurchChangeRequest::TYPE_UPDATE_PROFILE)"
        x-cloak
        :disabled="!(requestType === @js(ChurchChangeRequest::TYPE_CREATE) || requestType === @js(ChurchChangeRequest::TYPE_UPDATE_PROFILE))"
    >
        <div>
            <label class="{{ $labelClass }}"><span x-text="requestType === @js(ChurchChangeRequest::TYPE_CREATE) ? 'Nome da congregação *' : 'Nome'"></span></label>
            <input type="text" name="payload[name]" value="{{ $pf('name') }}" class="{{ $fieldClass }}" :required="requestType === @js(ChurchChangeRequest::TYPE_CREATE)">
            @error('payload.name')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}">Setor</label>
                <input type="text" name="payload[sector]" value="{{ $pf('sector') }}" class="{{ $fieldClass }}" placeholder="Ex.: Norte, Centro…">
                @error('payload.sector')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Cooperação</label>
                <select name="payload[cooperation_status]" class="{{ $fieldClass }}">
                    <option value="">—</option>
                    @foreach(Church::cooperationStatuses() as $st)
                        <option value="{{ $st }}" @selected($pf('cooperation_status', Church::COOPERATION_ATIVA) === $st)>{{ $st }}</option>
                    @endforeach
                </select>
                @error('payload.cooperation_status')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}">Data de fundação</label>
                <input type="date" name="payload[foundation_date]" value="{{ $pf('foundation_date') }}" class="{{ $fieldClass }} sm:max-w-xs">
                @error('payload.foundation_date')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Data de entrada JUB</label>
                <input type="date" name="payload[joined_at]" value="{{ $pf('joined_at') }}" class="{{ $fieldClass }} sm:max-w-xs">
                @error('payload.joined_at')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}">Cidade</label>
                <input type="text" name="payload[city]" value="{{ $pf('city') }}" class="{{ $fieldClass }}">
                @error('payload.city')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Telefone</label>
                <input type="text" name="payload[phone]" value="{{ $pf('phone') }}" class="{{ $fieldClass }}">
                @error('payload.phone')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label class="{{ $labelClass }}">Endereço</label>
            <input type="text" name="payload[address]" value="{{ $pf('address') }}" class="{{ $fieldClass }}">
            @error('payload.address')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="{{ $labelClass }}">E-mail institucional</label>
            <input type="email" name="payload[email]" value="{{ $pf('email') }}" class="{{ $fieldClass }}">
            @error('payload.email')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="{{ $labelClass }}">Notas ASBAF / registo</label>
            <textarea name="payload[asbaf_notes]" rows="3" class="{{ $fieldClass }}">{{ $pf('asbaf_notes') }}</textarea>
            @error('payload.asbaf_notes')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
    </fieldset>

    {{-- Liderança --}}
    <fieldset
        class="min-w-0 space-y-4 border-0 p-0 m-0"
        x-show="requestType === @js(ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE)"
        x-cloak
        :disabled="requestType !== @js(ChurchChangeRequest::TYPE_LEADERSHIP_CHANGE)"
    >
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="{{ $labelClass }}">Pastor (utilizador)</label>
                <select name="payload[pastor_user_id]" class="{{ $fieldClass }}">
                    <option value="">— Sem alteração / vazio —</option>
                    @foreach($leadershipUsers as $u)
                        <option value="{{ $u->id }}" @selected((string) $pf('pastor_user_id') === (string) $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
                @error('payload.pastor_user_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="{{ $labelClass }}">Líder Unijovem</label>
                <select name="payload[unijovem_leader_user_id]" class="{{ $fieldClass }}">
                    <option value="">— Sem alteração / vazio —</option>
                    @foreach($leadershipUsers as $u)
                        <option value="{{ $u->id }}" @selected((string) $pf('unijovem_leader_user_id') === (string) $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
                @error('payload.unijovem_leader_user_id')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400">Lista limitada a membros das congregações onde tem função de liderança. Indique pelo menos um dos dois cargos.</p>
    </fieldset>

    {{-- Desactivar --}}
    <fieldset
        class="min-w-0 space-y-4 border-0 p-0 m-0"
        x-show="requestType === @js(ChurchChangeRequest::TYPE_DEACTIVATE)"
        x-cloak
        :disabled="requestType !== @js(ChurchChangeRequest::TYPE_DEACTIVATE)"
    >
        <p class="text-sm text-slate-600 dark:text-slate-300">A aprovação pela diretoria desactiva a congregação na plataforma. Opcionalmente explique o motivo (fica no pedido).</p>
        <div>
            <label class="{{ $labelClass }}">Motivo (opcional)</label>
            <textarea name="payload[reason]" rows="3" class="{{ $fieldClass }}">{{ $pf('reason') }}</textarea>
            @error('payload.reason')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
    </fieldset>

    <div>
        <label class="{{ $labelClass }}">Notas para a diretoria (opcional)</label>
        <textarea name="payload[leader_notes]" rows="2" class="{{ $fieldClass }}" placeholder="Contexto que ajude a analisar o pedido…">{{ $pf('leader_notes') }}</textarea>
        @error('payload.leader_notes')<p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>

    @error('payload')<p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
</div>
