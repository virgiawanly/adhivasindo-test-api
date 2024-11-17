<?php

namespace App\Services\Auth;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    /**
     * Create a new service instance.
     *
     * @param  \App\Repositories\Interfaces\AdminRepositoryInterface  $adminRepository
     * @return void
     */
    public function __construct(protected AdminRepositoryInterface $adminRepository) {}

    /**
     * Login user by creating an access token.
     *
     * @param  array $data
     * @return array
     */
    public function login(array $data)
    {
        $admin = $this->adminRepository->findByEmail($data['email']);

        if (empty($admin) || !Hash::check($data['password'], $admin->password)) {
            throw new AuthenticationException('Invalid email or password.');
        }

        return [
            'user' => $admin,
            'token' => $admin->createToken('adminPanelToken')->plainTextToken
        ];
    }

    /**
     * Get the authenticated admin profile.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function getProfile()
    {
        return [
            'user' => Auth::user(),
        ];
    }
}
