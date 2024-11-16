<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends BaseResourceRepositoryInterface
{
    /**
     * Find data by email.
     *
     * @param  string $email
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByEmail(string $email): ?Model;
}
