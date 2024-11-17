<?php

namespace App\Http\Requests\AdminPanel\Chapter;

use Illuminate\Foundation\Http\FormRequest;

class CreateChapterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'course_id.required' => trans('validation.required', ['attribute' => 'course']),
            'course_id.exists' => trans('validation.exists', ['attribute' => 'course']),
        ];
    }
}
