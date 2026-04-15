@php
    $tc = $event->resolvedThemeConfig();
@endphp
<article class="mt-8 overflow-hidden rounded-[2rem] border border-slate-200/80 bg-gradient-to-br from-white to-slate-50 shadow-2xl dark:border-slate-700 dark:from-slate-900 dark:to-slate-950">
    <div class="px-8 py-12 md:px-12" style="border-left: 6px solid {{ $tc['primary_color'] }}">
        @if(!empty($isPreview))
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900">Pré-visualização</span>
        @endif
        @if($event->cover_path)
            <div class="mb-6 overflow-hidden rounded-2xl border border-slate-200/80">
                <img src="{{ asset('storage/'.$event->cover_path) }}" alt="" class="max-h-72 w-full object-cover">
            </div>
        @endif
        <h1 class="mt-4 text-3xl font-black tracking-tight text-slate-900 dark:text-white md:text-4xl">{{ $event->title }}</h1>
        <div class="mt-6 space-y-6">
            @include('calendario::public.partials.event-detail', ['event' => $event])
        </div>
    </div>
</article>
