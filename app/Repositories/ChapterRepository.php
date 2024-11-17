<?php

namespace App\Repositories;

use App\Models\Chapter;
use App\Repositories\Interfaces\ChapterRepositoryInterface;

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
