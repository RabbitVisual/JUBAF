<?php

namespace Modules\Talentos\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Talentos\App\Models\TalentSkill;

class UpdateTalentSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        $skill = $this->route('skill');

        return $skill instanceof TalentSkill
            && $this->user() !== null
            && $this->user()->can('update', $skill);
    }

    public function rules(): array
    {
        $skill = $this->route('skill');

        return [
            'name' => [
                'required',
                'string',
                'max:160',
                Rule::unique('talent_skills', 'name')->ignore($skill instanceof TalentSkill ? $skill->id : null),
            ],
        ];
    }
}
