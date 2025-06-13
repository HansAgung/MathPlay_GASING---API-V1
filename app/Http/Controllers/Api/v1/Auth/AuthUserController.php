<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LearningSubject;
use App\Models\UserLessonHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class AuthUserController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'       => 'required|string|max:255|unique:users',
            'fullname'       => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:users',
            'password'       => 'required|string|min:6|confirmed',
            'birth'          => 'required|date',
            'gender'         => 'required|in:male,female',
            'character_img'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'user_desc'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $characterImageUrl = null;

        if ($request->hasFile('character_img')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('character_img')->getRealPath(), [
                'folder' => 'character_images'
            ])->getSecurePath();

            $characterImageUrl = $uploadedFileUrl;
        }

        $user = User::create([
            'username'      => $request->username,
            'fullname'      => $request->fullname,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'birth'         => $request->birth,
            'gender'        => $request->gender,
            'character_img' => $characterImageUrl,
            'user_desc'     => $request->user_desc ?? null,
            'lives'         => 5,
            'is_active'     => true,
        ]);

        // ✅ Tambahan: isi otomatis user_lesson_history
        $subjects = LearningSubject::all();

        foreach ($subjects as $subject) {
            UserLessonHistory::create([
                'id_users'              => $user->id_users,
                'id_learning_subjects'  => $subject->id_learning_subjects,
                'status'                => $subject->id_learning_subjects == 1 ? 'onProgress' : 'toDo',
                'created_at'            => now(),
            ]);
        }

        $token = $user->createToken('mobile_register')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials',
            ], 401);
        }
      
        $token = $user->createToken('mobile_login')->plainTextToken;
    
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 201);
    }
    
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
    
        if (!$token) {
            return response()->json([
                'message' => 'Token tidak ditemukan atau user sudah keluar.',
            ], 401);
        }
    
        $token->delete();
    
        return response()->json([
            'message' => 'Logout berhasil.',
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'birth'                 => 'required|date',
            'password'              => 'required|string|confirmed|min:6',
        ]);

        $user = User::where('email', $request->email)->where('birth', $request->birth)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email atau tanggal lahir tidak cocok.'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui.'
        ], 200);
    }

    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'user' => $user
        ], 200);
    }
}
