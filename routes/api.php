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
use App\Http\Controllers\Api\v1\Learning\Asset_Modules\OptionQuizController;
use App\Http\Controllers\Api\v1\Learning\Asset_Modules\VideoLessonController;
use App\Http\Controllers\Api\v1\Learning\Asset_Modules\FlashCardGameController;
use App\Http\Controllers\Api\v1\Learning\Asset_Modules\FlashcardCardController;
use App\Http\Controllers\Api\v1\UserLessonHistoryController;
use App\Http\Controllers\Api\v1\UserModulesHistoryController;
use App\Http\Controllers\Api\v1\Learning\QuizScoreController;

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
        Route::get('getUserbyID/{id}', [AuthUserController::class, 'getUserById']);
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
        Route::get('/{id}',[BadgesController::class, 'getBadgesByID']);       
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
            Route::get('/user_subject/{id_learning_subjects}', [LearningModulesController::class, 'getModulesBySubjectId']);
            Route::post('/', [LearningModulesController::class, 'addLearningModules']);
            Route::post('/updateModule/{id}', [LearningModulesController::class, 'editLearningModules']);
            Route::delete('/deleteModule/{id}', [LearningModulesController::class, 'deleteLearningModules']);
        });
        
        //Set route untuk learning-units
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
                Route::get('/unit/{id_learning_units}', [InputQuizController::class, 'showQuizByModuleID']);
                Route::post('/', [InputQuizController::class, 'storeInputQuiz']);
                Route::post('/updateData/{id}', [InputQuizController::class, 'updateInputQuiz']);
                Route::delete('deleteData/{id}', [InputQuizController::class, 'destroyInputQuiz']);
            });

            Route::prefix('option-quizzes')->group(function () {
                Route::get('/',[OptionQuizController::class, 'showOptionQuiz']);
                Route::post('/', [OptionQuizController::class, 'storeOptionQuiz']);
                Route::patch('/updateData/{id}', [OptionQuizController::class, 'updateOptionQuiz']);
                Route::delete('deleteData/{id}', [OptionQuizController::class, 'destroyOptionQuiz']);
            });

            Route::prefix('video-lessons')->group(function () {
                Route::get('/', [VideoLessonController::class, 'index']);         
                Route::post('/', [VideoLessonController::class, 'store']);        
                Route::get('/{id}', [VideoLessonController::class, 'show']);      
                Route::post('/{id}', [VideoLessonController::class, 'updateVideoLesson']); 
                Route::post('/video-lesson-contents/{id}', [VideoLessonController::class, 'updateLessonContent']);   
                Route::delete('/{id}', [VideoLessonController::class, 'destroy']); 
            });

            Route::prefix('flashcard-games')->group(function () {
                Route::get('/', [FlashcardGameController::class, 'index']);
                Route::get('/flashcard-cards/{id}', [FlashcardCardController::class, 'show']);
                Route::post('/', [FlashcardGameController::class, 'store']);
                Route::patch('/game/{id}', [FlashcardGameController::class, 'updateFlashcardGame']);
                Route::patch('/card/{id}', [FlashcardGameController::class, 'updateFlashcardCard']);
                Route::delete('/{id}', [FlashcardGameController::class, 'destroy']);
                Route::delete('/card/{id}', [FlashcardGameController::class, 'destroyCard']);
            });
        });

        Route::prefix('quiz-score')->group(function () {
            Route::get('/', [QuizScoreController::class, 'index']);
            Route::post('/', [QuizScoreController::class, 'store']);
            Route::get('/{id}', [QuizScoreController::class, 'show']);
            Route::patch('/{id}', [QuizScoreController::class, 'update']);
            Route::delete('/{id}', [QuizScoreController::class, 'destroy']);
            Route::get('/leaderboard/post-test', [QuizScoreController::class, 'getPostTestScores']);
        });
    });

    Route::get('/lesson-history/{id}', [UserLessonHistoryController::class, 'showLessonByID']);
    Route::get('/module-history/user/{id_users}', [UserModulesHistoryController::class, 'showModulesByID']);
    Route::get('/module-history/{userId}/{subjectId}', [UserModulesHistoryController::class, 'showModuleHistoryByUserAndSubject']);
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





