<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseResourceRepositoryInterface
{
    /**
     * Get all resources.
     *
     * @param  array $queryParams
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(array $queryParams = [], array $relations = []): Collection;

    /**
     * Get all resources with pagination.
     *
     * @param int $perPage
     * @param array $queryParams
     * @param array $relations
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginatedList(int $perPage, array $queryParams = [], array $relations = []): LengthAwarePaginator;

    /**
     * Get a resource by id.
     *
     * @param  int $id
     * @param  array $relations
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id, array $relations = []): Model;

    /**
     * Create a new resource.
     *
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function save(array $data): Model;

    /**
     * Update a resource.
     *
     * @param  int $id
     * @param  array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete a resource.
     *
     * @param  int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
