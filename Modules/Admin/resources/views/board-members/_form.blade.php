@php
    /** @var \App\Models\BoardMember $boardMember */
    $routePrefix = $routePrefix ?? 'admin.board-members';
    $isEdit = $boardMember->exists;
@endphp

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label for="full_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nome completo <span class="text-red-500">*</span></label>
            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $boardMember->full_name) }}" required maxlength="255"
                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" />
            @error('full_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="public_title" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cargo público <span class="text-red-500">*</span></label>
            <input type="text" name="public_title" id="public_title" value="{{ old('public_title', $boardMember->public_title) }}" required maxlength="255"
                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" placeholder="Ex.: Presidente" />
            @error('public_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="group_label" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Grupo / chapéu</label>
            <input type="text" name="group_label" id="group_label" value="{{ old('group_label', $boardMember->group_label) }}" maxlength="120"
                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" placeholder="Ex.: Mesa diretora" />
            @error('group_label')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="bio_short" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Bio curta</label>
            <textarea name="bio_short" id="bio_short" rows="4" maxlength="2000"
                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">{{ old('bio_short', $boardMember->bio_short) }}</textarea>
            @error('bio_short')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="city" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                <input type="text" name="city" id="city" value="{{ old('city', $boardMember->city) }}" maxlength="120"
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" />
                @error('city')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="state" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">UF</label>
                <input type="text" name="state" id="state" value="{{ old('state', $boardMember->state) }}" maxlength="8"
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" />
                @error('state')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label for="birth_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Data de nascimento</label>
            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $boardMember->birth_date?->format('Y-m-d')) }}"
                class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" />
            @error('birth_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="photo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Foto</label>
            <input type="file" name="photo" id="photo" accept="image/*"
                class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-white" />
            @error('photo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            @if($isEdit && $boardMember->photo_path)
                <p class="mt-2 text-xs text-gray-500">Atual: <a href="{{ $boardMember->photoUrl() }}" target="_blank" rel="noopener" class="text-blue-600 underline">ver imagem</a></p>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="sort_order" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Ordem</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $boardMember->sort_order ?? 0) }}" min="0" max="9999"
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" />
                @error('sort_order')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-end pb-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('is_active', $boardMember->is_active) ? 'checked' : '' }} />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ativo no site</span>
                </label>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="mandate_year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Gestão / mandato (texto)</label>
                <input type="text" name="mandate_year" id="mandate_year" value="{{ old('mandate_year', $boardMember->mandate_year) }}" maxlength="40"
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" placeholder="Ex.: 2026–2027" />
                @error('mandate_year')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="mandate_end" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Mandato até</label>
                <input type="date" name="mandate_end" id="mandate_end" value="{{ old('mandate_end', $boardMember->mandate_end?->format('Y-m-d')) }}"
                    class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500" />
                @error('mandate_end')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div>
            <label for="user_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Utilizador vinculado (opcional)</label>
            <select name="user_id" id="user_id" class="w-full rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-4 py-2.5 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                <option value="">— Nenhum —</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" @selected(old('user_id', $boardMember->user_id) == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
                @endforeach
            </select>
            @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
