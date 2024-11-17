<?php

namespace App\Repositories\Interfaces;

interface ChapterRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Increment order of chapters in the given range.
     *
     * @param int $courseId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function incrementOrderInRange(int $courseId, int $start, int $end): void;

    /**
     * Decrement order of chapters in the given range.
     *
     * @param int $courseId
     * @param int $start
     * @param int $end
     * @return void
     */
    public function decrementOrderInRange(int $courseId, int $start, int $end): void;

    /**
     * Update chapter order.
     *
     * @param int $courseId
     * @param int $chapterId
     * @param int $newOrder
     * @return void
     */
    public function updateOrder(int $courseId, int $chapterId, int $newOrder): void;

    /**
     * Get the total number of chapters in a course.
     *
     * @param int $courseId
     * @return int
     */
    public function getChaptersCountByCourse(int $courseId): int;
}
