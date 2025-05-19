<?php

//Address untuk fix-produk
use App\Http\Controllers\Api\v1\Auth\AuthUserController;
use App\Http\Controllers\Api\v1\Auth\AuthAdminController;
use App\Http\Controllers\Api\v1\BadgesController;
use Illuminate\Support\Facades\Route;

//Address untuk test
use App\Http\Controllers\Api\test\AuthMockController;
use App\Http\Controllers\Api\test\QuestMockController;
use App\Http\Controllers\Api\test\LeaderboardMockController;  
use App\Http\Controllers\Api\test\OptionTestController;
use App\Http\Controllers\Api\test\SubjectMatterController;
use App\Http\Controllers\Api\test\InputTestController;

//Set route untuk fix-produk 
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

Route::prefix('v1')->group(function () {
    Route::prefix('badges')->group(function () {
        Route::get('/', [BadgesController::class, 'index']);         
        Route::post('/', [BadgesController::class, 'store']);        
        Route::get('{id}', [BadgesController::class, 'show']);       
        Route::post('updateBadges/{id}', [BadgesController::class, 'update']);     
        Route::delete('{id}', [BadgesController::class, 'destroy']); 
    });
});

//Set route untuk test
Route::get('/mock-login', [AuthMockController::class, 'mockLogin']);
Route::get('/mock/quest', [QuestMockController::class, 'QuestMock']);
Route::get('/mock/leaderboard', [LeaderboardMockController::class, 'leaderboard']);
Route::get('/mock/quest/optionTest', [OptionTestController::class, 'getOptionTest']);
Route::get('/mock/quest/subject-matter',[SubjectMatterController::class, 'getVideoPembelajaran']);
Route::get('/mock/quest/inputTest',[InputTestController::class, 'getInputTest']);

