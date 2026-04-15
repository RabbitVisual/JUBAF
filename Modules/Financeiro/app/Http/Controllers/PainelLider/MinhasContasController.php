<?php

namespace Modules\Financeiro\App\Http\Controllers\PainelLider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Models\FinQuotaInvoice;

class MinhasContasController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->can('financeiro.minhas_contas.view'), 403);

        $churchIds = $request->user()->affiliatedChurchIds();

        $obligations = collect();
        $invoices = collect();

        if ($churchIds !== []) {
            $obligations = FinObligation::query()
                ->with('church')
                ->whereIn('church_id', $churchIds)
                ->orderByDesc('assoc_start_year')
                ->limit(30)
                ->get();

            $invoices = FinQuotaInvoice::query()
                ->with('church')
                ->whereIn('church_id', $churchIds)
                ->orderByDesc('billing_month')
                ->limit(36)
                ->get();
        }

        $checkoutHint = Route::has('gateway.public.checkout');

        return view('financeiro::painellider.minhas-contas', [
            'obligations' => $obligations,
            'invoices' => $invoices,
            'churchIds' => $churchIds,
            'checkoutHint' => $checkoutHint,
        ]);
    }
}
