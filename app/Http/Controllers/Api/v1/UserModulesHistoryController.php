<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModuleHistory;
use App\Models\LearningModule;

class UserModulesHistoryController extends Controller
{
    public function showModulesByID($id_users)
    {
        $history = UserModuleHistory::where('id_users', $id_users)
                    ->with('learningModule') 
                    ->get();

        if ($history->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada riwayat modul untuk user ini.'
            ], 404);
        }

        return response()->json([
            'message' => 'Riwayat modul berhasil diambil.',
            'data' => $history
        ], 200);
    }

    public function showModuleHistoryByUserAndSubject($userId, $subjectId)
    {
        $modules = UserModuleHistory::with(['learningModule' => function ($query) use ($subjectId) {
            $query->where('id_learning_subjects', $subjectId);
        }])
        ->where('id_users', $userId)
        ->get()
        ->filter(function ($history) {
            return $history->learningModule !== null;
        })
        ->values();

        return response()->json([
            'message' => 'Data module berdasarkan user dan subject berhasil diambil.',
            'data' => $modules
        ]);
    }
}
