<?php

namespace Modules\Financeiro\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFinExpenseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', \Modules\Financeiro\App\Models\FinExpenseRequest::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'justification' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp'],
        ];
    }
}
