{{--
    Botões para confirmar/recusar convite (só quando status = invited).
    @var \Modules\Talentos\App\Models\TalentAssignment $a
    @var string $routePrefix ex.: jovens.talentos
    @var string $panel jovens|lider
--}}
@php
    use Modules\Talentos\App\Models\TalentAssignment;
    $accentConfirm =
        ($panel ?? 'jovens') === 'lider'
            ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/25'
            : 'bg-violet-600 hover:bg-violet-700 shadow-violet-600/25';
    $accentDecline =
        'border-gray-300 bg-white text-gray-800 hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-800 dark:text-white dark:hover:bg-slate-700';
@endphp
@if ($a->status === TalentAssignment::STATUS_INVITED)
    <div class="mt-3 flex flex-wrap gap-2 border-t border-gray-200/80 pt-3 dark:border-slate-600/80">
        <form method="post" action="{{ route($routePrefix . '.assignments.respond', $a) }}" class="inline">
            @csrf
            <input type="hidden" name="status" value="{{ TalentAssignment::STATUS_CONFIRMED }}">
            <button type="submit"
                class="inline-flex items-center justify-center gap-1.5 rounded-xl px-4 py-2 text-xs font-bold text-white shadow-md transition {{ $accentConfirm }}">
                <x-icon name="circle-check" class="h-3.5 w-3.5" style="solid" />
                Confirmar disponibilidade
            </button>
        </form>
        <form method="post" action="{{ route($routePrefix . '.assignments.respond', $a) }}" class="inline">
            @csrf
            <input type="hidden" name="status" value="{{ TalentAssignment::STATUS_DECLINED }}">
            <button type="submit"
                class="inline-flex items-center justify-center gap-1.5 rounded-xl border px-4 py-2 text-xs font-bold transition {{ $accentDecline }}">
                <x-icon name="circle-xmark" class="h-3.5 w-3.5" style="duotone" />
                Não posso nesta data
            </button>
        </form>
    </div>
@endif
