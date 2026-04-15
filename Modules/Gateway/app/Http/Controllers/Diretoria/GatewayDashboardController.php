<?php

namespace Modules\Gateway\App\Http\Controllers\Diretoria;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Gateway\App\Models\GatewayWebhookEvent;

class GatewayDashboardController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', GatewayPayment::class);

        $pending = GatewayPayment::query()->where('status', GatewayPayment::STATUS_PENDING)->count();
        $paidMonth = GatewayPayment::query()
            ->where('status', GatewayPayment::STATUS_PAID)
            ->where('paid_at', '>=', now()->startOfMonth())
            ->count();
        $failed = GatewayPayment::query()->where('status', GatewayPayment::STATUS_FAILED)->count();

        $recent = GatewayPayment::query()
            ->with(['user', 'providerAccount'])
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        $webhooks = GatewayWebhookEvent::query()
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('gateway::paineldiretoria.dashboard', [
            'layout' => 'layouts.app',
            'pending' => $pending,
            'paidMonth' => $paidMonth,
            'failed' => $failed,
            'recent' => $recent,
            'webhooks' => $webhooks,
        ]);
    }
}
