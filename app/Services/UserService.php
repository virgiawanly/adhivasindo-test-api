<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;

class UserService extends BaseResourceService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\UserRepositoryInterface  $repository
     * @return void
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get repository instance.
     *
     * @return \App\Repositories\Interfaces\UserRepositoryInterface
     */
    public function repository(): UserRepositoryInterface
    {
        return $this->repository;
    }
}
