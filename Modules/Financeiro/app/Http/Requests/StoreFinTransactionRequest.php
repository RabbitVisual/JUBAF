<?php

namespace Modules\Financeiro\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Financeiro\App\Models\FinCategory;
use Modules\Financeiro\App\Models\FinTransaction;
use Modules\Igrejas\App\Models\Church;

class StoreFinTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \Modules\Financeiro\App\Models\FinTransaction::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:fin_categories,id'],
            'occurred_on' => ['required', 'date'],
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
            if (! $user || ! $user->restrictsChurchDirectoryToSector()) {
                return;
            }
            if ((string) $this->input('scope') === FinTransaction::SCOPE_CHURCH && $this->filled('church_id')) {
                $church = Church::query()->find((int) $this->input('church_id'));
                if ($church && ! $user->canAccessChurchInSectorScope($church)) {
                    $v->errors()->add('church_id', 'Esta congregação não pertence ao seu setor.');
                }
            }
        });
    }
}
