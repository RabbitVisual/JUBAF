<?php

namespace Modules\Financeiro\App\Policies;

use App\Models\User;
use Modules\Financeiro\App\Models\FinTransaction;

class FinTransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('financeiro.transactions.view');
    }

    public function view(User $user, FinTransaction $finTransaction): bool
    {
        return $user->can('financeiro.transactions.view');
    }

    public function create(User $user): bool
    {
        return $user->can('financeiro.transactions.create');
    }

    public function update(User $user, FinTransaction $finTransaction): bool
    {
        if (! $user->can('financeiro.transactions.edit')) {
            return false;
        }

        if ($finTransaction->isFromGateway()) {
            return false;
        }

        return true;
    }

    public function delete(User $user, FinTransaction $finTransaction): bool
    {
        if (! $user->can('financeiro.transactions.delete')) {
            return false;
        }

        if ($finTransaction->isFromGateway()) {
            return false;
        }

        return true;
    }

    public function exportReports(User $user): bool
    {
        return $user->can('financeiro.reports.view');
    }
}
