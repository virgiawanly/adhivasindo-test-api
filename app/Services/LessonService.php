<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class LessonService extends BaseResourceService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\LessonRepositoryInterface  $repository
     * @return void
     */
    public function __construct(LessonRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\LessonRepositoryInterface
     */
    public function repository(): LessonRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Get all lessons by chapter.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listByChapter(int $chapterId, array $queryParams, array $relations = []): Collection
    {
        $chapter = app()->make(ChapterRepositoryInterface::class)->find($chapterId);

        $queryParams['chapter_id'] = $chapter->id;

        return $this->repository->list($queryParams, $relations);
    }

    /**
     * Get paginated lessons by chapter.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedListByChapter(int $chapterId, array $queryParams, array $relations = []): LengthAwarePaginator
    {
        $chapter = app()->make(ChapterRepositoryInterface::class)->find($chapterId);

        $queryParams['chapter_id'] = $chapter->id;

        $size = $queryParams['size'] ?? $this->defaultPageSize;

        return $this->repository->paginatedList($size, $queryParams, $relations);
    }

    /**
     * Create a new resource.
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function saveLesson(array $payload): Model
    {
        $totalLessonsInChapter = $this->repository()->getLessonsCountByChapter($payload['chapter_id']);

        $payload['order'] = $totalLessonsInChapter + 1;

        return parent::save($payload);
    }

    /**
     * Reorder lessons.
     *
     * @param  array $payload
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function reorder(array $payload): Model
    {
        $totalLessons = $this->repository()->getLessonsCountByChapter($payload['chapter_id']);

        if ($payload['order'] > $totalLessons || $payload['order'] < 1) {
            throw new BadRequestException('Invalid order: The order must be between 1 and ' . $totalLessons . '.');
        }

        $lesson = $this->repository()->find($payload['lesson_id']);

        if ($payload['order'] > $lesson->order) {
            $this->repository()->decrementOrderInRange(
                $payload['chapter_id'],
                $lesson->order + 1,
                $payload['order']
            );
        } elseif ($payload['order'] < $lesson->order) {
            $this->repository()->incrementOrderInRange(
                $payload['chapter_id'],
                $payload['order'],
                $lesson->order - 1
            );
        }

        $lesson->update(['order' => $payload['order']]);

        return $lesson;
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
        return $this->repository()->updateUserLessonProgress($userId, $lessonId, $isCompleted);
    }
}
