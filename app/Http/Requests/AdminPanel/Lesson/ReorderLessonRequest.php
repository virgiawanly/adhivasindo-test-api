<?php

namespace App\Http\Requests\AdminPanel\Lesson;

use Illuminate\Foundation\Http\FormRequest;

class ReorderLessonRequest extends FormRequest
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
            'chapter_id' => ['required', 'exists:chapters,id'],
            'lesson_id' => ['required', 'exists:lessons,id'],
            'order' => ['required', 'numeric'],
        ];
    }
}
