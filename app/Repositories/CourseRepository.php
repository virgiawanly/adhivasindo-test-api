<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class CourseRepository extends BaseResourceRepository implements CourseRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Course();
    }

    /**
     * Find data by slug.
     *
     * @param  string $slug
     * @param  int|null $excludeId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBySlug(string $slug, int|null $excludeId = null): ?Model
    {
        return $this->model
            ->where('slug', $slug)
            ->when(!empty($excludeId), function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->first();
    }
}
