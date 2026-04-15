@php
    $fieldClass = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-cyan-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-cyan-400';
    $leadershipUsers = $leadershipUsers ?? collect();
    $parentChurches = $parentChurches ?? collect();
    $jubafSectors = $jubafSectors ?? collect();
@endphp
<div class="space-y-5">
    <div class="rounded-xl border border-cyan-200/80 bg-cyan-50/40 p-4 dark:border-cyan-900/40 dark:bg-cyan-950/20">
        <p class="text-sm font-semibold text-gray-900 dark:text-white">Tipo de registo</p>
        <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Sede (igreja) exige CNPJ. Congregação não tem CNPJ e pode ligar-se opcionalmente a uma sede.</p>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="kind" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Tipo *</label>
                <select id="kind" name="kind" required class="{{ $fieldClass }}">
                    <option value="{{ \Modules\Igrejas\App\Models\Church::KIND_CHURCH }}" @selected(old('kind', $church->kind ?? \Modules\Igrejas\App\Models\Church::KIND_CHURCH) === \Modules\Igrejas\App\Models\Church::KIND_CHURCH)>Igreja (sede)</option>
                    <option value="{{ \Modules\Igrejas\App\Models\Church::KIND_CONGREGATION }}" @selected(old('kind', $church->kind ?? '') === \Modules\Igrejas\App\Models\Church::KIND_CONGREGATION)>Congregação</option>
                </select>
                @error('kind')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="parent_church_id" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Igreja mãe (sede)</label>
                <select id="parent_church_id" name="parent_church_id" class="{{ $fieldClass }}">
                    <option value="">— Nenhuma —</option>
                    @foreach($parentChurches as $p)
                        <option value="{{ $p->id }}" @selected((string) old('parent_church_id', $church->parent_church_id) === (string) $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
                @error('parent_church_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mt-4">
            <label for="cnpj" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">CNPJ (apenas sede)</label>
            <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj', $church->cnpj) }}" maxlength="18" inputmode="numeric" autocomplete="off" placeholder="14 dígitos"
                class="{{ $fieldClass }} font-mono">
            @error('cnpj')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <label for="logo" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Logótipo</label>
                <input type="file" id="logo" name="logo" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-cyan-600 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white dark:text-gray-300">
                @error('logo')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                @if($church->logo_path)
                    <p class="mt-2 text-xs text-gray-500">Atual: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($church->logo_path) }}" class="text-cyan-700 underline dark:text-cyan-400" target="_blank" rel="noopener">ver ficheiro</a></p>
                @endif
            </div>
            <div>
                <label for="cover" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Capa (imagem)</label>
                <input type="file" id="cover" name="cover" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-cyan-600 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white dark:text-gray-300">
                @error('cover')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                @if($church->cover_path)
                    <p class="mt-2 text-xs text-gray-500">Atual: <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($church->cover_path) }}" class="text-cyan-700 underline dark:text-cyan-400" target="_blank" rel="noopener">ver ficheiro</a></p>
                @endif
            </div>
        </div>
    </div>
    <div>
        <label for="name" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Nome *</label>
        <input type="text" id="name" name="name" value="{{ old('name', $church->name) }}" required class="{{ $fieldClass }}">
        @error('name')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="legal_name" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Razão social</label>
            <input type="text" id="legal_name" name="legal_name" value="{{ old('legal_name', $church->legal_name) }}" class="{{ $fieldClass }}" placeholder="Opcional — predefinido pelo nome">
            @error('legal_name')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="trade_name" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Nome fantasia</label>
            <input type="text" id="trade_name" name="trade_name" value="{{ old('trade_name', $church->trade_name) }}" class="{{ $fieldClass }}" placeholder="Opcional — exibição pública">
            @error('trade_name')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label for="slug" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Slug (URL)</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $church->slug) }}" class="{{ $fieldClass }} font-mono text-xs sm:text-sm" placeholder="gerado-automaticamente-se-vazio">
        @error('slug')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        @if($jubafSectors->isNotEmpty())
            <div>
                <label for="jubaf_sector_id" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Setor associacional (ERP)</label>
                <select id="jubaf_sector_id" name="jubaf_sector_id" class="{{ $fieldClass }}" @disabled(auth()->user()?->restrictsChurchDirectoryToSector())>
                    <option value="">— Selecionar —</option>
                    @foreach($jubafSectors as $s)
                        <option value="{{ $s->id }}" @selected((string) old('jubaf_sector_id', $church->jubaf_sector_id) === (string) $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('jubaf_sector_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">O nome curto «Setor» abaixo sincroniza com o setor escolhido.</p>
            </div>
        @endif
        <div>
            <label for="sector" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Setor (Encontro de Setores)</label>
            <input type="text" id="sector" name="sector" value="{{ old('sector', $church->sector) }}" class="{{ $fieldClass }}" placeholder="Ex.: Norte, Centro…">
            @error('sector')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="cooperation_status" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Cooperação</label>
            <select id="cooperation_status" name="cooperation_status" class="{{ $fieldClass }}">
                @foreach(\Modules\Igrejas\App\Models\Church::cooperationStatuses() as $st)
                    <option value="{{ $st }}" @selected(old('cooperation_status', $church->cooperation_status ?? 'ativa') === $st)>{{ $st }}</option>
                @endforeach
            </select>
            @error('cooperation_status')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="crm_status" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Situação CRM</label>
            <select id="crm_status" name="crm_status" class="{{ $fieldClass }}">
                @foreach(\Modules\Igrejas\App\Models\Church::crmStatuses() as $st)
                    <option value="{{ $st }}" @selected(old('crm_status', $church->crm_status ?? \Modules\Igrejas\App\Models\Church::CRM_ATIVA) === $st)>{{ $st }}</option>
                @endforeach
            </select>
            @error('crm_status')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">«Inadimplente» mantém a igreja ativa para contacto, com alerta financeiro.</p>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="foundation_date" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Data de fundação / aniversário</label>
            <input type="date" id="foundation_date" name="foundation_date" value="{{ old('foundation_date', $church->foundation_date?->format('Y-m-d')) }}" class="{{ $fieldClass }} sm:max-w-xs">
            @error('foundation_date')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div></div>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="pastor_user_id" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Pastor (utilizador)</label>
            <select id="pastor_user_id" name="pastor_user_id" class="{{ $fieldClass }}">
                <option value="">—</option>
                @foreach($leadershipUsers as $u)
                    <option value="{{ $u->id }}" @selected((string) old('pastor_user_id', $church->pastor_user_id) === (string) $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            @error('pastor_user_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="unijovem_leader_user_id" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Líder Unijovem (utilizador)</label>
            <select id="unijovem_leader_user_id" name="unijovem_leader_user_id" class="{{ $fieldClass }}">
                <option value="">—</option>
                @foreach($leadershipUsers as $u)
                    <option value="{{ $u->id }}" @selected((string) old('unijovem_leader_user_id', $church->unijovem_leader_user_id) === (string) $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
            @error('unijovem_leader_user_id')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="rounded-xl border border-gray-200/80 p-4 dark:border-slate-700" x-data="{
        cepUrl: {{ json_encode(route('diretoria.igrejas.cep')) }},
        cepLoading: false,
        cepErr: '',
        async buscarCep() {
            this.cepErr = '';
            const el = document.getElementById('postal_code');
            if (!el) return;
            const cep = (el.value || '').replace(/\D/g, '');
            if (cep.length !== 8) { this.cepErr = 'Informe 8 dígitos.'; return; }
            this.cepLoading = true;
            try {
                const u = new URL(this.cepUrl, window.location.origin);
                u.searchParams.set('cep', cep);
                const r = await fetch(u.toString(), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const j = await r.json();
                if (!r.ok) throw new Error(j.message || 'CEP não encontrado');
                if (j.street) document.getElementById('street').value = j.street;
                if (j.district) document.getElementById('district').value = j.district;
                if (j.city) document.getElementById('city').value = j.city;
                if (j.state) document.getElementById('state').value = j.state;
            } catch (e) {
                this.cepErr = e.message || 'Falha ao consultar CEP.';
            } finally {
                this.cepLoading = false;
            }
        }
    }">
        <p class="text-sm font-semibold text-gray-900 dark:text-white">Endereço (CEP Brasil)</p>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Preencha o CEP e use «Buscar» (ViaCEP). Campos estruturados alimentam relatórios e integrações.</p>
        <div class="mt-4 flex flex-wrap gap-3">
            <div class="min-w-[10rem] flex-1">
                <label for="postal_code" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">CEP</label>
                <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $church->postal_code) }}" maxlength="12" inputmode="numeric" class="{{ $fieldClass }} font-mono" placeholder="00000000">
                @error('postal_code')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end">
                <button type="button" @click="buscarCep()" :disabled="cepLoading" class="inline-flex items-center rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-900 disabled:opacity-50 dark:bg-slate-600 dark:hover:bg-slate-500">
                    <span x-show="!cepLoading">Buscar CEP</span>
                    <span x-show="cepLoading" x-cloak>A buscar…</span>
                </button>
            </div>
        </div>
        <p class="mt-2 text-xs text-red-600 dark:text-red-400" x-text="cepErr" x-show="cepErr"></p>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="street" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Logradouro</label>
                <input type="text" id="street" name="street" value="{{ old('street', $church->street) }}" class="{{ $fieldClass }}">
                @error('street')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="number" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Número</label>
                <input type="text" id="number" name="number" value="{{ old('number', $church->number) }}" class="{{ $fieldClass }}">
                @error('number')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="complement" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Complemento</label>
                <input type="text" id="complement" name="complement" value="{{ old('complement', $church->complement) }}" class="{{ $fieldClass }}">
                @error('complement')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="district" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Bairro</label>
                <input type="text" id="district" name="district" value="{{ old('district', $church->district) }}" class="{{ $fieldClass }}">
                @error('district')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="city" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Cidade</label>
                <input type="text" id="city" name="city" value="{{ old('city', $church->city) }}" class="{{ $fieldClass }}">
                @error('city')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="state" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">UF</label>
                <input type="text" id="state" name="state" value="{{ old('state', $church->state) }}" maxlength="8" class="{{ $fieldClass }} uppercase">
                @error('state')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="country" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">País</label>
                <input type="text" id="country" name="country" value="{{ old('country', $church->country ?? 'BR') }}" maxlength="8" class="{{ $fieldClass }} uppercase">
                @error('country')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="phone" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Telefone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $church->phone) }}" class="{{ $fieldClass }}">
            @error('phone')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
        </div>
        <div></div>
    </div>
    <div>
        <label for="address" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Endereço (texto livre / legado)</label>
        <input type="text" id="address" name="address" value="{{ old('address', $church->address) }}" class="{{ $fieldClass }}">
        @error('address')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="email" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">E-mail institucional</label>
        <input type="email" id="email" name="email" value="{{ old('email', $church->email) }}" class="{{ $fieldClass }}">
        @error('email')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="asbaf_notes" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Notas ASBAF / filiação</label>
        <textarea id="asbaf_notes" name="asbaf_notes" rows="4" class="{{ $fieldClass }}">{{ old('asbaf_notes', $church->asbaf_notes) }}</textarea>
        @error('asbaf_notes')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="joined_at" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Data de filiação JUBAF</label>
        <input type="date" id="joined_at" name="joined_at" value="{{ old('joined_at', $church->joined_at?->format('Y-m-d')) }}" class="{{ $fieldClass }} sm:max-w-xs">
        @error('joined_at')<p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>@enderror
    </div>
    @if(auth()->user()->can('igrejas.activate'))
        <div class="rounded-xl border border-cyan-200/80 bg-cyan-50/50 p-4 dark:border-cyan-900/40 dark:bg-cyan-950/20">
            <div class="flex items-start gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" class="mt-1 h-4 w-4 rounded border-gray-300 text-cyan-600 focus:ring-cyan-500 dark:border-slate-600 dark:bg-slate-900"
                    @checked(old('is_active', $church->is_active ?? true))>
                <div>
                    <label for="is_active" class="text-sm font-semibold text-gray-900 dark:text-white">Congregação ativa na JUBAF</label>
                    <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Inativas deixam de aparecer em fluxos públicos e podem ser ocultadas em relatórios.</p>
                </div>
            </div>
        </div>
    @endif
</div>
