<?php

namespace Modules\Talentos\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Talentos\App\Models\TalentAssignment;

class UpdateTalentAssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('talentos.assignments.edit') ?? false;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'calendar_event_id' => ['nullable', 'integer', 'exists:calendar_events,id'],
            'role_label' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in([
                TalentAssignment::STATUS_INVITED,
                TalentAssignment::STATUS_CONFIRMED,
                TalentAssignment::STATUS_DECLINED,
            ])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
