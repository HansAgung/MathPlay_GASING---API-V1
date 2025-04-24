<?php

use App\Http\Controllers\Api\v1\Auth\AuthUserController;
use App\Http\Controllers\Api\v1\Auth\AuthAdminController;

Route::prefix('v1')->group(function () {
    Route::post('register', [AuthUserController::class, 'register']);
    Route::post('login', [AuthUserController::class, 'login']);
    Route::post('forgot-password', [AuthUserController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthUserController::class, 'logout']);
        Route::get('profile', [AuthUserController::class, 'profile']);
    });
});

Route::prefix('v1/admin')->group(function () {
    Route::post('register', [AuthAdminController::class, 'register']);
    Route::post('login', [AuthAdminController::class, 'login']);
    Route::post('forgot-password', [AuthAdminController::class, 'forgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthAdminController::class, 'logout']);
    });
});

