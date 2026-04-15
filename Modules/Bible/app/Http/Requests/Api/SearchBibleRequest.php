<?php

namespace Modules\Bible\App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SearchBibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'q' => ['required', 'string', 'min:2', 'max:180'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'version' => ['nullable', 'string', 'max:20', 'exists:bible_versions,abbreviation'],
        ];
    }
}
