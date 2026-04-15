@php
    $tc = $event->resolvedThemeConfig();
@endphp
<article class="mt-8 max-w-2xl rounded-2xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-700 dark:bg-slate-900">
    @if(!empty($isPreview))
        <p class="mb-4 text-xs font-bold uppercase text-amber-600">Pré-visualização</p>
    @endif
    @if($event->cover_path)
        <div class="mb-6 overflow-hidden rounded-xl border border-slate-200/80">
            <img src="{{ asset('storage/'.$event->cover_path) }}" alt="" class="max-h-56 w-full object-cover">
        </div>
    @endif
    <h1 class="text-3xl font-light tracking-tight text-slate-900 dark:text-white" style="color: {{ $tc['primary_color'] }}">{{ $event->title }}</h1>
    <div class="mt-6 space-y-5 text-slate-700 dark:text-slate-300">
        @include('calendario::public.partials.event-detail', ['event' => $event])
    </div>
</article>
