<div class="space-y-6 md:space-y-8">
    <a href="{{ route($namePrefix.'.atas.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-all hover:gap-2.5 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">
        <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
        Voltar às atas
    </a>

    <article class="overflow-hidden rounded-[2rem] border border-gray-200/90 bg-white shadow-xl dark:border-gray-800 dark:bg-gray-900">
        <div class="relative overflow-hidden border-b border-white/10 bg-gradient-to-br from-emerald-700 via-emerald-800 to-slate-900 px-6 py-10 text-white md:px-10 md:py-12">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative">
                @if ($minute->published_at)
                    <p class="text-xs font-bold uppercase tracking-wider text-emerald-100/90">Publicada em {{ $minute->published_at->format('d/m/Y') }}</p>
                @endif
                <h1 class="mt-3 text-2xl font-bold leading-tight tracking-tight md:text-3xl">{{ $minute->title }}</h1>
            </div>
        </div>
        <div class="prose prose-gray max-w-none px-6 py-8 dark:prose-invert md:px-10 md:py-10">{!! $minute->body !!}</div>
        @if ($minute->attachments->isNotEmpty())
            <div class="border-t border-gray-200 bg-gray-50/80 px-6 py-6 dark:border-gray-800 dark:bg-gray-900/50 md:px-10 md:py-8">
                <h2 class="text-sm font-bold uppercase tracking-wide text-gray-900 dark:text-white">Anexos</h2>
                <ul class="mt-3 space-y-3 text-sm">
                    @foreach ($minute->attachments as $att)
                        <li class="flex flex-col gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700 dark:bg-gray-800">
                            <span class="min-w-0 text-gray-800 dark:text-gray-200">{{ $att->original_name ?? 'Anexo' }} <span class="text-gray-500 dark:text-gray-400">({{ str_replace('_', ' ', $att->kind) }})</span></span>
                            <a href="{{ route($namePrefix.'.atas.attachments.download', [$minute, $att]) }}" class="inline-flex shrink-0 items-center gap-2 font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400">
                                <x-icon name="arrow-down-to-line" class="h-4 w-4" style="duotone" />
                                Descarregar
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </article>
</div>