@php
    $minute = $minute ?? null;
@endphp
<div class="space-y-4">
    <div>
        <label for="title" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
        <input type="text" name="title" id="title" required value="{{ old('title', $minute?->title) }}"
            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white sm:text-sm" />
        @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="meeting_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Data da reunião</label>
        <input type="date" name="meeting_date" id="meeting_date" required value="{{ old('meeting_date', $minute?->meeting_date?->format('Y-m-d')) }}"
            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white sm:text-sm" />
        @error('meeting_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="tag" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Etiqueta</label>
        <select name="tag" id="tag" required
            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white sm:text-sm">
            @foreach($tagLabels as $slug => $label)
                <option value="{{ $slug }}" @selected(old('tag', $minute?->tag) === $slug)>{{ $label }}</option>
            @endforeach
        </select>
        @error('tag')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="pdf" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            Ficheiro PDF @if($minute)<span class="font-normal text-gray-500">(opcional — substitui o atual)</span>@endif
        </label>
        <input type="file" name="pdf" id="pdf" accept="application/pdf" @if(!$minute) required @endif
            class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100 dark:text-gray-400 dark:file:bg-slate-800 dark:file:text-blue-300" />
        @error('pdf')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Notas internas (opcional)</label>
        <textarea name="notes" id="notes" rows="3"
            class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-white sm:text-sm">{{ old('notes', $minute?->notes) }}</textarea>
        @error('notes')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
