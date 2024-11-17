<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * The attributes that are searchable in the query.
     *
     * @var array<int, string>
     */
    protected $searchables = [
        'title',
    ];

    /**
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [
        'course_id' => '=',
        'chapter_id' => '=',
        'title' => 'like',
    ];

    /**
     * The columns that are sortable in the query.
     *
     * @var array<int, string>
     */
    protected $sortableColumns = [
        'title',
        'order',
    ];

    /**
     * Get the lesson user progresses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userProgresses(): HasMany
    {
        return $this->hasMany(UserCourseProgress::class);
    }
}
