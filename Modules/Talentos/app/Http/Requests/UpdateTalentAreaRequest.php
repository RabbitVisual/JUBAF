<?php

namespace Modules\Talentos\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Talentos\App\Models\TalentArea;

class UpdateTalentAreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $area = $this->route('area');

        return $area instanceof TalentArea
            && $this->user() !== null
            && $this->user()->can('update', $area);
    }

    public function rules(): array
    {
        $area = $this->route('area');

        return [
            'name' => [
                'required',
                'string',
                'max:160',
                Rule::unique('talent_areas', 'name')->ignore($area instanceof TalentArea ? $area->id : null),
            ],
        ];
    }
}
