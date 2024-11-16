<?php

use App\Http\Controllers\AdminPanel\Auth\AdminAuthController;
use App\Http\Controllers\AdminPanel\Course\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['auth:admin'])->group(function () {
    Route::apiResource('courses', CourseController::class);
});
