<?php

namespace App\Services\Auth;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

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
     * Login user by creating an access token.
     *
     * @param  array $data
     * @return array
     */
    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if (empty($user) || !Hash::check($data['password'], $user->password)) {
            throw new AuthenticationException('Invalid email or password.');
        }

        return [
            'user' => $user,
            'token' => $user->createToken('mobileAppToken')->plainTextToken
        ];
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

        return [
            'user' => $user,
            'token' => $user->createToken('mobileAppToken')->plainTextToken
        ];
    }
}
