<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

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
     * @param  int $chapterId
     * @param  int $start
     * @param  int $end
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
     * @param  int $chapterId
     * @param  int $start
     * @param  int $end
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
     * @param  int $chapterId
     * @param  int $lessonId
     * @param  int $newOrder
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
     * @param  int $chapterId
     * @return int
     */
    public function getLessonsCountByChapter(int $chapterId): int
    {
        return $this->model
            ->where('chapter_id', $chapterId)
            ->count();
    }

    /**
     * Mark lesson as completed or incomplete.
     *
     * @param  int $userId
     * @param  int $lessonId
     * @param  bool $isCompleted
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function updateUserLessonProgress(int $userId, int $lessonId, bool $isCompleted): Model
    {
        $lesson = $this->model->findOrFail($lessonId);

        $userProgress = $lesson->userProgresses()
            ->where('user_id', $userId)
            ->first();

        if ($userProgress && !$isCompleted) {
            $userProgress->delete();
        }

        if (!$userProgress && $isCompleted) {
            $lesson->userProgresses()->create([
                'course_id' => $lesson->course_id,
                'chapter_id' => $lesson->chapter_id,
                'lesson_id' => $lesson->id,
                'user_id' => $userId,
                'completed_at' => now()
            ]);
        }

        return $lesson;
    }
}
