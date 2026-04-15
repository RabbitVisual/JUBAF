@if(session('success'))
    <div class="mb-4 md:mb-6 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 p-4 shadow-sm" data-flash>
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 flex-shrink-0 text-emerald-600 dark:text-emerald-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="flex-1 text-sm font-medium text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
            <button type="button" onclick="this.closest('[data-flash]')?.remove()" class="flex-shrink-0 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200" aria-label="Fechar">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 md:mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 shadow-sm" data-flash>
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p class="flex-1 text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            <button type="button" onclick="this.closest('[data-flash]').remove()" class="flex-shrink-0 text-red-600 dark:text-red-400" aria-label="Fechar">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 md:mb-6 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 p-4 shadow-sm" data-flash>
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 flex-shrink-0 text-amber-600 dark:text-amber-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <p class="flex-1 text-sm font-medium text-amber-800 dark:text-amber-200">{!! session('warning') !!}</p>
            <button type="button" onclick="this.closest('[data-flash]').remove()" class="flex-shrink-0 text-amber-600 dark:text-amber-400" aria-label="Fechar">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif

@if(isset($errors) && is_object($errors) && $errors->any())
    <div class="mb-4 md:mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4 shadow-sm" data-flash>
        <div class="flex items-start gap-3">
            <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">Erros encontrados:</h3>
                <ul class="space-y-1 text-sm text-red-700 dark:text-red-300">
                    @foreach($errors->all() as $error)
                        <li class="flex items-start gap-2"><span class="text-red-500 mt-0.5">•</span><span>{{ $error }}</span></li>
                    @endforeach
                </ul>
            </div>
            <button type="button" onclick="this.closest('[data-flash]').remove()" class="flex-shrink-0 text-red-600 dark:text-red-400" aria-label="Fechar">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif
