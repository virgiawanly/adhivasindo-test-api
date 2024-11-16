<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Auth\UserLoginRequest;
use App\Services\Auth\UserAuthService;

class UserAuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected UserAuthService $userAuthService) {}

    /**
     * Login an user user by creating an access token.
     *
     * @param  \App\Http\Requests\Mobile\Auth\UserLoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request)
    {
        $results = $this->userAuthService->login($request->validated());

        return ResponseHelper::success(trans('messages.successfully_logged_in'), $results, 200);
    }
}
