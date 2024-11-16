<?php

use App\Http\Controllers\AdminPanel\Auth\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});
