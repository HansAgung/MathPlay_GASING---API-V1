<?php

//Address untuk fix-produk
use App\Http\Controllers\Api\v1\Auth\AuthUserController;
use App\Http\Controllers\Api\v1\Auth\AuthAdminController;
use App\Http\Controllers\Api\v1\BadgesController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\Learning\LearningSubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Learning\LearningModulesController;
use App\Http\Controllers\Api\v1\Learning\LearningUnitController;
use App\Http\Controllers\Api\v1\Learning\Asset_Modules\InputQuizController;

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
        Route::get('/history/{id}', [UserController::class, 'getHistoryLessonUser']);
    });

    Route::prefix('learning')->group(function () {
        //Set route untuk learning subject
        Route::prefix('learning-subject')->group(function () {
            Route::get('/', [LearningSubjectController::class, 'showLearningSubjects']);
            Route::post('/', [LearningSubjectController::class, 'addLearningSubjects']);
            Route::post('{id}', [LearningSubjectController::class, 'updateLearningSubject']);
            Route::delete('{id}',[LearningSubjectController::class, 'deleteLearningSubject']);
        }); 

        //Set route untuk learning modules
        Route::prefix('learning-modules')->group(function () {
            Route::get('/', [LearningModulesController::class, 'getLearningModules']);
            Route::post('/', [LearningModulesController::class, 'addLearningModules']);
            Route::post('/updateModule/{id}', [LearningModulesController::class, 'editLearningModules']);
            Route::delete('/deleteModule/{id}', [LearningModulesController::class, 'deleteLearningModules']);
        });
        
        Route::prefix('learning-units')->group(function () {
            Route::get('/units', [LearningUnitController::class, 'index']);
            Route::post('/units', [LearningUnitController::class, 'store']);
            Route::get('/units/{id}', [LearningUnitController::class, 'show']);
            Route::put('/units/{id}', [LearningUnitController::class, 'update']);
            Route::delete('/units/{id}', [LearningUnitController::class, 'destroy']);
        });

        Route::prefix('asset-modules')->group(function () {
            Route::prefix('input-quizzes')->group(function () {
                Route::get('/',[InputQuizController::class, 'showQuiz']);
                Route::post('/', [InputQuizController::class, 'store']);
                Route::put('/{id}', [InputQuizController::class, 'update']);
                Route::delete('/{id}', [InputQuizController::class, 'destroy']);
            });

            Route::prefix('option-quizzes')->group(function () {
                Route::post('/', [OptionQuizController::class, 'store']);
                Route::put('/{id}', [OptionQuizController::class, 'update']);
                Route::delete('/{id}', [OptionQuizController::class, 'destroy']);
            });

            Route::prefix('video-lessons')->group(function () {
                Route::post('/', [VideoLessonController::class, 'store']);
                Route::put('/{id}', [VideoLessonController::class, 'update']);
                Route::delete('/{id}', [VideoLessonController::class, 'destroy']);
            });

            Route::prefix('flashcard-games')->group(function () {
                Route::post('/', [FlashcardGameController::class, 'store']);
                Route::put('/{id}', [FlashcardGameController::class, 'update']);
                Route::delete('/{id}', [FlashcardGameController::class, 'destroy']);
            });

            Route::prefix('flashcard-cards')->group(function () {
                Route::post('/', [FlashcardCardController::class, 'store']);
                Route::put('/{id}', [FlashcardCardController::class, 'update']);
                Route::delete('/{id}', [FlashcardCardController::class, 'destroy']);
            });
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





