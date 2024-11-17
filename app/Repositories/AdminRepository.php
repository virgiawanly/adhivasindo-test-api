<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AdminRepository extends BaseResourceRepository implements AdminRepositoryInterface
{
    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Admin();
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
