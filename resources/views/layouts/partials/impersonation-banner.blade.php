@if(session()->has('impersonator_id'))
    <div class="bg-amber-500 text-white py-2 px-4 shadow-md sticky top-0 z-[100] border-b border-amber-600">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-1 bg-white/20 rounded-lg">
                    <x-icon name="eye" class="w-5 h-5" />
                </div>
                <div class="text-sm font-bold tracking-tight">
                    MODO VISUALIZAÇÃO: <span class="font-black uppercase">{{ Auth::user()->name }}</span>
                </div>
            </div>
            <a href="{{ route('admin.stop-impersonation') }}" class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-black bg-white text-amber-600 rounded-lg hover:bg-amber-50 transition-all shadow-sm transform hover:scale-105 active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                VOLTAR AO ADMIN
            </a>
        </div>
    </div>
@endif
