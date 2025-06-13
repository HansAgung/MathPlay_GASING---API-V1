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

    public function storeInputQuiz(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_learning_units' => 'required|integer|exists:learning_units,id_learning_units',
                'title_question'    => 'required|string|max:255',
                'set_time'          => 'required|integer|min:1',
                'type_assets'       => 'nullable|string|max:255',
                'energy_cost'       => 'required|integer|min:0',
                'status'            => 'nullable|boolean',
            ]);

            $inputQuiz = InputQuiz::create($validated);

            return response()->json([
                'message' => 'Input quiz berhasil ditambahkan.',
                'data'    => $inputQuiz
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function updateInputQuiz(Request $request, $id)
    {
        $inputQuiz = InputQuiz::find($id);

        if (!$inputQuiz) {
            return response()->json([
                'message' => 'Data input quiz tidak ditemukan.'
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

        $inputQuiz->update($validated);

        return response()->json([
            'message' => 'Input quiz berhasil diperbarui.',
            'data' => $inputQuiz
        ], 200);
    }

    public function destroyInputQuiz($id)
    {
        $inputQuiz = InputQuiz::find($id);

        if (!$inputQuiz) {
            return response()->json([
                'message' => 'Data input quiz tidak ditemukan.'
            ], 404);
        }

        $inputQuiz->delete();

        return response()->json([
            'message' => 'Input quiz berhasil dihapus.'
        ], 200);
    }

    public function showQuizByModuleID($id_learning_units)
    {
        $quizzes = InputQuiz::where('id_learning_units', $id_learning_units)->get();

        if ($quizzes->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada quiz yang ditemukan untuk unit/modul tersebut.'
            ], 404);
        }

        return response()->json([
            'message' => 'Daftar quiz berhasil diambil berdasarkan ID unit/modul.',
            'data' => $quizzes
        ], 200);
    }

}
