<?php

namespace Modules\Gateway\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Calendario\App\Models\CalendarRegistration;
use Modules\Financeiro\App\Events\FinancialObligationPaid;
use Modules\Financeiro\App\Events\PagamentoConfirmado;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinObligation;
use Modules\Financeiro\App\Models\FinQuotaInvoice;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Financeiro\App\Services\FinanceiroService;
use Modules\Gateway\App\Models\GatewayPayment;
use Modules\Notificacoes\App\Models\Notificacao;

class ReconcilePaymentToFinTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $gatewayPaymentId) {}

    public function handle(FinanceiroService $financeiro): void
    {
        $payment = GatewayPayment::query()->find($this->gatewayPaymentId);
        if (! $payment || $payment->status !== GatewayPayment::STATUS_PAID) {
            return;
        }

        if ($payment->fin_transaction_id) {
            $this->finalizePayable($payment);

            return;
        }

        $payload = is_array($payment->raw_last_payload) ? $payment->raw_last_payload : [];
        $obligationId = isset($payload['fin_obligation_id']) ? (int) $payload['fin_obligation_id'] : null;
        $quotaInvoiceId = isset($payload['fin_quota_invoice_id']) ? (int) $payload['fin_quota_invoice_id'] : null;
        $churchFromPayload = isset($payload['church_id']) ? (int) $payload['church_id'] : null;

        $obligation = $obligationId ? FinObligation::query()->find($obligationId) : null;
        $quotaInvoice = $quotaInvoiceId ? FinQuotaInvoice::query()->find($quotaInvoiceId) : null;

        $churchId = $obligation?->church_id ?? ($quotaInvoice?->church_id ?? ($churchFromPayload ?: null));
        $scope = $churchId ? FinTransaction::SCOPE_CHURCH : FinTransaction::SCOPE_REGIONAL;

        $category = $this->resolveCategoryForGateway($obligation, $quotaInvoice);
        if (! $category) {
            return;
        }

        $obligationForEvent = null;
        $quotaForEvent = null;

        DB::transaction(function () use (
            $payment,
            $category,
            $obligation,
            $quotaInvoice,
            $churchId,
            $scope,
            $financeiro,
            &$obligationForEvent,
            &$quotaForEvent
        ): void {
            $meta = [
                'gateway_payment_id' => $payment->id,
                'gateway_driver' => $payment->driver,
                'provider_reference' => $payment->provider_reference,
            ];
            if ($obligation) {
                $meta['fin_obligation_id'] = $obligation->id;
            }
            if ($quotaInvoice) {
                $meta['fin_quota_invoice_id'] = $quotaInvoice->id;
            }

            $tx = $financeiro->persistGatewayTransaction([
                'category_id' => $category->id,
                'occurred_on' => now()->toDateString(),
                'amount' => $payment->amount,
                'direction' => 'in',
                'scope' => $scope,
                'church_id' => $churchId,
                'description' => $payment->description ?: 'Receita online (gateway)',
                'reference' => 'GW-'.$payment->uuid,
                'source' => FinTransaction::SOURCE_GATEWAY,
                'metadata' => $meta,
                'created_by' => $payment->user_id,
            ], FinanceiroService::defaultBankAccount());

            $payment->fin_transaction_id = $tx->id;
            $payment->save();

            if ($obligation && $obligation->status === FinObligation::STATUS_PENDING) {
                $obligation->update([
                    'status' => FinObligation::STATUS_PAID,
                    'fin_transaction_id' => $tx->id,
                    'paid_at' => now(),
                ]);
                $obligationForEvent = $obligation->fresh();
                event(new FinancialObligationPaid($obligationForEvent, $tx->fresh()));
            }

            if ($quotaInvoice && $quotaInvoice->status === FinQuotaInvoice::STATUS_PENDING) {
                $quotaInvoice->update([
                    'status' => FinQuotaInvoice::STATUS_PAID,
                    'fin_transaction_id' => $tx->id,
                    'paid_at' => now(),
                ]);
                $quotaForEvent = $quotaInvoice->fresh();
            }

            event(new PagamentoConfirmado($tx->fresh(), $payment->fresh(), $obligationForEvent, $quotaForEvent));
        });

        $this->finalizePayable($payment->fresh());
        $this->notifyUser($payment);
    }

    private function resolveCategoryForGateway(?FinObligation $obligation, ?FinQuotaInvoice $quotaInvoice): ?FinCategory
    {
        if ($obligation || $quotaInvoice) {
            $code = (string) config('financeiro.quota.income_category_code', '');
            if ($code !== '') {
                $byCode = FinCategory::query()->where('direction', 'in')->where('code', $code)->first();
                if ($byCode) {
                    return $byCode;
                }
            }

            $quota = FinCategory::query()
                ->where('direction', 'in')
                ->where(function ($q) {
                    $q->where('name', 'like', '%cota%')
                        ->orWhere('name', 'like', '%anuid%');
                })
                ->orderBy('sort_order')
                ->first();
            if ($quota) {
                return $quota;
            }
        }

        return FinCategory::query()
            ->where('direction', 'in')
            ->where('code', FinCategory::CODE_REC_INSCRICOES_EVENTOS)
            ->first()
            ?? FinCategory::query()
                ->where('direction', 'in')
                ->where(function ($q) {
                    $q->where('name', 'like', '%Inscri%')
                        ->orWhere('name', 'like', '%evento%');
                })
                ->first()
            ?? FinCategory::query()->where('direction', 'in')->orderBy('sort_order')->first();
    }

    private function finalizePayable(GatewayPayment $payment): void
    {
        $payable = $payment->payable;
        if ($payable instanceof CalendarRegistration) {
            $event = $payable->event;
            $max = $event?->max_participants;
            $othersConfirmed = $event
                ? $event->registrations()
                    ->where('status', CalendarRegistration::STATUS_CONFIRMED)
                    ->where('id', '!=', $payable->id)
                    ->count()
                : 0;
            $status = CalendarRegistration::STATUS_CONFIRMED;
            if ($max !== null && $othersConfirmed >= $max) {
                $status = CalendarRegistration::STATUS_WAITLIST;
            }
            $payable->update([
                'status' => $status,
                'payment_status' => 'paid',
                'amount_charged' => $payment->amount,
            ]);
        }
    }

    private function notifyUser(GatewayPayment $payment): void
    {
        if (! $payment->user_id || ! module_enabled('Notificacoes') || ! class_exists(Notificacao::class)) {
            return;
        }

        try {
            Notificacao::query()->create([
                'user_id' => $payment->user_id,
                'title' => 'Pagamento confirmado',
                'message' => 'O teu pagamento de R$ '.number_format((float) $payment->amount, 2, ',', '.').
                    ' foi confirmado.',
                'is_read' => false,
                'type' => 'gateway.payment_paid',
                'module_source' => 'Gateway',
                'data' => ['gateway_payment_id' => $payment->id],
                'panel' => 'jovens',
            ]);
        } catch (\Throwable) {
            // módulo opcional / schema diferente
        }
    }
}
