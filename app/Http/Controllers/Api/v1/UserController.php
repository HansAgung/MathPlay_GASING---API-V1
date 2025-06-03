<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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
}
