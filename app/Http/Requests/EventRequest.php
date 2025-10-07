<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'string', 'in:webinar,workshop,seminar,activity,conference'],
            'event_start_date' => ['required', 'date', 'after_or_equal:today'],
            'event_end_date' => ['required', 'date', 'after_or_equal:event_start_date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'max_attendees' => ['nullable', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'end_time.after' => 'The end time must be after the start time.',
            'event_start_date.after_or_equal' => 'The start date must be today or in the future.',
        ];
    }
}
