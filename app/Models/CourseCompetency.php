<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCompetency extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_competencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'name',
    ];
}
