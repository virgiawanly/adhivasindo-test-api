<?php

namespace App\Repositories;

use App\Enums\LessonType;
use App\Models\Chapter;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ChapterRepository extends BaseResourceRepository implements ChapterRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Chapter();
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
            ->withCount([
                'lessons AS total_lessons',
                'lessons AS total_video_lessons' => function ($query) {
                    $query->where('type', LessonType::Video->value);
                },
                'lessons AS total_text_lessons' => function ($query) {
                    $query->where('type', LessonType::Text->value);
                }
            ])
            ->when(count($relations), function ($query) use ($relations) {
                $query->with($relations);
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
            ->withCount([
                'lessons AS total_lessons',
                'lessons AS total_video_lessons' => function ($query) {
                    $query->where('type', LessonType::Video->value);
                },
                'lessons AS total_text_lessons' => function ($query) {
                    $query->where('type', LessonType::Text->value);
                }
            ])
            ->when(count($relations), function ($query) use ($relations) {
                $query->with($relations);
            })
            ->search($search, $searchableColumns)
            ->searchColumns($queryParams)
            ->ofOrder($sortBy, $sortOrder)
            ->paginate($perPage);
    }

    /**
     * Get a resource by id.
     *
     * @param  int $id
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id, array $relations = []): Model
    {
        return $this->model
            ->with($relations)
            ->withCount([
                'lessons AS total_lessons',
                'lessons AS total_video_lessons' => function ($query) {
                    $query->where('type', LessonType::Video->value);
                },
                'lessons AS total_text_lessons' => function ($query) {
                    $query->where('type', LessonType::Text->value);
                }
            ])
            ->findOrFail($id);
    }

    /**
     * Increment order of chapters in the given range.
     *
     * @param int $courseId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function incrementOrderInRange(int $courseId, int $start, int $end): void
    {
        $this->model
            ->where('course_id', $courseId)
            ->whereBetween('order', [$start, $end])
            ->increment('order');
    }

    /**
     * Decrement order of chapters in the given range.
     *
     * @param int $courseId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function decrementOrderInRange(int $courseId, int $start, int $end): void
    {
        $this->model
            ->where('course_id', $courseId)
            ->whereBetween('order', [$start, $end])
            ->decrement('order');
    }

    /**
     * Update chapter order.
     *
     * @param int $courseId
     * @param int $chapterId
     * @param int $newOrder
     * @return void
     */
    public function updateOrder(int $courseId, int $chapterId, int $newOrder): void
    {
        $this->model
            ->where('course_id', $courseId)
            ->where('id', $chapterId)
            ->update(['order' => $newOrder]);
    }

    /**
     * Get the total number of chapters in a course.
     *
     * @param int $courseId
     * @return int
     */
    public function getChaptersCountByCourse(int $courseId): int
    {
        return $this->model
            ->where('course_id', $courseId)
            ->count();
    }
}
