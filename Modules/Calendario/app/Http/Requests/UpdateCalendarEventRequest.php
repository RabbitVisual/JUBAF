<?php

namespace Modules\Calendario\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Calendario\App\Models\CalendarEvent;

class UpdateCalendarEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        $e = $this->route('event');

        return $e && $this->user()?->can('update', $e);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:20000'],
            'status' => ['nullable', Rule::in([
                CalendarEvent::STATUS_DRAFT,
                CalendarEvent::STATUS_WAITING_APPROVAL,
                CalendarEvent::STATUS_PUBLISHED,
                CalendarEvent::STATUS_CANCELLED,
            ])],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'all_day' => ['boolean'],
            'visibility' => ['required', Rule::in([
                CalendarEvent::VIS_PUBLIC,
                CalendarEvent::VIS_AUTH,
                CalendarEvent::VIS_DIRETORIA,
                CalendarEvent::VIS_LIDERES,
                CalendarEvent::VIS_JOVENS,
            ])],
            'type' => ['required', 'string', 'max:48'],
            'location' => ['nullable', 'string', 'max:200'],
            'church_id' => ['nullable', 'exists:igrejas_churches,id'],
            'registration_open' => ['boolean'],
            'registration_deadline' => ['nullable', 'date'],
            'max_participants' => ['nullable', 'integer', 'min:1'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'is_featured' => ['boolean'],
            'requires_council_approval' => ['boolean'],
            'contact_name' => ['nullable', 'string', 'max:120'],
            'contact_email' => ['nullable', 'email', 'max:190'],
            'contact_phone' => ['nullable', 'string', 'max:64'],
            'contact_whatsapp' => ['nullable', 'string', 'max:64'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'max_age' => ['nullable', 'integer', 'min:0', 'max:120'],
            'max_per_registration' => ['nullable', 'integer', 'min:1', 'max:50'],
            'blog_post_id' => ['nullable', 'exists:blog_posts,id'],
            'aviso_id' => ['nullable', 'exists:avisos,id'],
            'cover' => ['nullable', 'image', 'max:5120'],
            'banner' => ['nullable', 'image', 'max:8192'],
            'theme' => ['nullable', 'string', 'in:minimal,corporate,modern'],
            'primary_color' => ['nullable', 'string', 'max:32'],
            'secondary_color' => ['nullable', 'string', 'max:32'],
            'schedule_json' => ['nullable', 'string', 'max:50000'],
            'form_fields_json' => ['nullable', 'string', 'max:50000'],
            'metadata_json' => ['nullable', 'string', 'max:50000'],
            'schedule_items' => ['nullable', 'array'],
            'schedule_items.*.time' => ['nullable', 'string', 'max:32'],
            'schedule_items.*.label' => ['nullable', 'string', 'max:500'],
            'meta_tips' => ['nullable', 'string', 'max:5000'],
            'meta_dress_code' => ['nullable', 'string', 'max:500'],
            'pricing_discount_code' => ['nullable', 'string', 'max:64'],
            'pricing_discount_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'batches' => ['nullable', 'array'],
            'batches.*.id' => ['nullable', 'integer'],
            'batches.*.name' => ['nullable', 'string', 'max:120'],
            'batches.*.price' => ['nullable', 'numeric', 'min:0'],
            'batches.*.sale_starts_at' => ['nullable', 'date'],
            'batches.*.sale_ends_at' => ['nullable', 'date'],
            'batches.*.capacity' => ['nullable', 'integer', 'min:1'],
            'batches.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'requires_council_approval' => $this->boolean('requires_council_approval'),
        ]);
    }
}
