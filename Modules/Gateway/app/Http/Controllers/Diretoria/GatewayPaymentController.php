<?php

namespace Modules\Gateway\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Modules\Gateway\App\Models\GatewayPayment;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GatewayPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', GatewayPayment::class);

        $q = GatewayPayment::query()->with(['user', 'providerAccount', 'payable']);

        if ($request->filled('status')) {
            $q->where('status', $request->string('status'));
        }

        $payments = $q->orderByDesc('id')->paginate(25)->withQueryString();

        return view('gateway::paineldiretoria.payments.index', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'payments' => $payments,
            'filters' => $request->only(['status']),
        ]);
    }

    public function show(GatewayPayment $payment): View
    {
        $this->authorize('view', $payment);
        $payment->load(['user', 'providerAccount', 'payable', 'finTransaction']);

        return view('gateway::paineldiretoria.payments.show', [
            'layout' => 'paineldiretoria::components.layouts.app',
            'payment' => $payment,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse|Response
    {
        $this->authorize('viewAny', GatewayPayment::class);

        $from = $request->date('from') ?? now()->subMonth();
        $to = $request->date('to') ?? now();

        $filename = 'gateway_payments_'.$from->format('Y-m-d').'_'.$to->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($from, $to): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'uuid', 'driver', 'status', 'amount', 'currency', 'paid_at', 'provider_reference', 'fin_transaction_id']);

            GatewayPayment::query()
                ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
                ->orderBy('id')
                ->chunk(200, function ($rows) use ($out): void {
                    foreach ($rows as $p) {
                        fputcsv($out, [
                            $p->id,
                            $p->uuid,
                            $p->driver,
                            $p->status,
                            $p->amount,
                            $p->currency,
                            $p->paid_at?->toDateTimeString(),
                            $p->provider_reference,
                            $p->fin_transaction_id,
                        ]);
                    }
                });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
