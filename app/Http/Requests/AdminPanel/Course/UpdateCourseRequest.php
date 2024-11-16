<?php

namespace App\Http\Requests\AdminPanel\Course;

use App\Enums\CourseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'status' => ['nullable', 'sometimes', Rule::in(array_column(CourseStatus::cases(), 'value'))],
            'image' => ['nullable', 'image', 'max:2048'],
            'competencies' => ['nullable', 'array'],
            'competencies.*.name' => ['string', 'max:255'],
            'tool_ids' => ['nullable', 'array'],
            'tool_ids.*' => ['exists:tools,id'],
            'regenerate_slug' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'tool_ids' => 'tools',
            'tool_ids.*' => 'tools',
        ];
    }
}
