<?php

namespace Modules\Talentos\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Talentos\App\Models\TalentSkill;

class UpdateTalentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('talentos.profile.edit') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $levels = $this->input('skill_levels', []);
        if (! is_array($levels)) {
            return;
        }
        $clean = [];
        foreach ($levels as $k => $v) {
            $clean[$k] = ($v === '' || $v === null) ? null : $v;
        }
        $this->merge(['skill_levels' => $clean]);
    }

    public function rules(): array
    {
        return [
            'bio' => ['nullable', 'string', 'max:2000'],
            'availability_text' => ['nullable', 'string', 'max:500'],
            'is_searchable' => ['sometimes', 'boolean'],
            'skill_ids' => ['nullable', 'array'],
            'skill_ids.*' => ['integer', 'exists:talent_skills,id'],
            'skill_levels' => ['nullable', 'array'],
            'skill_levels.*' => ['nullable', 'string', Rule::in(array_keys(TalentSkill::levelOptions()))],
            'area_ids' => ['nullable', 'array'],
            'area_ids.*' => ['integer', 'exists:talent_areas,id'],
        ];
    }
}
