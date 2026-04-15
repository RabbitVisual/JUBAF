{{--
    Formulário partilhado: inscrição no banco de talentos (jovens / líderes).
    @var \Modules\Talentos\App\Models\TalentProfile $profile
    @var \Illuminate\Support\Collection $skills
    @var \Illuminate\Support\Collection $areas
    @var string $routePrefix ex.: jovens.talentos
    @var string $panel jovens|lider — acento visual do painel de origem
--}}
@php
    $panel = $panel ?? 'jovens';
    $formAccent =
        $panel === 'lider'
            ? 'border-emerald-200/70 ring-emerald-500/10 dark:border-emerald-900/40'
            : 'border-violet-200/70 ring-violet-500/10 dark:border-violet-900/40';
    $inputClass =
        $panel === 'lider'
            ? 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-emerald-400'
            : 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-violet-400';
    $consentBox =
        $panel === 'lider'
            ? 'rounded-xl border border-emerald-200/80 bg-emerald-50/50 p-4 dark:border-emerald-900/40 dark:bg-emerald-950/20'
            : 'rounded-xl border border-violet-200/80 bg-violet-50/50 p-4 dark:border-violet-900/40 dark:bg-violet-950/20';
    $submitClass =
        $panel === 'lider'
            ? 'inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700'
            : 'inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-violet-700';
@endphp
<form method="post" action="{{ route($routePrefix . '.profile.update') }}"
    class="space-y-6 rounded-2xl border bg-white p-6 shadow-sm ring-1 dark:bg-slate-800 {{ $formAccent }}">
    @csrf
    @method('PUT')

    <div>
        <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apresentação
            breve</label>
        <textarea id="bio" name="bio" rows="4" class="{{ $inputClass }}"
            placeholder="Quem é você na JUBAF? O que gosta de fazer em equipe?">{{ old('bio', $profile->bio) }}</textarea>
        @error('bio')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="availability_text"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Disponibilidade para servir</label>
        <input type="text" id="availability_text" name="availability_text"
            value="{{ old('availability_text', $profile->availability_text) }}"
            placeholder="Ex.: fins de semana, vésperas de eventos, férias…" class="{{ $inputClass }}">
        @error('availability_text')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="{{ $consentBox }}">
        <div class="flex items-start gap-3">
            <input type="hidden" name="is_searchable" value="0">
            <input type="checkbox" id="is_searchable" name="is_searchable" value="1"
                class="mt-1 rounded border-gray-300 dark:border-slate-600" @checked(old('is_searchable', $profile->is_searchable))>
            <div>
                <label for="is_searchable" class="text-sm font-semibold text-gray-900 dark:text-white">Quero integrar o
                    banco de talentos da JUBAF</label>
                <p class="mt-1 text-xs text-gray-600 dark:text-gray-400">Se marcar esta opção, a diretoria poderá
                    encontrá-lo(a) no diretório interno e enviar convites para funções em cultos, eventos ou missões —
                    quando houver necessidade e disponibilidade.</p>
            </div>
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Competências que posso oferecer</p>
        <p class="mb-2 text-xs text-gray-500 dark:text-gray-400">Opcional: indique o nível em cada competência selecionada.</p>
        <div
            class="space-y-2 max-h-72 overflow-y-auto rounded-lg border border-gray-200 dark:border-slate-600 p-3">
            @foreach ($skills as $skill)
                @php
                    $oldLevels = old('skill_levels', []);
                    $pivotLevel = $profile->exists ? $profile->skills->firstWhere('id', $skill->id)?->pivot?->level : null;
                    $levelVal = $oldLevels[$skill->id] ?? $oldLevels[(string) $skill->id] ?? $pivotLevel;
                @endphp
                <div class="flex flex-col gap-2 rounded-lg bg-gray-50/80 px-2 py-2 sm:flex-row sm:items-center sm:justify-between dark:bg-slate-900/40">
                    <label class="flex min-w-0 flex-1 items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="skill_ids[]" value="{{ $skill->id }}" @checked(in_array($skill->id, old('skill_ids', $profile->exists ? $profile->skills->pluck('id')->all() : []), true))>
                        <span class="truncate">{{ $skill->name }}</span>
                    </label>
                    <select name="skill_levels[{{ $skill->id }}]" class="{{ $inputClass }} sm:max-w-[11rem] shrink-0 text-xs sm:text-sm">
                        <option value="">— Nível —</option>
                        @foreach (\Modules\Talentos\App\Models\TalentSkill::levelOptions() as $val => $label)
                            <option value="{{ $val }}" @selected($levelVal === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
        </div>
        @error('skill_ids')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('skill_levels')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Áreas onde quero servir</p>
        <div
            class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto rounded-lg border border-gray-200 dark:border-slate-600 p-3">
            @foreach ($areas as $area)
                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="area_ids[]" value="{{ $area->id }}" @checked(in_array($area->id, old('area_ids', $profile->exists ? $profile->areas->pluck('id')->all() : []), true))>
                    {{ $area->name }}
                </label>
            @endforeach
        </div>
        @error('area_ids')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-2 border-t border-gray-100 dark:border-slate-700">
        <p class="text-xs text-gray-500 dark:text-gray-400">Ao guardar, confirma que os dados são verdadeiros e autoriza
            o contacto institucional para convites de serviço.</p>
        <button type="submit" class="{{ $submitClass }}">
            <x-icon name="circle-check" class="w-4 h-4" style="duotone" />
            Guardar inscrição
        </button>
    </div>
</form>
