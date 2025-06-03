<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;


class AuthAdminController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email|unique:admins,email',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'username'              => 'required|string|unique:admins,username',
            'address'               => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('password_confirmation')) {
                return response()->json([
                    'message' => 'The password confirmation does not match.'
                ], 422);
            }

            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = Admin::create([
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'username'     => $request->username,
            'address'      => $request->address,
            'is_approved'  => false,
            'role_admins'  => "0",
            'profile_img'  => null,
        ]);

        $token = $admin->createToken('admin_register')->plainTextToken;

        return response()->json([
            'message'      => 'Register berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'admin'        => $admin,
        ], 201);
    }


    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Kredensial tidak valid'], 401);
        }

        $token = $admin->createToken('admin_login')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'admin'        => $admin,
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'address'               => 'required',
            'password'              => 'required|confirmed',
        ]);

        $admin = Admin::where('email', $request->email)->where('address', $request->address)->first();

        if (!$admin) {
            return response()->json(['message' => 'Data tidak cocok'], 404);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password berhasil direset.'], 200);
    }

    public function logout(Request $request)
    {
        if ($request->user('admin')->currentAccessToken()) {
            $request->user('admin')->currentAccessToken()->delete();
            return response()->json(['message' => 'Logout berhasil.']);
        }

        return response()->json(['message' => 'Token tidak ditemukan atau sudah logout.'], 401);
    }
}
