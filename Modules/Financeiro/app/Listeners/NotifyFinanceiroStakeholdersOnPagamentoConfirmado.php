<?php

namespace Modules\Financeiro\App\Listeners;

use App\Models\User;
use Modules\Financeiro\App\Events\PagamentoConfirmado;
use Modules\Notificacoes\App\Services\NotificacaoService;

class NotifyFinanceiroStakeholdersOnPagamentoConfirmado
{
    public function handle(PagamentoConfirmado $event): void
    {
        if (! module_enabled('Notificacoes') || ! class_exists(NotificacaoService::class)) {
            return;
        }

        $svc = app(NotificacaoService::class);
        $amount = number_format((float) $event->transaction->amount, 2, ',', '.');
        $title = 'Pagamento confirmado (Tesouraria)';
        $message = 'Receita de R$ '.$amount.' registada no livro (ref. '.($event->transaction->reference ?? $event->transaction->uuid).').';

        foreach (User::role(['tesoureiro-1', 'tesoureiro-2'])->get() as $user) {
            $svc->sendToUser(
                $user->id,
                'financeiro.pagamento_confirmado',
                $title,
                $message,
                null,
                [
                    'fin_transaction_id' => $event->transaction->id,
                    'gateway_payment_id' => $event->gatewayPayment?->id,
                ],
                'Financeiro',
                null,
                null,
                false,
                'diretoria'
            );
        }

        $churchId = $event->transaction->church_id;
        if (! $churchId) {
            return;
        }

        $leaders = User::query()
            ->where(function ($q) use ($churchId) {
                $q->where('church_id', $churchId)
                    ->orWhereHas('assignedChurches', function ($c) use ($churchId) {
                        $c->whereKey($churchId);
                    });
            })
            ->whereHas('roles', function ($r) {
                $r->whereIn('name', ['lider', 'pastor']);
            })
            ->get();

        foreach ($leaders as $user) {
            $svc->sendToUser(
                $user->id,
                'financeiro.pagamento_confirmado',
                'Pagamento JUBAF confirmado',
                'A tesouraria registou um pagamento de R$ '.$amount.' vinculado à sua congregação.',
                null,
                [
                    'fin_transaction_id' => $event->transaction->id,
                    'church_id' => $churchId,
                ],
                'Financeiro',
                null,
                null,
                false,
                'lideres'
            );
        }
    }
}
