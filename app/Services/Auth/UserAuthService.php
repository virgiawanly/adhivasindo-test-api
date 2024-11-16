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
     * @param array $data
     * @return \App\Models\User
     */
    public function login(array $data)
    {
        $admin = $this->userRepository->findByEmail($data['email']);

        if (empty($admin) || !Hash::check($data['password'], $admin->password)) {
            throw new AuthenticationException('Invalid email or password.');
        }

        return [
            'user' => $admin,
            'token' => $admin->createToken('mobileAppToken')->plainTextToken
        ];
    }
}
