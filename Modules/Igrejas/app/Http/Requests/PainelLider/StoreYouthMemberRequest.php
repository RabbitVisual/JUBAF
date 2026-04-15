<?php

namespace Modules\Igrejas\App\Http\Requests\PainelLider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreYouthMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $u = $this->user();

        return $u && $u->can('igrejas.jovens.provision') && (bool) $u->church_id;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')],
            'phone' => ['nullable', 'string', 'max:40'],
            'set_password_now' => ['sometimes', 'boolean'],
            'password' => [Rule::requiredIf($this->boolean('set_password_now')), 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Já existe uma conta com este e-mail.',
        ];
    }
}
