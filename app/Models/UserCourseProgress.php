<?php

namespace App\Models;

class UserCourseProgress extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_course_progresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'chapter_id',
        'lesson_id',
        'user_id',
        'completed_at',
    ];
}
