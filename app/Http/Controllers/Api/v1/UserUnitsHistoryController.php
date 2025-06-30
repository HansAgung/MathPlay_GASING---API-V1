<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserUnitsHistory;
use App\Models\User;
use App\Models\LearningUnit;
use App\Models\LearningModule;
use Illuminate\Support\Facades\DB;

class UserUnitsHistoryController extends Controller
{
    public function showModulesByID($id_users) 
    {
        $history = UserUnitsHistory::where('id_users', $id_users)
                    ->with('learningUnit')
                    ->get();

        if($history->isEmpty()) {
            return response()->json([
                'message'=>'Tidak ada unit pada module ini'
            ]);
        }

        return response()->json([
            'message' => 'Riwayat modul berhasil diambil.',
            'data' => $history
        ], 200);
    }

    public function updateProgress($id_users, $id_learning_units)
{
    $userExists = User::where('id_users', $id_users)->exists();
    $unit = LearningUnit::find($id_learning_units);

    if (!$userExists || !$unit) {
        return response()->json([
            'message' => 'User atau Unit tidak ditemukan.'
        ], 404);
    }

    try {
        DB::beginTransaction();

        // Update unit menjadi complete
        $unitHistory = UserUnitsHistory::where('id_users', $id_users)
            ->where('id_learning_units', $id_learning_units)
            ->first();

        if ($unitHistory && $unitHistory->status !== 'complete') {
            $unitHistory->status = 'complete';
            $unitHistory->save();
        }

        // Cek apakah ada unit selanjutnya dalam modul
        $nextUnit = LearningUnit::where('id_learning_modules', $unit->id_learning_modules)
            ->where('id_learning_units', '>', $unit->id_learning_units)
            ->orderBy('id_learning_units')
            ->first();

        if ($nextUnit) {
            UserUnitsHistory::updateOrCreate(
                ['id_users' => $id_users, 'id_learning_units' => $nextUnit->id_learning_units],
                ['status' => 'onProgress']
            );
        } else {
            // Cek apakah semua unit di modul sudah complete
            $allUnits = LearningUnit::where('id_learning_modules', $unit->id_learning_modules)->pluck('id_learning_units');
            $completedUnits = UserUnitsHistory::where('id_users', $id_users)
                ->whereIn('id_learning_units', $allUnits)
                ->where('status', 'complete')
                ->count();

            if ($completedUnits === $allUnits->count()) {
                // Tandai modul sebagai complete
                DB::table('user_module_history')
                    ->where('id_users', $id_users)
                    ->where('id_learning_modules', $unit->id_learning_modules)
                    ->update(['status' => 'complete']);

                // Ambil relasi modul
                $module = $unit->learningModule; // pastikan ada relasi di model
                $subjectId = $module->id_learning_subjects ?? null;

                if ($subjectId) {
                    // Cari modul selanjutnya
                    $nextModule = \App\Models\LearningModule::where('id_learning_subjects', $subjectId)
                        ->where('id_learning_modules', '>', $unit->id_learning_modules)
                        ->orderBy('id_learning_modules')
                        ->first();

                    if ($nextModule) {
                        DB::table('user_module_history')->updateOrInsert(
                            ['id_users' => $id_users, 'id_learning_modules' => $nextModule->id_learning_modules],
                            ['status' => 'onProgress']
                        );
                    } else {
                        // Semua modul complete → tandai subject complete
                        $allModules = \App\Models\LearningModule::where('id_learning_subjects', $subjectId)->pluck('id_learning_modules');
                        $completedModules = DB::table('user_module_history')
                            ->where('id_users', $id_users)
                            ->whereIn('id_learning_modules', $allModules)
                            ->where('status', 'complete')
                            ->count();

                        if ($completedModules === $allModules->count()) {
                            DB::table('user_lesson_history')
                                ->where('id_users', $id_users)
                                ->where('id_learning_subjects', $subjectId)
                                ->update(['status' => 'complete']);
                        }
                    }
                }
            }
        }

        DB::commit();
        return response()->json([
            'message' => 'Progress berhasil diperbarui.'
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Gagal memperbarui progress.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


}
