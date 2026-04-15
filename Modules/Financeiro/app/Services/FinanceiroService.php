<?php

namespace Modules\Financeiro\App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Financeiro\App\Models\FinBankAccount;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;

class FinanceiroService
{
    public function categoryRequiresExtraordinaryAudit(FinCategory $category, string $direction): bool
    {
        if ($direction !== 'out') {
            return false;
        }

        if ($category->requiresAuditForExpense()) {
            return true;
        }

        $groups = config('financeiro.extraordinary_expense_group_keys', []);

        return $category->group_key && in_array($category->group_key, $groups, true);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createManualTransaction(array $data, ?User $user): FinTransaction
    {
        return DB::transaction(function () use ($data, $user) {
            $category = FinCategory::query()->findOrFail((int) $data['category_id']);
            $direction = (string) $data['direction'];
            $isExtraordinary = $this->categoryRequiresExtraordinaryAudit($category, $direction);

            $status = (string) ($data['status'] ?? FinTransaction::STATUS_PAID);
            $occurredOn = $data['occurred_on'] ?? now()->toDateString();

            $payload = [
                'category_id' => $category->id,
                'bank_account_id' => $data['bank_account_id'] ?? null,
                'occurred_on' => $occurredOn,
                'due_on' => $data['due_on'] ?? null,
                'paid_on' => $data['paid_on'] ?? ($status === FinTransaction::STATUS_PAID ? $occurredOn : null),
                'amount' => $data['amount'],
                'direction' => $direction,
                'scope' => $data['scope'],
                'church_id' => $data['church_id'] ?? null,
                'description' => $data['description'] ?? null,
                'reference' => $data['reference'] ?? null,
                'document_ref' => $data['document_ref'] ?? null,
                'source' => FinTransaction::SOURCE_MANUAL,
                'comprovante_path' => $data['comprovante_path'] ?? null,
                'status' => $status,
                'reconciled' => false,
                'is_extraordinary' => $isExtraordinary,
                'secretaria_minute_id' => $data['secretaria_minute_id'] ?? null,
                'calendar_event_id' => $data['calendar_event_id'] ?? null,
                'metadata' => $data['metadata'] ?? null,
                'created_by' => $user?->id,
            ];

            /** @var FinTransaction $tx */
            $tx = FinTransaction::query()->create($payload);

            if ($this->shouldApplyLedger($tx)) {
                $this->applyLedgerDelta($tx, 1);
            }

            return $tx->fresh();
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateTransaction(FinTransaction $transaction, array $data): FinTransaction
    {
        return DB::transaction(function () use ($transaction, $data) {
            $wasApplicable = $this->shouldApplyLedger($transaction);

            if ($wasApplicable) {
                $this->applyLedgerDelta($transaction, -1);
            }

            $category = isset($data['category_id'])
                ? FinCategory::query()->findOrFail((int) $data['category_id'])
                : $transaction->category;
            $direction = (string) ($data['direction'] ?? $transaction->direction);
            $isExtraordinary = $this->categoryRequiresExtraordinaryAudit($category, $direction);

            $transaction->fill([
                'category_id' => $data['category_id'] ?? $transaction->category_id,
                'bank_account_id' => array_key_exists('bank_account_id', $data) ? $data['bank_account_id'] : $transaction->bank_account_id,
                'occurred_on' => $data['occurred_on'] ?? $transaction->occurred_on,
                'due_on' => array_key_exists('due_on', $data) ? $data['due_on'] : $transaction->due_on,
                'paid_on' => array_key_exists('paid_on', $data) ? $data['paid_on'] : $transaction->paid_on,
                'amount' => $data['amount'] ?? $transaction->amount,
                'direction' => $direction,
                'scope' => $data['scope'] ?? $transaction->scope,
                'church_id' => array_key_exists('church_id', $data) ? $data['church_id'] : $transaction->church_id,
                'description' => $data['description'] ?? $transaction->description,
                'reference' => $data['reference'] ?? $transaction->reference,
                'document_ref' => $data['document_ref'] ?? $transaction->document_ref,
                'comprovante_path' => $data['comprovante_path'] ?? $transaction->comprovante_path,
                'status' => $data['status'] ?? $transaction->status,
                'is_extraordinary' => $isExtraordinary,
                'secretaria_minute_id' => array_key_exists('secretaria_minute_id', $data) ? $data['secretaria_minute_id'] : $transaction->secretaria_minute_id,
                'calendar_event_id' => array_key_exists('calendar_event_id', $data) ? $data['calendar_event_id'] : $transaction->calendar_event_id,
            ]);

            $transaction->save();
            $transaction->refresh();

            if ($this->shouldApplyLedger($transaction)) {
                $this->applyLedgerDelta($transaction, 1);
            }

            return $transaction->fresh();
        });
    }

    public function deleteTransaction(FinTransaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            if ($this->shouldApplyLedger($transaction)) {
                $this->applyLedgerDelta($transaction, -1);
            }

            $transaction->delete();
        });
    }

    /**
     * Pagamento online reconciliado. O chamador deve envolver em {@see DB::transaction} se necessário atomicidade com GatewayPayment / obrigações.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function persistGatewayTransaction(array $attributes, ?FinBankAccount $bankAccount = null): FinTransaction
    {
        if ($bankAccount) {
            $attributes['bank_account_id'] = $bankAccount->id;
        }

        $attributes['reconciled'] = true;
        $attributes['status'] = $attributes['status'] ?? FinTransaction::STATUS_PAID;
        $attributes['paid_on'] = $attributes['paid_on'] ?? ($attributes['occurred_on'] ?? now()->toDateString());

        /** @var FinTransaction $tx */
        $tx = FinTransaction::query()->create($attributes);

        if ($this->shouldApplyLedger($tx)) {
            $this->applyLedgerDelta($tx, 1);
        }

        return $tx->fresh();
    }

    public function storeComprovante(UploadedFile $file): string
    {
        return $file->store('financeiro/comprovantes', 'local');
    }

    public function deleteComprovanteIfExists(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        Storage::disk('local')->delete($path);
    }

    private function shouldApplyLedger(FinTransaction $tx): bool
    {
        if ($tx->bank_account_id === null) {
            return false;
        }

        if ($tx->status !== FinTransaction::STATUS_PAID) {
            return false;
        }

        return true;
    }

    private function applyLedgerDelta(FinTransaction $tx, int $sign): void
    {
        $account = FinBankAccount::query()->whereKey($tx->bank_account_id)->lockForUpdate()->first();
        if (! $account) {
            return;
        }

        $delta = (float) $tx->amount;
        if ($tx->direction === 'out') {
            $delta = -$delta;
        }
        $delta *= $sign;

        $account->balance = bcadd((string) $account->balance, (string) $delta, 2);
        $account->save();
    }

    public static function defaultBankAccount(): ?FinBankAccount
    {
        return FinBankAccount::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();
    }
}
