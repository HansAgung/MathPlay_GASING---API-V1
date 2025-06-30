<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserLessonHistory;
use App\Models\LearningModule;
use App\Models\UserModuleHistory;
use Illuminate\Http\Request;

class UserLessonHistoryController extends Controller
{
    public function showLessonByID($id_users)
{
    // Ambil semua histori pelajaran user
    $histories = UserLessonHistory::with('subject')
        ->where('id_users', $id_users)
        ->orderBy('created_at', 'asc')
        ->get();

    if ($histories->isEmpty()) {
        return response()->json([
            'message' => 'Data tidak ditemukan untuk user ini.',
        ], 404);
    }

    // Loop setiap histori pelajaran
    foreach ($histories as $history) {
        $subjectId = $history->id_learning_subjects;

        // Ambil semua modul dari subject ini
        $allModules = LearningModule::where('id_learning_subjects', $subjectId)->pluck('id_learning_modules');

        // Cek apakah semua modul sudah complete oleh user ini
        $completedModules = UserModuleHistory::where('id_users', $id_users)
            ->whereIn('id_learning_modules', $allModules)
            ->where('status', 'complete')
            ->count();

        if ($allModules->count() > 0) {
    if ($completedModules === $allModules->count()) {
        // Semua modul complete → tandai subject complete
        UserLessonHistory::where('id_users', $id_users)
            ->where('id_learning_subjects', $subjectId)
            ->update(['status' => 'complete']);
    } else {
        // Ada modul baru yang belum complete → subject belum selesai
        UserLessonHistory::where('id_users', $id_users)
            ->where('id_learning_subjects', $subjectId)
            ->update(['status' => 'onProgress']);
    }
}
    }

    // Ambil ulang histori setelah update
    $updatedHistories = UserLessonHistory::with('subject')
        ->where('id_users', $id_users)
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json([
        'user_id' => $id_users,
        'lesson_histories' => $updatedHistories,
    ], 200);
}

}
