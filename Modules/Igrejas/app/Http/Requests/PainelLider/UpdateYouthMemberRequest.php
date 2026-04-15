<?php

namespace Modules\Igrejas\App\Http\Requests\PainelLider;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateYouthMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $youth = $this->route('youth');
        if (! $youth instanceof \App\Models\User) {
            return false;
        }

        return $this->user()?->can('igrejasManageChurchYouth', $youth) ?? false;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $youth = $this->route('youth');
        $id = $youth instanceof \App\Models\User ? $youth->id : 0;

        return [
            'first_name' => ['required', 'string', 'max:120'],
            'last_name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Já existe uma conta com este e-mail.',
        ];
    }
}
