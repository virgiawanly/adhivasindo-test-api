<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Interfaces\LessonRepositoryInterface;

class LessonRepository extends BaseResourceRepository implements LessonRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Lesson();
    }

    /**
     * Increment order of lessons in the given range.
     *
     * @param int $chapterId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function incrementOrderInRange(int $chapterId, int $start, int $end): void
    {
        $this->model
            ->where('chapter_id', $chapterId)
            ->whereBetween('order', [$start, $end])
            ->increment('order');
    }

    /**
     * Decrement order of lessons in the given range.
     *
     * @param int $chapterId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function decrementOrderInRange(int $chapterId, int $start, int $end): void
    {
        $this->model
            ->where('chapter_id', $chapterId)
            ->whereBetween('order', [$start, $end])
            ->decrement('order');
    }

    /**
     * Update lesson order.
     *
     * @param int $chapterId
     * @param int $lessonId
     * @param int $newOrder
     * @return void
     */
    public function updateOrder(int $chapterId, int $lessonId, int $newOrder): void
    {
        $this->model
            ->where('chapter_id', $chapterId)
            ->where('id', $lessonId)
            ->update(['order' => $newOrder]);
    }

    /**
     * Get the total number of lessons in a chapter.
     *
     * @param int $chapterId
     * @return int
     */
    public function getLessonsCountByChapter(int $chapterId): int
    {
        return $this->model
            ->where('chapter_id', $chapterId)
            ->count();
    }
}
