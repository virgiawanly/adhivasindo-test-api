<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseResourceRepository implements UserRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * Find data by email.
     *
     * @param  string $email
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByEmail(string $email): ?Model
    {
        return $this->model->where('email', $email)->first();
    }
}
