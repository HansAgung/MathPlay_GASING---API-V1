<?php
namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningUnit;
use App\Models\User;
use App\Models\UserUnitsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LearningUnitController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Daftar learning units',
            'data' => LearningUnit::with(['inputQuizzes.questionsQuiz', 'optionQuizzes.questionsQuiz', 'videoLessons','flashcardGames.cards'])->get()
        ]);
    }

    public function showUserUnitsByModules($id_users, $id_learning_modules)
    {
        try {
            $units = LearningUnit::with([
                'inputQuizzes',
                'optionQuizzes',
                'flashcardGames',
                'videoLessons',
            ])
            ->where('id_learning_modules', $id_learning_modules)
            ->orderBy('unit_learning_order', 'asc')
            ->get()
            ->map(function ($unit) use ($id_users) {
                $history = UserUnitsHistory::where('id_users', $id_users)
                    ->where('id_learning_units', $unit->id_learning_units)
                    ->first();

                return [
                    'id_learning_units'   => $unit->id_learning_units,
                    'unit_learning_order' => $unit->unit_learning_order,
                    'status'              => $history ? $history->status : 'not_assigned',
                    'input_quizzes'       => $unit->inputQuizzes,      
                    'option_quizzes'      => $unit->optionQuizzes,
                    'flashcard_games'     => $unit->flashcardGames,
                    'video_lessons'       => $unit->videoLessons,
                ];
            });

            return response()->json([
                'message' => 'Data unit berhasil diambil',
                'data'    => $units,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengambil data unit',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $unit = LearningUnit::with(['inputQuizzes', 'optionQuizzes', 'videoLessons', 'flashcardGames.cards'])->findOrFail($id);
        return response()->json(['data' => $unit]);
    }
}
