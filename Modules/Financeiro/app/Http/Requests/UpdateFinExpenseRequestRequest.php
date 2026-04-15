<?php

namespace Modules\Financeiro\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFinExpenseRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        $er = $this->route('expense_request');

        return $er && $this->user()?->can('update', $er);
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
