<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use App\Models\VideoLessonContent;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class VideoLessonContentController extends Controller
{
    public function index()
    {
        $data = VideoLessonContent::all();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['message' => 'Data berhasil diambil.', 'data' => $data], 200);
    }

    public function store(Request $request, $id_video_lessons)
    {
        try {
            $validated = $request->validate([
                'title_material'        => 'required|string|max:255',
                'description_material'  => 'required|string',
                'video_url'             => 'required|string',
                'material_img_support'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Siapkan data untuk disimpan
            $dataToCreate = [
                'id_video_lessons'      => $id_video_lessons,
                'title_material'        => $validated['title_material'],
                'description_material'  => $validated['description_material'],
                'video_url'             => $validated['video_url'],
                'material_img_support'  => null, // default null
            ];

            // Jika ada file gambar, upload ke Cloudinary
            if ($request->hasFile('material_img_support')) {
                $uploadedUrl = Cloudinary::upload(
                    $request->file('material_img_support')->getRealPath(),
                    [
                        'folder' => 'mathplay_gasing/video_lesson_materials'
                    ]
                )->getSecurePath();
                
                $dataToCreate['material_img_support'] = $uploadedUrl;
            }

            $data = VideoLessonContent::create($dataToCreate);

            return response()->json([
                'message' => 'Konten video berhasil ditambahkan.', 
                'data' => $data
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $data = VideoLessonContent::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['message' => 'Data berhasil ditemukan.', 'data' => $data], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = VideoLessonContent::find($id);
            if (!$data) {
                return response()->json(['message' => 'Data tidak ditemukan.'], 404);
            }

            $validated = $request->validate([
                'title_material'        => 'sometimes|string|max:255',
                'description_material'  => 'sometimes|string',
                'video_url'             => 'sometimes|string',
                'material_img_support'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'order'                 => 'sometimes|integer',
            ]);

            // Jika ada file gambar baru, upload ke Cloudinary
            if ($request->hasFile('material_img_support')) {
                $uploadedUrl = Cloudinary::upload(
                    $request->file('material_img_support')->getRealPath(),
                    [
                        'folder' => 'mathplay_gasing/video_lesson_materials'
                    ]
                )->getSecurePath();
                
                $validated['material_img_support'] = $uploadedUrl;
            }

            $data->update($validated);
            
            return response()->json([
                'message' => 'Data berhasil diperbarui.', 
                'data' => $data->fresh()
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = VideoLessonContent::find($id);
            if (!$data) {
                return response()->json(['message' => 'Data tidak ditemukan.'], 404);
            }

            $data->delete();
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method tambahan untuk menampilkan berdasarkan id_video_lessons
    public function getByVideoLesson($id_video_lessons)
    {
        try {
            $data = VideoLessonContent::where('id_video_lessons', $id_video_lessons)
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json(['message' => 'Data tidak ditemukan untuk video lesson ini.'], 404);
            }

            return response()->json([
                'message' => 'Data berhasil diambil.',
                'data' => $data,
                'total_content' => $data->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}