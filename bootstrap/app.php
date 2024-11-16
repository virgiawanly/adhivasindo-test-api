<?php

use App\Helpers\ResponseHelper;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api/admin-panel')->middleware('api')->group(base_path('routes/api/admin-panel.php'));
            Route::prefix('api/mobile')->middleware('api')->group(base_path('routes/api/mobile.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    // Send default response
                } else if ($e instanceof AuthenticationException) {
                    return ResponseHelper::unauthenticated($e->getMessage());
                } else if ($e instanceof UnauthorizedException) {
                    return ResponseHelper::forbidden($e->getMessage());
                } else if ($e instanceof ModelNotFoundException) {
                    return ResponseHelper::notFound($e->getMessage());
                } else {
                    return ResponseHelper::internalServerError($e->getMessage(), $e);
                }
            }
        });
    })->create();
