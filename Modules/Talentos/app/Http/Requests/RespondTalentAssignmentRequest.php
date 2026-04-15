<?php

namespace Modules\Talentos\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Talentos\App\Models\TalentAssignment;

class RespondTalentAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        $assignment = $this->route('assignment');

        return $assignment instanceof TalentAssignment
            && $this->user() !== null
            && $this->user()->can('respond', $assignment);
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in([
                    TalentAssignment::STATUS_CONFIRMED,
                    TalentAssignment::STATUS_DECLINED,
                ]),
            ],
        ];
    }
}
