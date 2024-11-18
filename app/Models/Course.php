<?php

namespace App\Models;

use App\Enums\CourseStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'status',
        'description',
        'image',
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'image_url',
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
     * The columns that are searchable in the query.
     *
     * @var array<string, string>
     */
    protected $searchableColumns = [
        'name' => 'like',
        'status' => '=',
    ];

    /**
     * The columns that are sortable in the query.
     *
     * @var array<int, string>
     */
    protected $sortableColumns = [
        'name',
        'slug',
        'status',
    ];

    /**
     * The custom searchables query.
     *
     * @return array
     */
    public function getCustomSearchables(): array
    {
        return [
            'tools' => function ($query, $value) {
                $query->whereHas('tools', function ($query) use ($value) {
                    $query->where('tools.name', 'like', '%' . $value . '%');
                });
            }
        ];
    }

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
     * Get the chapters of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * Get the tools of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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

    /**
     * Get the users that enrolled the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_courses', 'course_id', 'user_id')
            ->withTimestamps()
            ->withPivot('deleted_at');
    }

    /**
     * Get the image URL attribute.
     *
     * @return string|null
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::url($this->image) : null;
    }

    /**
     * Get the lessons of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the user progresses of the course.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userProgresses(): HasMany
    {
        return $this->hasMany(UserCourseProgress::class);
    }
}
