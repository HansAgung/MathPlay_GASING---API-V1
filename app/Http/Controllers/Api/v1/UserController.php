<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserHistory;

class UserController extends Controller
{
    public function getAllUsers() {
        return response()->json ([
            User::all()
        ]);
    }

    public function getAllUsersById(Request $request, $id)
    {
        $user = User::find($id); 

        if (!$user) {
            return response()->json([
                'message' => 'Data user tidak ada!!'
            ], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil data user.',
            'data' => $user
        ], 200);
    }

    public function getHistoryLessonUser(Request $request, $id)
    {
        $history = UserHistory::where('id_users', $id)->get();

        if ($history->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada data history untuk user ini.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Data history berhasil diambil.',
            'data' => $history
        ], 200);
    }

}
