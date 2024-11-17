<?php

use App\Http\Controllers\Mobile\Auth\UserAuthController;
use App\Http\Controllers\Mobile\Course\AllCourseController;
use App\Http\Controllers\Mobile\Course\UserCourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::apiResource('courses', AllCourseController::class)->only(['index', 'show']);

Route::middleware(['auth:user'])->group(function () {
    Route::post('courses/{course}/enroll', [AllCourseController::class, 'enrollCourse']);

    Route::prefix('user-courses')->group(function () {
        Route::get('/', [UserCourseController::class, 'userCourses']);
        Route::patch('/progress', [UserCourseController::class, 'updateLessonProgress']);
        Route::get('/{course}', [UserCourseController::class, 'viewCourse']);
    });
});
