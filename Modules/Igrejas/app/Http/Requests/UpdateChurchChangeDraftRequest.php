<?php

namespace Modules\Igrejas\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Modules\Igrejas\App\Models\ChurchChangeRequest;

class UpdateChurchChangeDraftRequest extends FormRequest
{
    public function authorize(): bool
    {
        $req = $this->route('churchChangeRequest');

        return $req instanceof ChurchChangeRequest && $this->user()?->can('update', $req);
    }

    public function rules(): array
    {
        $req = $this->route('churchChangeRequest');
        if (! $req instanceof ChurchChangeRequest) {
            return ['payload' => ['required', 'array']];
        }

        return ChurchChangeDraftPayloadRules::rulesForUpdate($req->type);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $req = $this->route('churchChangeRequest');
            if (! $req instanceof ChurchChangeRequest) {
                return;
            }

            ChurchChangeDraftPayloadRules::assertPayloadMeetsType($v, $req->type, $this->input('payload', []));
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function normalizedPayload(): array
    {
        $req = $this->route('churchChangeRequest');
        $validated = $this->validated();

        return ChurchChangeDraftPayloadRules::normalizePayload(
            is_array($validated['payload'] ?? null) ? $validated['payload'] : [],
            $req instanceof ChurchChangeRequest ? $req->type : ''
        );
    }
}
