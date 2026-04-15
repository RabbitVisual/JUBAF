<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BoardMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'public_title' => ['required', 'string', 'max:255'],
            'group_label' => ['nullable', 'string', 'max:120'],
            'bio_short' => ['nullable', 'string', 'max:2000'],
            'city' => ['nullable', 'string', 'max:120'],
            'state' => ['nullable', 'string', 'max:8'],
            'birth_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['sometimes', 'boolean'],
            'mandate_year' => ['nullable', 'string', 'max:40'],
            'mandate_end' => ['nullable', 'date'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
