<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;

class BadgesController extends Controller
{
    public function index()
    {
        return response()->json(Badge::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title_badges' => 'required|string|max:255',
            'badges_desc' => 'nullable|string',
            'badges_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['title_badges', 'badges_desc']);

        if ($request->hasFile('badges_img')) {
            $file = $request->file('badges_img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/badges'), $filename);
            $data['badges_img'] = 'uploads/badges/' . $filename;
        }

        $badge = Badge::create($data);
        return response()->json($badge, 201);
    }

    public function update(Request $request, $id)
{
    $badge = Badge::find($id);
    if (!$badge) {
        return response()->json(['message' => 'Badge not found'], 404);
    }

    // Validasi tetap jalan
    $request->validate([
        'title_badges' => 'nullable|string|max:255',
        'badges_desc' => 'nullable|string',
        'badges_img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = [];

    // Tidak pakai 'has()' atau 'filled()', langsung ambil dan cek null
    if (!is_null($request->input('title_badges'))) {
        $data['title_badges'] = $request->input('title_badges');
    }

    if (!is_null($request->input('badges_desc'))) {
        $data['badges_desc'] = $request->input('badges_desc');
    }

    // Cek dan proses upload gambar
    if ($request->hasFile('badges_img')) {
        if ($badge->badges_img && file_exists(public_path($badge->badges_img))) {
            unlink(public_path($badge->badges_img));
        }

        $file = $request->file('badges_img');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/badges'), $filename);
        $data['badges_img'] = 'uploads/badges/' . $filename;
    }

    // Update data
    $badge->update($data);

    // Ambil ulang data setelah update
    $badge->refresh();

    return response()->json([
        'message' => 'Badge updated successfully',
        'data' => $badge
    ]);
}


    public function destroy($id)
    {
        $badge = Badge::find($id);
        if (!$badge) {
            return response()->json(['message' => 'Badge not found'], 404);
        }

        // Optional: hapus gambar
        if ($badge->badges_img && file_exists(public_path($badge->badges_img))) {
            unlink(public_path($badge->badges_img));
        }

        $badge->delete();
        return response()->json(['message' => 'Badge deleted']);
    }
}
