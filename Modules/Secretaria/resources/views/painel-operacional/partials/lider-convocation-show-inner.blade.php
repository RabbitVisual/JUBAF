<div class="space-y-6 md:space-y-8">
    <a href="{{ route($namePrefix.'.convocatorias.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-all hover:gap-2.5 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">
        <x-icon name="arrow-left" class="h-4 w-4" style="duotone" />
        Voltar às convocatórias
    </a>

    <article class="overflow-hidden rounded-[2rem] border border-gray-200/90 bg-white shadow-xl dark:border-gray-800 dark:bg-gray-900">
        <div class="relative overflow-hidden border-b border-white/10 bg-gradient-to-br from-emerald-700 via-emerald-800 to-slate-900 px-6 py-10 text-white md:px-10 md:py-12">
            <div class="pointer-events-none absolute inset-0 opacity-[0.12]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-wider text-emerald-100/90">{{ $convocation->assembly_at->format('d/m/Y · H:i') }}</p>
                <h1 class="mt-3 text-2xl font-bold leading-tight tracking-tight md:text-3xl">{{ $convocation->title }}</h1>
            </div>
        </div>
        <div class="prose prose-gray max-w-none px-6 py-8 dark:prose-invert md:px-10 md:py-10">{!! $convocation->body !!}</div>
    </article>
</div>