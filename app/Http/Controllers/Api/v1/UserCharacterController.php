<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserCharacter;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserCharacterController extends Controller
{
    public function index()
    {
        $data = UserCharacter::all();
        return response()->json([
            'message' => 'Data karakter pengguna berhasil diambil.',
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        $data = UserCharacter::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Karakter tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data karakter berhasil diambil.',
            'data' => $data
        ], 200);
    }

   

    public function store(Request $request)
    {
        $request->validate([
            'img_character' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['description']);

        if ($request->hasFile('img_character')) {
            $uploadImage = Cloudinary::upload(
                $request->file('img_character')->getRealPath(),
                ['folder' => 'mathplay_gasing/character']
            )->getSecurePath();

            $data['img_character'] = $uploadImage;
        }

        $character = UserCharacter::create($data);

        if (!$character) {
            return response()->json(['message' => 'Gagal menyimpan data.'], 500);
        }

        return response()->json([
            'message' => 'Karakter berhasil ditambahkan.',
            'data' => $character
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $character = UserCharacter::find($id);

        if (!$character) {
            return response()->json(['message' => 'Karakter tidak ditemukan.'], 404);
        }

        $request->validate([
            'img_character' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['description']);

        if ($request->hasFile('img_character')) {
            $uploadImage = Cloudinary::upload(
                $request->file('img_character')->getRealPath(),
                ['folder' => 'mathplay_gasing/character']
            )->getSecurePath();

            $data['img_character'] = $uploadImage;
        }

        $character->update($data);

        return response()->json([
            'message' => 'Karakter berhasil diperbarui.',
            'data' => $character
        ], 200);
    }

    public function destroy($id)
    {
        $data = UserCharacter::find($id);
        if (!$data) {
            return response()->json([
                'message' => 'Karakter tidak ditemukan.'
            ], 404);
        }

        $data->delete();

        return response()->json([
            'message' => 'Karakter berhasil dihapus.'
        ], 200);
    }
}
