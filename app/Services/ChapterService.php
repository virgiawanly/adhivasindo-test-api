<?php

namespace App\Services;

use App\Repositories\Interfaces\ChapterRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ChapterService extends BaseResourceService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\ChapterRepositoryInterface  $repository
     * @return void
     */
    public function __construct(ChapterRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\ChapterRepositoryInterface
     */
    public function repository(): ChapterRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Get all chapters by course.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function listByCourse(int $courseId, array $queryParams, array $relations = []): Collection
    {
        $queryParams['course_id'] = $courseId;

        return $this->repository->list($queryParams, $relations);
    }

    /**
     * Get paginated chapters by course.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedListByCourse(int $courseId, array $queryParams, array $relations = []): LengthAwarePaginator
    {
        $queryParams['course_id'] = $courseId;
        $size = $queryParams['size'] ?? $this->defaultPageSize;

        return $this->repository->paginatedList($size, $queryParams, $relations);
    }

    /**
     * Create a new resource.
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function saveChapter(array $payload): Model
    {
        $totalChapters = $this->repository()->getChaptersCountByCourse($payload['course_id']);

        $payload['order'] = $totalChapters + 1;

        return parent::save($payload);
    }

    /**
     * Reorder chapters.
     *
     * @param  array $payload
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function reorder(array $payload): Model
    {
        $totalChapters = $this->repository()->getChaptersCountByCourse($payload['course_id']);

        if ($payload['order'] > $totalChapters || $payload['order'] < 1) {
            throw new BadRequestException('Invalid order: The order must be between 1 and ' . $totalChapters . '.');
        }

        $chapter = $this->repository()->find($payload['chapter_id']);

        if ($payload['order'] > $chapter->order) {
            $this->repository()->decrementOrderInRange(
                $payload['course_id'],
                $chapter->order + 1,
                $payload['order']
            );
        } elseif ($payload['order'] < $chapter->order) {
            $this->repository()->incrementOrderInRange(
                $payload['course_id'],
                $payload['order'],
                $chapter->order - 1
            );
        }

        $chapter->update(['order' => $payload['order']]);

        return $chapter;
    }
}
