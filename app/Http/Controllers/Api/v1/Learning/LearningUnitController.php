<?php
namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningUnit;
use Illuminate\Http\Request;

class LearningUnitController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Daftar learning units',
            'data' => LearningUnit::with(['inputQuizzes.questionsQuiz', 'optionQuizzes.questionsQuiz', 'videoLessons','flashcardGames.cards'])->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_learning_modules' => 'required|exists:learning_module,id_learning_modules',
            'title_learning_unit' => 'required|string|max:255',
            'description_unit' => 'required|string',
            'unit_learning_order' => 'required|integer'
        ]);

        $unit = LearningUnit::create($request->all());

        return response()->json(['message' => 'Unit berhasil ditambahkan', 'data' => $unit], 201);
    }

    public function update(Request $request, $id)
    {
        $unit = LearningUnit::findOrFail($id);
        $unit->update($request->all());

        return response()->json(['message' => 'Unit berhasil diperbarui', 'data' => $unit], 200);
    }

    public function destroy($id)
    {
        $unit = LearningUnit::findOrFail($id);
        $unit->delete();

        return response()->json(['message' => 'Unit berhasil dihapus'], 200);
    }

    public function show($id)
    {
        $unit = LearningUnit::with(['inputQuizzes', 'optionQuizzes', 'videoLessons', 'flashcardGames.cards'])->findOrFail($id);
        return response()->json(['data' => $unit]);
    }
}
