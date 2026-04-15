@php
    use Modules\Talentos\App\Models\TalentAssignment;
    $fieldClass =
        'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500/25 dark:border-slate-600 dark:bg-slate-900 dark:text-white dark:focus:border-violet-400';
@endphp

<div class="space-y-5">
    <div>
        <label for="user_id" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Membro</label>
        <select id="user_id" name="user_id" required class="{{ $fieldClass }}">
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('user_id', $assignment->user_id) == $u->id)>{{ $u->name }}
                    ({{ $u->email }})</option>
            @endforeach
        </select>
        @error('user_id')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="calendar_event_id" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Evento
            (opcional)</label>
        <select id="calendar_event_id" name="calendar_event_id" class="{{ $fieldClass }}">
            <option value="">— Sem evento —</option>
            @foreach ($events as $ev)
                <option value="{{ $ev->id }}" @selected(old('calendar_event_id', $assignment->calendar_event_id) == $ev->id)>{{ $ev->title }} ·
                    {{ $ev->starts_at?->format('d/m/Y H:i') }}</option>
            @endforeach
        </select>
        @error('calendar_event_id')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
        @if ($events->isEmpty())
            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Sem eventos listados (módulo Calendário inativo
                ou sem datas futuras).</p>
        @endif
    </div>

    <div>
        <label for="role_label" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Função /
            papel</label>
        <input type="text" id="role_label" name="role_label"
            value="{{ old('role_label', $assignment->role_label) }}" required class="{{ $fieldClass }}"
            placeholder="Ex.: Música — violão; Receção; Som">
        @error('role_label')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Estado</label>
        <select id="status" name="status" required class="{{ $fieldClass }}">
            <option value="{{ TalentAssignment::STATUS_INVITED }}" @selected(old('status', $assignment->status) === TalentAssignment::STATUS_INVITED)>Convidado</option>
            <option value="{{ TalentAssignment::STATUS_CONFIRMED }}" @selected(old('status', $assignment->status) === TalentAssignment::STATUS_CONFIRMED)>Confirmado</option>
            <option value="{{ TalentAssignment::STATUS_DECLINED }}" @selected(old('status', $assignment->status) === TalentAssignment::STATUS_DECLINED)>Declinou</option>
        </select>
        @error('status')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="notes" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Notas
            (opcional)</label>
        <textarea id="notes" name="notes" rows="4" class="{{ $fieldClass }}"
            placeholder="Instruções ou contexto visível à equipe interna.">{{ old('notes', $assignment->notes) }}</textarea>
        @error('notes')
            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
</div>
