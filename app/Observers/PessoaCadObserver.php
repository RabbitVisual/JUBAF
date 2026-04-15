<?php

namespace App\Observers;

use Modules\Pessoas\App\Models\PessoaCad;
use Illuminate\Support\Facades\Auth;

class PessoaCadObserver
{
    /**
     * Handle the PessoaCad "creating" event.
     */
    public function creating(PessoaCad $pessoaCad): void
    {
        if (Auth::check()) {
            $pessoaCad->created_by = Auth::id();
            $pessoaCad->updated_by = Auth::id();
        }
    }

    /**
     * Handle the PessoaCad "updating" event.
     */
    public function updating(PessoaCad $pessoaCad): void
    {
        if (Auth::check()) {
            $pessoaCad->updated_by = Auth::id();
        }
    }

    /**
     * Handle the PessoaCad "created" event.
     */
    public function created(PessoaCad $pessoaCad): void
    {
        // Log de auditoria pode ser implementado aqui
    }

    /**
     * Handle the PessoaCad "updated" event.
     */
    public function updated(PessoaCad $pessoaCad): void
    {
        // Log de auditoria pode ser implementado aqui
    }

    /**
     * Handle the PessoaCad "deleted" event.
     */
    public function deleted(PessoaCad $pessoaCad): void
    {
        // Log de auditoria pode ser implementado aqui
    }

    /**
     * Handle the PessoaCad "restored" event.
     */
    public function restored(PessoaCad $pessoaCad): void
    {
        // Log de auditoria pode ser implementado aqui
    }

    /**
     * Handle the PessoaCad "force deleted" event.
     */
    public function forceDeleted(PessoaCad $pessoaCad): void
    {
        // Log de auditoria pode ser implementado aqui
    }
}
