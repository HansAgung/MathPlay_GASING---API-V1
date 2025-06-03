<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InputQuiz;

class InputQuizController extends Controller
{
    public function showQuiz() {
        return response()->json(InputQuiz::all()); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_learning_units' => 'required|integer',
            'title_question' => 'required|string',
            'set_time' => 'required|integer',
            'type_assets' => 'nullable|string',
            'energy_cost' => 'required|integer',
            'status' => 'nullable|boolean',
            'question_quiz' => 'required|string',
            'description_question' => 'nullable|string',
            'option_1' => 'nullable|string',
            'option_2' => 'nullable|string',
            'option_3' => 'nullable|string',
            'option_4' => 'nullable|string',
            'question_answer' => 'required|string',
        ]);

        return InputQuiz::create($validated);
    }

    public function update(Request $request, $id)
    {
        $quiz = InputQuiz::findOrFail($id);
        $quiz->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $quiz]);
    }

    public function destroy($id)
    {
        InputQuiz::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }

}
