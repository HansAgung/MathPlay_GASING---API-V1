<?php

namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuizScore;
use App\Models\InputQuiz;
use App\Models\OptionQuiz;

class QuizScoreController extends Controller
{
   public function index()
    {
        $scores = QuizScore::select('id_users')
            ->selectRaw('SUM(score) as total_score')
            ->groupBy('id_users')
            ->get();

        return response()->json([
            'message' => 'Total quiz scores per user retrieved successfully',
            'data'    => $scores,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_users'   => 'required|exists:users,id_users',
            'quiz_id'   => 'required|integer',
            'type_assets' => 'required|in:0,1',
            'score'     => 'required|integer|min:0|max:100',
        ]);

        if ($validated['type_assets'] === '0') {
            if (!InputQuiz::where('id_input_quizezz', $validated['quiz_id'])->exists()) {
                return response()->json(['message' => 'Quiz ID not found in input_quizzes'], 404);
            }
        } elseif ($validated['type_assets'] === '1') {
            if (!OptionQuiz::where('id_option_quizezz', $validated['quiz_id'])->exists()) {
                return response()->json(['message' => 'Quiz ID not found in option_quizzes'], 404);
            }
        }

        $score = QuizScore::create($validated);
        return response()->json([
            'message' => 'Quiz score created successfully',
            'data' => $score,
        ]);
    }

    public function show($id)
    {
        $score = QuizScore::find($id);
        if (!$score) {
            return response()->json(['message' => 'Quiz score not found'], 404);
        }

        return response()->json([
            'message' => 'Quiz score retrieved successfully',
            'data' => $score,
        ]);
    }

    public function update(Request $request, $id)
    {
        $score = QuizScore::find($id);

        if (!$score) {
            return response()->json(['message' => 'Quiz score not found'], 404);
        }

        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $score->score = $validated['score'];
        $score->save();

        return response()->json([
            'message' => 'Quiz score updated successfully',
            'data' => $score,
        ]);
    }

    public function destroy($id)
    {
        $score = QuizScore::find($id);
        if (!$score) {
            return response()->json(['message' => 'Quiz score not found'], 404);
        }

        $score->delete();

        return response()->json(['message' => 'Quiz score deleted successfully']);
    }

    public function getPostTestScores()
    {
        $scores = QuizScore::with(['inputQuiz.learningSubject', 'optionQuiz.learningSubject'])
            ->get()
            ->filter(function ($score) {
                if ($score->quiz_type === 'input' && $score->inputQuiz && $score->inputQuiz->test_type === 'post') {
                    return true;
                }

                if ($score->quiz_type === 'option' && $score->optionQuiz && $score->optionQuiz->test_type === 'post') {
                    return true;
                }

                return false;
            })
            ->map(function ($score) {
                $subject = null;

                if ($score->quiz_type === 'input') {
                    $subject = optional($score->inputQuiz->learningSubject)->title_learning_subject;
                } elseif ($score->quiz_type === 'option') {
                    $subject = optional($score->optionQuiz->learningSubject)->title_learning_subject;
                }

                return [
                    'id_users' => $score->id_users,
                    'score' => $score->score,
                    'quiz_type' => $score->quiz_type,
                    'quiz_id' => $score->quiz_id,
                    'title_learning_subject' => $subject,
                ];
            })
            ->values(); // reset index

        return response()->json([
            'message' => 'Post test scores retrieved successfully',
            'data' => $scores
        ]);
    }
}
