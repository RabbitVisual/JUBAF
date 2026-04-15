<?php

namespace Modules\Gateway\App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Gateway\App\Models\GatewayPayment;

class CheckoutController extends Controller
{
    public function show(Request $request, string $uuid): View|RedirectResponse
    {
        $payment = GatewayPayment::query()->where('uuid', $uuid)->firstOrFail();

        if ($payment->checkout_url && $payment->status === GatewayPayment::STATUS_PENDING) {
            return redirect()->away($payment->checkout_url);
        }

        return view('gateway::public.checkout', [
            'payment' => $payment,
        ]);
    }

    public function returnPage(Request $request, string $uuid): View
    {
        $payment = GatewayPayment::query()->where('uuid', $uuid)->firstOrFail();

        return view('gateway::public.return', [
            'payment' => $payment,
            'sessionId' => $request->query('session_id'),
        ]);
    }
}
