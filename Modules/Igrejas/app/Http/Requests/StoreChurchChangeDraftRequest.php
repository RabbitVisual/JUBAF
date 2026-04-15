<?php

namespace Modules\Igrejas\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Igrejas\App\Models\ChurchChangeRequest;

class StoreChurchChangeDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ChurchChangeRequest::class) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->input('church_id') === '' || $this->input('church_id') === null) {
            $this->merge(['church_id' => null]);
        }
    }

    public function rules(): array
    {
        $type = (string) $this->input('type', '');

        return ChurchChangeDraftPayloadRules::rulesForStore($type);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $type = $this->input('type');
            if (! is_string($type) || ! in_array($type, ChurchChangeRequest::types(), true)) {
                return;
            }

            ChurchChangeDraftPayloadRules::assertPayloadMeetsType($v, $type, $this->input('payload', []));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function normalizedPayload(): array
    {
        $validated = $this->validated();

        return ChurchChangeDraftPayloadRules::normalizePayload(
            is_array($validated['payload'] ?? null) ? $validated['payload'] : [],
            (string) $validated['type']
        );
    }
}
