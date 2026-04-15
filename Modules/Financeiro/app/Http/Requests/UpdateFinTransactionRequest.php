<?php

namespace Modules\Financeiro\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Financeiro\App\Services\FinanceiroService;
use Modules\Igrejas\App\Models\Church;

class UpdateFinTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $tx = $this->route('transaction');

        return $tx && $this->user()?->can('update', $tx);
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:fin_categories,id'],
            'bank_account_id' => ['nullable', 'exists:fin_bank_accounts,id'],
            'occurred_on' => ['required', 'date'],
            'due_on' => ['nullable', 'date'],
            'paid_on' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'direction' => ['required', Rule::in(['in', 'out'])],
            'scope' => ['required', Rule::in([FinTransaction::SCOPE_REGIONAL, FinTransaction::SCOPE_CHURCH])],
            'church_id' => [
                'nullable',
                'required_if:scope,'.FinTransaction::SCOPE_CHURCH,
                'exists:igrejas_churches,id',
            ],
            'description' => ['nullable', 'string', 'max:500'],
            'reference' => ['nullable', 'string', 'max:120'],
            'document_ref' => ['nullable', 'string', 'max:120'],
            'secretaria_minute_id' => ['nullable', 'integer', 'exists:secretaria_minutes,id'],
            'status' => ['required', Rule::in([FinTransaction::STATUS_PENDING, FinTransaction::STATUS_PAID, FinTransaction::STATUS_OVERDUE])],
            'comprovante' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $v): void {
            $cat = FinCategory::query()->find($this->input('category_id'));
            if ($cat && $cat->direction !== $this->input('direction')) {
                $v->errors()->add('category_id', 'A categoria seleccionada não corresponde ao tipo (receita/despesa).');
            }

            $user = $this->user();
            $transaction = $this->route('transaction');
            if ($user && $user->restrictsChurchDirectoryToSector()
                && (string) $this->input('scope') === FinTransaction::SCOPE_CHURCH && $this->filled('church_id')) {
                $church = Church::query()->find((int) $this->input('church_id'));
                if ($church && ! $user->canAccessChurchInSectorScope($church)) {
                    $v->errors()->add('church_id', 'Esta congregação não pertence ao seu setor.');
                }
            }

            if (! $cat || ! $transaction instanceof FinTransaction) {
                return;
            }

            $svc = app(FinanceiroService::class);
            if ($svc->categoryRequiresExtraordinaryAudit($cat, (string) $this->input('direction'))) {
                $hasExisting = (bool) ($transaction->comprovante_path);
                if (! $this->hasFile('comprovante') && ! $hasExisting) {
                    $v->errors()->add('comprovante', 'Comprovante obrigatório para esta despesa (auditoria).');
                }
                if (! $this->filled('secretaria_minute_id')) {
                    $v->errors()->add('secretaria_minute_id', 'Seleccione a ata que autorizou o gasto.');
                }
            }
        });
    }
}
