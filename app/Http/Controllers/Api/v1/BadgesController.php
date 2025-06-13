<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BadgesController extends Controller
{
    public function showAllBadges()
    {
        return response()->json(Badge::all());
    }
    
    public function getBadgesByID($id)
    {
        $badge = Badge::find($id);

        if (!$badge) {
            return response()->json([
                'message' => 'Badge tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail badge ditemukan.',
            'data' => $badge
        ], 200);
    }
    
    public function storeBadges(Request $request)
    {
        $request->validate([
            'title_badges' => 'required|string|max:100',
            'badges_desc' => 'requir ed|string|max:255',
            'badges_img' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'point' => 'required|integer',
            'energy' => 'required|integer',
        ]);

        $data = $request->only(['title_badges','badges_desc','badges_img','point','energy']);

        if($request->hasFile('badges_img')) {
            $updloadImage = Cloudinary::upload($request->file('badges_img')->getRealPath(), [
                'folder' => 'mathplay_gasing/badges'
            ]) -> getSecurePath();

            $data['badges_img'] = $updloadImage;
        }

        $badge = Badge::create($data);
        return response() -> json([
            'message'=>'Data badges berhasil ditambahkan.',
            'badge' => $badge,
        ], 201);

        if(!$badge) {
            return response()-> json(['message' => 'Gagal menyimpan data.', 500]);
        }
    }

    public function updateBadges(Request $request, $id) {
        $badge = Badge::find($id);
        if(!$badge){
            return response() -> json(['message'=>'Tidak ditemukan data', 404]);
        } 

        $request->validate([
            'title_badges' => 'nullable|string|max:100',
            'badges_desc' => 'nullable|string|max:255',
            'badges_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'point' => 'nullable|integer',
            'energy' => 'nullable|integer',
        ]);

        $fields = ['title_badges','badges_desc','point','energy'];
        $data = [];

        //Ini untuk update data yang berupa text
        foreach ($fields as $field) {
            if (!is_null($request->input($field))) {
                $data[$field] = $request->input($field);
            }
        }

        if($request->hasFile('badges_img')) {
            $updloadImage = Cloudinary::upload($request->file('badges_img')->getRealPath(), [
                'folder' => 'mathplay_gasing/badges'
            ]) -> getSecurePath();

            $data['badges_img'] = $updloadImage;
        } 

        $badge->update($data);
        $badge->refresh();

        return response()->json([
            'message'=>'Proses update badge berhasil',
            'data' => $badge
        ], 201);

        if(!$data) {
            return response() -> json(['message'=>'Proses Update Gagal.']);
        }
    }

    public function destroyBadges($id)
    {
        $badge = Badge::find($id);
        if (!$badge) {
            return response()->json(['message' => 'Badge not found'], 404);
        }

        if ($badge->badges_img && file_exists(public_path($badge->badges_img))) {
            unlink(public_path($badge->badges_img));
        }

        $badge->delete();
        return response()->json(['message' => 'Badge deleted']);
    }
}
