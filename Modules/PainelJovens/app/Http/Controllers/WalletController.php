<?php

namespace Modules\PainelJovens\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Calendario\App\Models\CalendarRegistration;

class WalletController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(module_enabled('Calendario'), 404);
        abort_unless($request->user()?->can('calendario.participate'), 403);

        $user = $request->user();

        $registrations = CalendarRegistration::query()
            ->where('user_id', $user->id)
            ->where('status', '!=', CalendarRegistration::STATUS_CANCELLED)
            ->with([
                'event',
                'gatewayPayment',
            ])
            ->whereHas('event', fn ($q) => $q->whereNull('deleted_at'))
            ->orderByDesc('updated_at')
            ->limit(50)
            ->get();

        return view('paineljovens::wallet.index', [
            'registrations' => $registrations,
        ]);
    }
}
