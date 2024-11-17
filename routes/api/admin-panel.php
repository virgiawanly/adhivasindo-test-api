<?php

use App\Http\Controllers\AdminPanel\Auth\AdminAuthController;
use App\Http\Controllers\AdminPanel\Chapter\ChapterController;
use App\Http\Controllers\AdminPanel\Course\CourseController;
use App\Http\Controllers\AdminPanel\Lesson\LessonController;
use App\Http\Controllers\AdminPanel\Tool\ToolController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['auth:admin'])->group(function () {
    Route::get('courses/{course}/chapters', [CourseController::class, 'chapters']);
    Route::apiResource('courses', CourseController::class);

    Route::patch('chapters/reorder', [ChapterController::class, 'reorder']);
    Route::get('chapters/{chapter}/lessons', [ChapterController::class, 'lessons']);
    Route::apiResource('chapters', ChapterController::class);

    Route::patch('lessons/reorder', [LessonController::class, 'reorder']);
    Route::apiResource('lessons', LessonController::class);

    Route::apiResource('tools', ToolController::class);
});
