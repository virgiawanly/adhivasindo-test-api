<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends BaseModel
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
    ];

    /**
     * The attributes that are searchable in the query.
     *
     * @var array<int, string>
     */
    protected $searchables = [
        'name',
    ];

    /**
     * Get the competencies of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competencies()
    {
        return $this->hasMany(CourseCompetency::class);
    }

    /**
     * Get the tools of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'course_tools', 'course_id', 'tool_id');
    }

    /**
     * Scope a query to only include draft courses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', CourseStatus::Draft->value);
    }

    /**
     * Scope a query to only include published courses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', CourseStatus::Published->value);
    }
}
