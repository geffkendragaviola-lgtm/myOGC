<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:webinar,workshop,seminar,activity,conference',
            'event_start_date' => 'required|date|after_or_equal:today',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'is_required' => 'boolean',
            'for_all_colleges' => 'boolean',
            'colleges' => 'required_if:for_all_colleges,false|array',
            'colleges.*' => 'exists:colleges,id',
        ];

        // Add image validation for create and update
        if ($this->isMethod('POST')) {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'colleges.required_if' => 'Please select at least one college when not choosing "All Colleges"',
            'image.image' => 'The file must be an image',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'The image may not be greater than 2MB',
        ];
    }
}
