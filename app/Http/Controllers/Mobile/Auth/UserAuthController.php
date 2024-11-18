<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mobile\Auth\UserLoginRequest;
use App\Http\Requests\Mobile\Auth\UserRegistrationRequest;
use App\Services\Auth\UserAuthService;
use Illuminate\Http\Request;

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
        $results = $this->userAuthService->loginJWT($request->only('email', 'password'));

        return ResponseHelper::success(trans('messages.successfully_logged_in'), $results, 200);
    }

    /**
     * Login an user user by creating an access token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken(Request $request)
    {
        $results = $this->userAuthService->refreshJWT($request->only('refresh_token'));

        return ResponseHelper::success(trans('messages.successfully_logged_in'), $results, 200);
    }

    /**
     * Register a new user and create an access token.
     *
     * @param  \App\Http\Requests\Mobile\Auth\UserRegistrationRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegistrationRequest $request)
    {
        $results = $this->userAuthService->register($request->validated());

        return ResponseHelper::success(trans('messages.successfully_registered'), $results, 201);
    }

    /**
     * Get the authenticated admin profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserProfile()
    {
        $results = $this->userAuthService->getProfile();

        return ResponseHelper::data($results, 200);
    }
}
