<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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
     * Get all resources.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $queryParams = [], array $relations = []): Collection
    {
        $search = $queryParams['search'] ?? '';
        $sortBy = $queryParams['sort'] ?? '';
        $order = $queryParams['order'] ?? 'asc';
        $sortOrder = (str_contains($order, 'asc') ? 'asc' : 'desc') ?? '';
        $searchableColumns = $queryParams['searchable_columns'] ?? [];

        return $this->model
            ->when(count($relations), function ($query) use ($relations) {
                $query->with($relations);
            })
            ->when(!empty($queryParams['tool_id']), function ($query) use ($queryParams) {
                $query->whereHas('tools', function ($query) use ($queryParams) {
                    $query->where('tool_id', $queryParams['tool_id']);
                });
            })
            ->search($search, $searchableColumns)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->get();
    }

    /**
     * Get all resources with pagination.
     *
     * @param int $perPage
     * @param array $queryParams
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedList(int $perPage, array $queryParams = [], array $relations = []): LengthAwarePaginator
    {
        $search = $queryParams['search'] ?? '';
        $sortBy = $queryParams['sort'] ?? '';
        $order = $queryParams['order'] ?? 'asc';
        $sortOrder = (str_contains($order, 'asc') ? 'asc' : 'desc') ?? '';
        $searchableColumns = $queryParams['searchable_columns'] ?? [];

        return $this->model
            ->when(count($relations), function ($query) use ($relations) {
                $query->with($relations);
            })
            ->when(!empty($queryParams['tool_id']), function ($query) use ($queryParams) {
                $query->whereHas('tools', function ($query) use ($queryParams) {
                    $query->where('tool_id', $queryParams['tool_id']);
                });
            })
            ->search($search, $searchableColumns)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    /**
     * Find published course.
     *
     * @param  int $id
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findPublished(int $id, array $relations = []): ?Model
    {
        return $this->model
            ->published()
            ->when(count($relations), function ($query) use ($relations) {
                $query->with($relations);
            })->findOrFail($id);
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

    /**
     * Get all user's courses with pagination.
     *
     * @param int $perPage
     * @param array $queryParams
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function userCoursePaginatedList(int $userId, int $perPage, array $queryParams = [], array $relations = []): LengthAwarePaginator
    {
        $search = $queryParams['search'] ?? '';
        $sortBy = $queryParams['sort'] ?? '';
        $order = $queryParams['order'] ?? 'asc';
        $sortOrder = (str_contains($order, 'asc') ? 'asc' : 'desc') ?? '';
        $searchableColumns = $queryParams['searchable_columns'] ?? [];

        return $this->model
            ->published()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when(count($relations), function ($query) use ($relations) {
                $query->with($relations);
            })
            ->search($search, $searchableColumns)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    /**
     * Enroll a course.
     *
     * @param  int $userId
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function enrollCourse(int $userId, int $id): Model
    {
        $course = $this->model
            ->published()
            ->findOrFail($id);

        $alreadyEnrolled = $course->users()
            ->where('user_id', $userId)
            ->exists();

        if (!$alreadyEnrolled) {
            $course->users()->attach($userId);
        }

        return $course;
    }

    /**
     * Find user course with its chapters, lessons, and user's progress.
     *
     * @param  int $userId
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function viewUserCourse(int $userId, int $id): Model
    {
        $course = $this->model
            ->published()
            ->with([
                'chapters' => function ($query) use ($userId) {
                    $query->with('lessons', function ($query) use ($userId) {
                        $query->select('id', 'chapter_id', 'title', 'type', 'video_duration')
                            ->with(['userProgresses' => function ($query) use ($userId) {
                                $query
                                    ->select('id', 'lesson_id', 'completed_at')
                                    ->where('user_id', $userId);
                            }]);
                    });
                },
            ])
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->findOrFail($id);

        return $course;
    }
}
