<?php

namespace App\Http\Requests;

use App\Models\Devotional;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DevotionalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            if ($this->input('author_type') === Devotional::AUTHOR_PASTOR_GUEST) {
                if (trim((string) $this->input('guest_author_name')) === '') {
                    $validator->errors()->add('guest_author_name', 'Indique o nome do pastor ou convidado.');
                }
            }
            if ($this->input('author_type') === Devotional::AUTHOR_BOARD_MEMBER && ! $this->filled('board_member_id')) {
                $validator->errors()->add('board_member_id', 'Selecione um membro da diretoria.');
            }
        });
    }

    public function rules(): array
    {
        $id = $this->route('devotional')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable', 'string', 'max:255',
                Rule::unique('devotionals', 'slug')->ignore($id),
            ],
            'devotional_date' => ['nullable', 'date'],
            'theme' => ['nullable', 'string', 'max:120'],
            'scripture_reference' => ['required', 'string', 'max:255'],
            'scripture_text' => ['nullable', 'string', 'max:65535'],
            'bible_version_id' => ['nullable', 'integer', 'exists:bible_versions,id'],
            'body' => ['required', 'string', 'max:65535'],
            'cover' => ['nullable', 'image', 'max:5120'],
            'video' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:102400'],
            'video_url' => ['nullable', 'string', 'max:2048'],
            'status' => ['required', Rule::in([Devotional::STATUS_DRAFT, Devotional::STATUS_PUBLISHED])],
            'author_type' => ['required', Rule::in([
                Devotional::AUTHOR_USER,
                Devotional::AUTHOR_BOARD_MEMBER,
                Devotional::AUTHOR_PASTOR_GUEST,
            ])],
            'user_id' => ['nullable', 'exists:users,id'],
            'board_member_id' => ['nullable', 'exists:board_members,id'],
            'guest_author_name' => ['nullable', 'string', 'max:255'],
            'guest_author_title' => ['nullable', 'string', 'max:255'],
        ];
    }
}
