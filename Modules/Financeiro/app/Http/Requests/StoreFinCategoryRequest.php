<?php

namespace Modules\Financeiro\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Financeiro\App\Models\FinCategory;

class StoreFinCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \Modules\Financeiro\App\Models\FinCategory::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'code' => [
                'nullable',
                'string',
                'max:48',
                'regex:/^[A-Z0-9_]+$/',
                Rule::unique('fin_categories', 'code'),
            ],
            'group_key' => ['nullable', 'string', 'max:64', Rule::in([
                FinCategory::GROUP_RECEITAS_OPERACIONAIS,
                FinCategory::GROUP_RECEITAS_FINANCEIRAS,
                FinCategory::GROUP_APLICACAO_DIRETA,
                FinCategory::GROUP_DESPESAS_OPERACIONAIS,
                FinCategory::GROUP_DESPESAS_ADMINISTRATIVAS,
                FinCategory::GROUP_OUTROS,
            ])],
            'description' => ['nullable', 'string', 'max:2000'],
            'direction' => ['required', Rule::in(['in', 'out'])],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
