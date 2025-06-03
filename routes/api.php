<?php

//Address untuk fix-produk
use App\Http\Controllers\Api\v1\Auth\AuthUserController;
use App\Http\Controllers\Api\v1\Auth\AuthAdminController;
use App\Http\Controllers\Api\v1\BadgesController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\Learning\LearningSubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Learning\LearningModulesController;

//Address untuk test
use App\Http\Controllers\Api\test\AuthMockController;
use App\Http\Controllers\Api\test\QuestMockController;
use App\Http\Controllers\Api\test\LeaderboardMockController;  
use App\Http\Controllers\Api\test\OptionTestController;
use App\Http\Controllers\Api\test\SubjectMatterController;
use App\Http\Controllers\Api\test\InputTestController;
use App\Http\Controllers\Api\test\FlashCardController;

//Set route untuk user
Route::prefix('v1')->group(function () {
    Route::prefix('auth-user')->group(function () {
        Route::post('register', [AuthUserController::class, 'register']);
        Route::post('login', [AuthUserController::class, 'login']);
        Route::post('forgot-password', [AuthUserController::class, 'forgotPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthUserController::class, 'logout']);
        });
    });

    //Set route untuk admin
    Route::prefix('auth-admin')->group(function () {
        Route::post('register', [AuthAdminController::class, 'register']);
        Route::post('login', [AuthAdminController::class, 'login']);
        Route::post('forgot-password', [AuthAdminController::class, 'forgotPassword']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', [AuthAdminController::class, 'logout']);
        });
    });

    //Set route untuk badges
     Route::prefix('badges')->group(function () {
        Route::get('/', [BadgesController::class, 'showAllBadges']);         
        Route::post('/', [BadgesController::class, 'storeBadges']);              
        Route::post('updateBadges/{id}', [BadgesController::class, 'updateBadges']);     
        Route::delete('{id}', [BadgesController::class, 'destroyBadges']); 
    });

    //Set route untuk leaderboard
    Route::prefix('users')->group(function () {
        Route::get('/getUsers', [UserController::class, 'getAllUsers']);
        Route::get('/getUsers/{id}', [UserController::class, 'getAllUsersById']);
    });

    //Set route untuk learning subject
    Route::prefix('learning')->group(function () {
        Route::prefix('learning-subject')->group(function () {
            Route::get('/', [LearningSubjectController::class, 'showLearningSubjects']);
            Route::post('/', [LearningSubjectController::class, 'addLearningSubjects']);
            Route::post('{id}', [LearningSubjectController::class, 'updateLearningSubject']);
            Route::delete('{id}',[LearningSubjectController::class, 'deleteLearningSubject']);
        }); 
    });

    //Set route untuk learning modules
    Route::prefix('learning')->group(function () {
        Route::prefix('learning-modules')->group(function () {
            Route::get('/', [LearningModulesController::class, 'getLearningModules']);
            Route::post('/', [LearningModulesController::class, 'addLearningModules']);
        }); 
    });
});

//Set route untuk test 
Route::prefix('mock')->group(function () {
    Route::get('/mock-login', [AuthMockController::class, 'mockLogin']);
    Route::get('quest', [QuestMockController::class, 'QuestMock']);
    Route::get('leaderboard', [LeaderboardMockController::class, 'leaderboard']);
    Route::get('quest/optionTest', [OptionTestController::class, 'getOptionTest']);
    Route::get('quest/subject-matter',[SubjectMatterController::class, 'getVideoPembelajaran']);
    Route::get('quest/inputTest',[InputTestController::class, 'getInputTest']);
    Route::get('quest/flashCard',[FlashCardController::class, 'getFlashCard']);
});





