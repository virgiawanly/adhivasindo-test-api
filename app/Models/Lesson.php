<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lessons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'chapter_id',
        'title',
        'type',
        'video_url',
        'video_duration',
        'text_content',
        'order'
    ];

    /**
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [
        'course_id' => '=',
        'title' => 'like',
    ];
}
