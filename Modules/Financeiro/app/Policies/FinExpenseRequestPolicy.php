<?php

namespace Modules\Financeiro\App\Policies;

use App\Models\User;
use Modules\Financeiro\App\Models\FinExpenseRequest;

class FinExpenseRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('financeiro.expense_requests.view');
    }

    public function view(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        return $user->can('financeiro.expense_requests.view');
    }

    public function create(User $user): bool
    {
        return $user->can('financeiro.expense_requests.create');
    }

    public function update(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        if (! $user->can('financeiro.expense_requests.edit')) {
            return false;
        }

        if (in_array($finExpenseRequest->status, [FinExpenseRequest::STATUS_PAID, FinExpenseRequest::STATUS_REJECTED], true)) {
            return false;
        }

        $owner = (int) $finExpenseRequest->requested_by === (int) $user->id;
        $treasury = $user->hasAnyRole(['super-admin', 'tesoureiro-1', 'tesoureiro-2']);

        if ($finExpenseRequest->status === FinExpenseRequest::STATUS_DRAFT) {
            return $owner || $treasury || $user->hasRole('presidente');
        }

        return $treasury || $user->hasRole('super-admin');
    }

    public function delete(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        if (! $user->can('financeiro.expense_requests.delete')) {
            return false;
        }

        return $finExpenseRequest->status === FinExpenseRequest::STATUS_DRAFT
            && (int) $finExpenseRequest->requested_by === (int) $user->id;
    }

    public function submit(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        return $finExpenseRequest->status === FinExpenseRequest::STATUS_DRAFT
            && (int) $finExpenseRequest->requested_by === (int) $user->id
            && $user->can('financeiro.expense_requests.edit');
    }

    public function approve(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        return $user->can('financeiro.expense_requests.approve')
            && $finExpenseRequest->status === FinExpenseRequest::STATUS_SUBMITTED;
    }

    public function reject(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        return $user->can('financeiro.expense_requests.approve')
            && $finExpenseRequest->status === FinExpenseRequest::STATUS_SUBMITTED;
    }

    public function pay(User $user, FinExpenseRequest $finExpenseRequest): bool
    {
        return $user->can('financeiro.expense_requests.pay')
            && $finExpenseRequest->status === FinExpenseRequest::STATUS_APPROVED
            && $finExpenseRequest->paid_transaction_id === null;
    }
}
