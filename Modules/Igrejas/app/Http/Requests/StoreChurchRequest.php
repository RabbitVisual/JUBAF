<?php

namespace Modules\Igrejas\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Modules\Igrejas\App\Models\Church;

class StoreChurchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('igrejas.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        $kind = $this->input('kind', Church::KIND_CHURCH);
        if ($kind === Church::KIND_CHURCH) {
            $this->merge(['parent_church_id' => null]);
        }
        if ($kind === Church::KIND_CONGREGATION) {
            $this->merge(['cnpj' => null]);
        }
        if ($this->filled('cnpj')) {
            $digits = preg_replace('/\D+/', '', (string) $this->input('cnpj'));
            $this->merge(['cnpj' => $digits !== '' ? $digits : null]);
        }

        if ($this->user()?->restrictsChurchDirectoryToSector()) {
            $this->merge(['jubaf_sector_id' => $this->user()->jubaf_sector_id]);
        }
    }

    public function rules(): array
    {
        $sectorRule = Schema::hasTable('jubaf_sectors')
            ? ['nullable', 'integer', 'exists:jubaf_sectors,id']
            : ['nullable'];

        return [
            'kind' => ['required', 'string', Rule::in(Church::kinds())],
            'parent_church_id' => ['nullable', 'integer', 'exists:igrejas_churches,id'],
            'cnpj' => [
                'nullable',
                'string',
                'size:14',
                Rule::requiredIf(fn () => $this->input('kind') === Church::KIND_CHURCH),
                Rule::unique('igrejas_churches', 'cnpj'),
            ],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique(Church::class, 'slug')],
            'sector' => ['nullable', 'string', 'max:120'],
            'jubaf_sector_id' => $sectorRule,
            'foundation_date' => ['nullable', 'date'],
            'cooperation_status' => ['nullable', 'string', Rule::in(Church::cooperationStatuses())],
            'pastor_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'unijovem_leader_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:255'],
            'asbaf_notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'joined_at' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v): void {
            $kind = $this->input('kind');
            $pid = $this->input('parent_church_id');
            if ($kind === Church::KIND_CHURCH && $pid) {
                $v->errors()->add('parent_church_id', 'Uma sede (igreja) não pode ter igreja mãe.');
            }
            if ($pid) {
                $parent = Church::query()->find((int) $pid);
                if ($parent && $parent->kind !== Church::KIND_CHURCH) {
                    $v->errors()->add('parent_church_id', 'A igreja mãe deve ser do tipo sede (igreja).');
                }
            }
        });
    }
}
