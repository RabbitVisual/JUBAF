<?php

namespace Modules\Diretoria\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Diretoria\Models\BoardMinute;

class UpdateBoardMinuteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->canAccess('governance_manage');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'meeting_date' => ['required', 'date'],
            'tag' => ['required', 'string', Rule::in(array_keys(BoardMinute::tagLabels()))],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:15360'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
