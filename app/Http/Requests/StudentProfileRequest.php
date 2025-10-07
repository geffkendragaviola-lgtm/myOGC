<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === 'student';
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'string', 'max:50'],
            'year_level' => ['required', 'string', 'in:1st Year,2nd Year,3rd Year,4th Year,5th Year,Graduate'],
            'college_id' => ['required', 'exists:colleges,id'],
        ];
    }
}
