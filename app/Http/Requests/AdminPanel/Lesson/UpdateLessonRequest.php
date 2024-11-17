<?php

namespace App\Http\Requests\AdminPanel\Lesson;

use App\Enums\LessonType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
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
            'course_id' => ['required', 'exists:courses,id,deleted_at,NULL'],
            'chapter_id' => ['required', 'exists:chapters,id,deleted_at,NULL'],
            'title' => ['required', 'max:255'],
            'type' => ['required', Rule::in(array_column(LessonType::cases(), 'value'))],
            'video_url' => ['required_if:type,' . LessonType::Video->value, 'url'],
            'video_duration' => ['nullable'],
            'text_content' => ['required_if:type,' . LessonType::Text->value],
        ];
    }
}
