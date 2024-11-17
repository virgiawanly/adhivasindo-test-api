<?php

namespace App\Services;

use App\Repositories\Interfaces\BaseResourceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseResourceService
{
    /**
     * Base resource repository instance.
     *
     * @var \App\Repositories\Interfaces\BaseResourceRepositoryInterface
     */
    public BaseResourceRepositoryInterface $repository;

    /**
     * Default pagination size.
     *
     * @var int
     */
    protected $defaultPageSize = 10;

    /**
     * Create a new controller instance.
     *
     * @param \App\Repositories\Interfaces\BaseResourceRepositoryInterface $repository
     */
    public function __construct(BaseResourceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\BaseResourceRepositoryInterface
     */
    public function repository(): BaseResourceRepositoryInterface
    {
        return $this->repository;
    }

    /**
     * Get all resources.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $queryParams, array $relations = []): Collection
    {
        return $this->repository->list($queryParams, $relations);
    }

    /**
     * Get paginated resources.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedList(array $queryParams, array $relations = []): LengthAwarePaginator
    {
        $size = $queryParams['size'] ?? $this->defaultPageSize;

        return $this->repository->paginatedList($size, $queryParams, $relations);
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
        return $this->repository->find($id, $relations);
    }

    /**
     * Create a new resource.
     *
     * @param array $payload
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(array $payload): Model
    {
        return $this->repository->save($payload);
    }

    /**
     * Update a resource.
     *
     * @param int $id
     * @param array $payload
     */
    public function patch(int $id, array $payload): Model
    {
        return $this->repository->update($id, $payload);
    }

    /**
     * Delete a resource.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
