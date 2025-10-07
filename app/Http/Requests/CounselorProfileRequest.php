<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CounselorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'counselor';
    }

    public function rules(): array
    {
        return [
            'position' => ['required', 'string', 'max:255'],
            'credentials' => ['required', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:500'],
            'college_id' => ['required', 'exists:colleges,id'],
        ];
    }
}
