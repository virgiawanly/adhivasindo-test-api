<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\Auth\AdminAuthService;
use App\Services\Auth\UserAuthService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(AdminAuthService::class, function () {
            return new AdminAuthService($this->app->make(AdminRepositoryInterface::class));
        });

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserAuthService::class, function () {
            return new UserAuthService($this->app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
