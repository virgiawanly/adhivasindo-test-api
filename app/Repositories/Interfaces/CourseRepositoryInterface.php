<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface CourseRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Find data by slug.
     *
     * @param  string $slug
     * @param  int|null $excludeId
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findBySlug(string $slug, int|null $excludeId = null): ?Model;

    /**
     * Find published course.
     *
     * @param  int $id
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findPublished(int $id, array $relations = []): ?Model;

    /**
     * Get all user's courses with pagination.
     *
     * @param int $userId
     * @param int $perPage
     * @param array $queryParams
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function userCoursePaginatedList(int $userId, int $perPage, array $queryParams = [], array $relations = []): LengthAwarePaginator;

    /**
     * Find user course with its chapters, lessons, and user's progress.
     *
     * @param  int $userId
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function viewUserCourse(int $userId, int $id): Model;

    /**
     * Enroll a course.
     *
     * @param  int $userId
     * @param  int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function enrollCourse(int $userId, int $id): Model;

    /**
     * Get popular courses.
     *
     * @param  int $perPage
     * @param  array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPopularCourses(int $perPage, array $relations = []): LengthAwarePaginator;

    /**
     * Get recently added courses.
     *
     * @param  int $perPage
     * @param  array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getRecentlyAddedCourses(int $perPage, array $relations = []): LengthAwarePaginator;

    /**
     * Get user's recently accessed courses.
     *
     * @param  int $userId
     * @param  int $perPage
     * @param  array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserRecentlyAccessedCourses(int $userId, int $perPage, array $relations = []): LengthAwarePaginator;
}
