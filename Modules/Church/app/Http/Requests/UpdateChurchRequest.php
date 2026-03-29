<?php

namespace Modules\Church\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChurchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $churchId = $this->route('church') ? $this->route('church')->id : null;

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:churches,slug,' . $churchId,
            'unijovem_name' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:100',
            'leader_name' => 'nullable|string|max:255',
            'leader_phone' => 'nullable|string|max:50',
            'unijovem_representative_user_id' => 'nullable|exists:users,id',
            'city' => 'nullable|string|max:255',
            'neighborhood' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
