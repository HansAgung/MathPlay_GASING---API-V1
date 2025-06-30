<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OptionQuiz;
use App\Models\LearningUnit;
use App\Models\User;
use App\Models\UserUnitsHistory;

class OptionQuizController extends Controller
{
    public function showOptionQuiz() {
        return response()->json(OptionQuiz::all()); 
    }

  public function storeOptionQuiz(Request $request, $id_learning_modules)
{
    try {
        $request->merge(['id_learning_modules' => $id_learning_modules]);

        $validated = $request->validate([
            'id_learning_modules' => 'required|integer|exists:learning_modules,id_learning_modules',
            'title_question'      => 'required|string|max:255',
            'set_time'            => 'required|integer|min:1',
            'energy_cost'         => 'required|integer|min:0',
            'test_type'           => 'required|string|max:255',
            'id_badges'           => 'nullable|integer|exists:badges,id_badges',
            'reward'              => 'nullable|string',
        ]);

        $currentCount = LearningUnit::where('id_learning_modules', $validated['id_learning_modules'])->count();
        $nextOrder = $currentCount + 1;

        $unit = LearningUnit::create([
            'id_learning_modules' => $validated['id_learning_modules'],
            'unit_learning_order' => $nextOrder,
        ]);

        $optionQuiz = OptionQuiz::create([
            'id_learning_units' => $unit->id_learning_units,
            'title_question'    => $validated['title_question'],
            'set_time'          => $validated['set_time'],
            'type_assets'       => '1',
            'energy_cost'       => $validated['energy_cost'],
            'test_type'         => $validated['test_type'],
            'id_badges'         => $validated['id_badges'] ?? null,
            'reward'            => $validated['reward'] ?? null,
        ]);

        $users = User::all();
        foreach ($users as $user) {
            // Ambil semua unit dalam modul ini
            $unitIds = LearningUnit::where('id_learning_modules', $validated['id_learning_modules'])->pluck('id_learning_units');

            // Hitung unit yang sudah complete untuk user ini
            $completedCount = UserUnitsHistory::where('id_users', $user->id_users)
                ->whereIn('id_learning_units', $unitIds)
                ->where('status', 'complete')
                ->count();

            // Tentukan status berdasarkan progres sebelumnya
            $status = ($completedCount === ($unitIds->count() - 1)) ? 'onProgress' : 'toDo';

            UserUnitsHistory::create([
                'id_users'          => $user->id_users,
                'id_learning_units' => $unit->id_learning_units,
                'status'            => $status,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        return response()->json([
            'message' => 'Option quiz dan unit berhasil ditambahkan.',
            'data'    => $optionQuiz,
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


    public function updateOptionQuiz(Request $request, $id)
    {
        $optionQuiz = OptionQuiz::find($id);

        if (!$optionQuiz) {
            return response()->json([
                'message' => 'Data option quiz tidak ditemukan.'
            ], 404);
        }

        $validated = $request->validate([
            'title_question'   => 'sometimes|string|max:255',
            'set_time'         => 'sometimes|integer|min:1',
            'type_assets'      => 'sometimes|nullable|string|max:255',
            'energy_cost'      => 'sometimes|integer|min:0',
            'test_type'        => 'sometimes|string|max:255',
            'id_badges'        => 'sometimes|nullable|integer|exists:badges,id_badges',
            'reward'           => 'sometimes|nullable|string',
        ]);

        $optionQuiz->update($validated);

        return response()->json([
            'message' => 'Option quiz berhasil diperbarui.',
            'data' => $optionQuiz
        ], 200);
    }

    public function destroyOptionQuiz($id)
    {
        $optionQuiz = OptionQuiz::find($id);

        if(!$optionQuiz){
            return response()->json([
                'message' => 'Data option quiz tidak ditemukan'
            ], 404);
        }

        try {
            $unitId = $optionQuiz->id_learning_units;

            $optionQuiz->delete();

            UserUnitsHistory::where('id_learning_units',$unitId)->delete();
            LearningUnit::where('id_learning_units', $unitId)->delete();

            return response()->json([
                'message' => 'Option quiz, unit terkait, dan riwayat pengguna berhasil dihapus.'
            ], 200);

        }catch(\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
