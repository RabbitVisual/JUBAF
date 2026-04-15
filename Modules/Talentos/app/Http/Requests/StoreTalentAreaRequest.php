<?php

namespace Modules\Talentos\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTalentAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && $this->user()->can('create', \Modules\Talentos\App\Models\TalentArea::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:160', 'unique:talent_areas,name'],
        ];
    }
}
