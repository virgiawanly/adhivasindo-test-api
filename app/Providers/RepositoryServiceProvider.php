<?php

namespace App\Providers;

use App\Repositories\AdminRepository;
use App\Repositories\ChapterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\ChapterRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\LessonRepositoryInterface;
use App\Repositories\Interfaces\ToolRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\LessonRepository;
use App\Repositories\ToolRepository;
use App\Repositories\UserRepository;
use App\Services\Auth\AdminAuthService;
use App\Services\Auth\UserAuthService;
use App\Services\ChapterService;
use App\Services\CourseService;
use App\Services\LessonService;
use App\Services\ToolService;
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

        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseService::class, function () {
            return new CourseService($this->app->make(CourseRepositoryInterface::class));
        });

        $this->app->bind(ChapterRepositoryInterface::class, ChapterRepository::class);
        $this->app->bind(ChapterService::class, function () {
            return new ChapterService($this->app->make(ChapterRepositoryInterface::class));
        });

        $this->app->bind(LessonRepositoryInterface::class, LessonRepository::class);
        $this->app->bind(LessonService::class, function () {
            return new LessonService($this->app->make(LessonRepositoryInterface::class));
        });

        $this->app->bind(ToolRepositoryInterface::class, ToolRepository::class);
        $this->app->bind(ToolService::class, function () {
            return new ToolService($this->app->make(ToolRepositoryInterface::class));
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
