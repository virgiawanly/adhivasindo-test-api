<?php

namespace App\Services\Auth;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAuthService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\UserRepositoryInterface  $userRepository
     * @return void
     */
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    /**
     * Login user by creating JWT access and refresh token.
     *
     * @param  array $data
     * @return array
     */
    public function loginJWT(array $data)
    {
        try {
            $token = JWTAuth::attempt($data);

            if (!$token) {
                throw new UnauthorizedException('Invalid credentials');
            }
        } catch (JWTException $e) {
            throw new UnauthorizedException('Could not create token');
        }

        $accessToken = $token;
        $refreshToken = JWTAuth::fromUser(Auth::user(), ['type' => 'refresh']);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    /**
     * Refresh JWT token.
     *
     * @param  array $data
     * @return array
     */
    public function refreshJWT(array $data)
    {
        try {
            $refreshToken = $data['refresh_token'];
            $newToken = JWTAuth::setToken($refreshToken)->refresh();

            return ['access_token' => $newToken];
        } catch (JWTException $e) {
            throw new UnauthorizedException('Token is invalid');
        }
    }

    /**
     * Register a new user and create an access token.
     *
     * @param  array $data
     * @return array
     */
    public function register(array $data)
    {
        $user = $this->userRepository->save($data);

        $accessToken = JWTAuth::fromUser($user);
        $refreshToken = JWTAuth::fromUser($user, ['type' => 'refresh']);

        return [
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }
}
