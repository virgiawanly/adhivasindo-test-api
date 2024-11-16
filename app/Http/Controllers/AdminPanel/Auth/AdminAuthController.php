<?php

namespace App\Http\Controllers\AdminPanel\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPanel\Auth\AdminLoginRequest;
use App\Services\Auth\AdminAuthService;

class AdminAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected AdminAuthService $adminAuthService) {}

    /**
     * Login an admin user by creating an access token.
     *
     * @param  \App\Http\Requests\AdminPanel\Auth\AdminLoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AdminLoginRequest $request)
    {
        $results = $this->adminAuthService->login($request->validated());

        return ResponseHelper::success(trans('messages.successfully_logged_in'), $results, 200);
    }
}
