<?php

namespace App\Http\Controllers\AdminPanel\User;

use App\Http\Controllers\BaseResourceController;
use App\Services\UserService;

class UserController extends BaseResourceController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected UserService $userService)
    {
        parent::__construct($userService->repository);
    }
}
