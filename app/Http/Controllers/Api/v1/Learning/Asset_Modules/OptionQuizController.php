<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OptionQuiz;

class OptionQuizController extends Controller
{
    public function showOptionQuiz() {
        return response()->json(OptionQuiz::all()); 
    }

    public function storeOptionQuiz(Request $request)
    {
        $validated = $request->validate([
            'id_learning_units' => 'required|integer',
            'title_question' => 'required|string',
            'set_time' => 'required|integer',
            'type_assets' => 'nullable|string',
            'energy_cost' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        return OptionQuiz::create($validated);
    }

    public function updateOptionQuiz(Request $request, $id)
    {
       $optionQuiz = OptionQuiz::find($id);

       if(!$optionQuiz) {
         return response()->json([
            'message' => 'Data option quiz tidak ditemukan.'
        ], 404);
       }

       $validated = $request->validate([
            'id_learning_units' => 'sometimes|integer',
            'title_question' => 'sometimes|string',
            'set_time' => 'sometimes|integer',
            'type_assets' => 'nullable|string',
            'energy_cost' => 'sometimes|integer',
            'status' => 'nullable|boolean',
        ]);

        $optionQuiz->update($validated);

        return response()->json([
            'message' => 'Input quiz berhasil diperbarui.',
            'data' => $optionQuiz
        ], 200);
    }

    public function destroyOptionQuiz($id)
    {
        OptionQuiz::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }

}
