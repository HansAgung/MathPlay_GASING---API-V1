<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use App\Models\VideoLesson;
use App\Models\VideoLessonContent;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VideoLessonController extends Controller
{
    public function store(Request $request, $id_learning_modules)
    {
        DB::beginTransaction();
        
        try {
            $lessonValidated = $request->validate([
                'title_lessons' => 'required|string|max:255',
                'video_url_lessons' => 'nullable|string',
                'description_contents' => 'required|string',
            ]);

            $contentValidated = [];
            if ($request->has('contents') && is_array($request->contents)) {
                $contentValidated = $request->validate([
                    'contents' => 'required|array|min:1',
                    'contents.*.title_material' => 'required|string|max:255',
                    'contents.*.description_material' => 'required|string',
                    'contents.*.video_url' => 'nullable|string',
                    'contents.*.material_img_support' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
                ]);
            }

            $videoLessonData = [
                'id_learning_units' => $id_learning_modules,
                'title_lessons' => $lessonValidated['title_lessons'],
                'description_contents' => $lessonValidated['description_contents'],
            ];

            if (!empty($lessonValidated['video_url_lessons'])) {
                $videoLessonData['video_url_lessons'] = $lessonValidated['video_url_lessons'];
            }

            $videoLesson = VideoLesson::create($videoLessonData);

            if (!$videoLesson || !$videoLesson->id_video_lessons) {
                throw new \Exception('Gagal membuat video lesson');
            }

            Log::info('Video Lesson created with ID: ' . $videoLesson->id_video_lessons);

            $createdContents = [];

            if (!empty($contentValidated['contents'])) {
                foreach ($contentValidated['contents'] as $index => $contentData) {
                    if (empty($contentData['title_material']) || empty($contentData['description_material'])) {
                        throw new \Exception("Content pada index {$index} tidak lengkap");
                    }

                    $dataToCreate = [
                        'id_video_lessons' => $videoLesson->id_video_lessons,
                        'title_material' => $contentData['title_material'],
                        'description_material' => $contentData['description_material'],
                        'video_url' => $contentData['video_url'] ?? null,
                        'material_img_support' => null,
                    ];

                    $fileKey = "contents.{$index}.material_img_support";
                    if ($request->hasFile($fileKey)) {
                        try {
                            $uploadedUrl = Cloudinary::upload(
                                $request->file($fileKey)->getRealPath(),
                                ['folder' => 'mathplay_gasing/video_lesson_materials']
                            )->getSecurePath();
                            
                            $dataToCreate['material_img_support'] = $uploadedUrl;
                        } catch (\Exception $uploadException) {
                            Log::error('Cloudinary upload failed: ' . $uploadException->getMessage());
                            throw new \Exception('Gagal mengupload gambar untuk content ' . ($index + 1));
                        }
                    }

                    Log::info('Creating content with data:', $dataToCreate);

                    $createdContent = VideoLessonContent::create($dataToCreate);

                    if (!$createdContent) {
                        throw new \Exception("Gagal membuat content pada index {$index}");
                    }

                    $createdContents[] = $createdContent;
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Video lesson dan konten berhasil ditambahkan.',
                'data' => [
                    'video_lesson' => $videoLesson->fresh(),
                    'contents' => $createdContents,
                    'total_contents' => count($createdContents)
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in store method: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {
        $videoLesson = VideoLesson::find($id);
        if (!$videoLesson) {
            return response()->json(['message' => 'Video lesson tidak ditemukan.'], 404);
        }

        // Validasi untuk update VideoLesson
        $lessonValidated = $request->validate([
            'title_lessons' => 'sometimes|string|max:255',
            'video_url_lessons' => 'sometimes|string',
            'description_contents' => 'sometimes|string',
        ]);

        // Update hanya data di tabel video_lessons
        $videoLesson->update($lessonValidated);

        DB::commit();

        return response()->json([
            'message' => 'Video lesson berhasil diperbarui.',
            'data' => $videoLesson->fresh() // fresh() untuk memastikan data terbaru diambil
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollback();
        return response()->json([
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        DB::beginTransaction();
        
        try {
            $videoLesson = VideoLesson::find($id);
            if (!$videoLesson) {
                return response()->json(['message' => 'Video lesson tidak ditemukan.'], 404);
            }

            VideoLessonContent::where('id_video_lessons', $videoLesson->id_video_lessons)->delete();
            $videoLesson->delete();

            DB::commit();

            return response()->json(['message' => 'Video lesson dan semua konten berhasil dihapus.'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $data = VideoLesson::with('contents')->get();
        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['message' => 'Data berhasil diambil.', 'data' => $data], 200);
    }

    public function show($id)
    {
        $data = VideoLesson::with('contents')->find($id);
        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['message' => 'Data berhasil ditemukan.', 'data' => $data], 200);
    }

    public function testStore(Request $request, $id_learning_modules)
    {
        Log::info('Request data:', [
            'id_learning_modules' => $id_learning_modules,
            'request_data' => $request->all()
        ]);

        return response()->json([
            'message' => 'Debug data',
            'id_learning_modules' => $id_learning_modules,
            'request_data' => $request->all()
        ]);
    }
}
