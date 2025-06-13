<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\UserLessonHistory;
use Illuminate\Http\Request;

class UserLessonHistoryController extends Controller
{
    public function showLessonByID($id_users)
    {
        $histories = UserLessonHistory::with('subject')
            ->where('id_users', $id_users)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($histories->isEmpty()) {
            return response()->json([
                'message' => 'Data tidak ditemukan untuk user ini.',
            ], 404);
        }

        return response()->json([
            'user_id' => $id_users,
            'lesson_histories' => $histories,
        ], 200);
    }
}
