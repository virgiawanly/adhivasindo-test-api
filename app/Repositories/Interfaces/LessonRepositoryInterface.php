<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface LessonRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Increment order of lessons in the given range.
     *
     * @param int $chapterId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function incrementOrderInRange(int $chapterId, int $start, int $end): void;

    /**
     * Decrement order of lessons in the given range.
     *
     * @param int $chapterId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function decrementOrderInRange(int $chapterId, int $start, int $end): void;

    /**
     * Update lesson order.
     *
     * @param int $chapterId
     * @param int $lessonId
     * @param int $newOrder
     * @return void
     */
    public function updateOrder(int $chapterId, int $lessonId, int $newOrder): void;

    /**
     * Get the total number of lessons in a chapter.
     *
     * @param int $chapterId
     * @return int
     */
    public function getLessonsCountByChapter(int $chapterId): int;

    /**
     * Mark lesson as completed or incomplete.
     *
     * @param  int $userId
     * @param  int $lessonId
     * @param  bool $isCompleted
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateUserLessonProgress(int $userId, int $lessonId, bool $isCompleted): Model;
}
