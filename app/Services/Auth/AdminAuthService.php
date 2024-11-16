<?php

namespace App\Services\Auth;

use App\Repositories\Interfaces\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

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
     * @param array $data
     * @return \App\Models\User
     */
    public function login(array $data)
    {
        $admin = $this->adminRepository->findByEmail($data['email']);

        if (empty($admin) || !Hash::check($data['password'], $admin->password)) {
            throw new UnauthorizedException('Invalid email or password.');
        }

        return [
            'user' => $admin,
            'token' => $admin->createToken('adminPanelToken')->plainTextToken
        ];
    }
}
