<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use App\Models\VideoLesson;
use App\Models\VideoLessonContent;
use Illuminate\Http\Request;
use App\Models\UserUnitsHistory;
use App\Models\LearningUnit;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VideoLessonController extends Controller
{
    public function store(Request $request, $id_learning_modules)
{
    DB::beginTransaction();

    try {
        // Validasi request utama
        $lessonValidated = $request->validate([
            'title_lessons'         => 'required|string|max:255',
            'video_url_lessons'     => 'nullable|string',
            'description_contents'  => 'required|string',
        ]);

        // Validasi konten (jika ada)
        $contentValidated = [];
        if ($request->has('contents') && is_array($request->contents)) {
            $contentValidated = $request->validate([
                'contents'                             => 'required|array|min:1',
                'contents.*.title_material'            => 'required|string|max:255',
                'contents.*.description_material'      => 'required|string',
                'contents.*.video_url'                 => 'nullable|string',
                'contents.*.material_img_support'      => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            ]);
        }

        // Hitung urutan unit
        $currentCount = LearningUnit::where('id_learning_modules', $id_learning_modules)->count();
        $nextOrder = $currentCount + 1;

        // Buat unit
        $unit = LearningUnit::create([
            'id_learning_modules' => $id_learning_modules,
            'unit_learning_order' => $nextOrder,
        ]);

        // Buat video lesson
        $videoLesson = VideoLesson::create([
            'id_learning_units'     => $unit->id_learning_units,
            'title_lessons'         => $lessonValidated['title_lessons'],
            'description_contents'  => $lessonValidated['description_contents'],
            'type_assets'           => "2",
            'video_url_lessons'     => $lessonValidated['video_url_lessons'] ?? null,
        ]);

        // Buat konten
        $createdContents = [];
        if (!empty($contentValidated['contents'])) {
            foreach ($contentValidated['contents'] as $index => $contentData) {
                $dataToCreate = [
                    'id_video_lessons'       => $videoLesson->id_video_lessons,
                    'title_material'         => $contentData['title_material'],
                    'description_material'   => $contentData['description_material'],
                    'video_url'              => $contentData['video_url'] ?? null,
                    'material_img_support'   => null,
                ];

                $fileKey = "contents.{$index}.material_img_support";
                if ($request->hasFile($fileKey)) {
                    $uploadedUrl = Cloudinary::upload(
                        $request->file($fileKey)->getRealPath(),
                        ['folder' => 'mathplay_gasing/video_lesson_materials']
                    )->getSecurePath();

                    $dataToCreate['material_img_support'] = $uploadedUrl;
                }

                $createdContents[] = VideoLessonContent::create($dataToCreate);
            }
        }

        // Tambahkan ke user_units_history dengan logika progres dinamis
        $users = User::all();
        foreach ($users as $user) {
            $unitIds = LearningUnit::where('id_learning_modules', $id_learning_modules)->pluck('id_learning_units');

            $completedCount = UserUnitsHistory::where('id_users', $user->id_users)
                ->whereIn('id_learning_units', $unitIds)
                ->where('status', 'complete')
                ->count();

            $status = ($completedCount === ($unitIds->count() - 1)) ? 'onProgress' : 'toDo';

            UserUnitsHistory::create([
                'id_users'          => $user->id_users,
                'id_learning_units' => $unit->id_learning_units,
                'status'            => $status,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        DB::commit();

        return response()->json([
            'message' => 'Video lesson dan konten berhasil ditambahkan.',
            'data' => [
                'video_lesson'    => $videoLesson->fresh(),
                'contents'        => $createdContents,
                'total_contents'  => count($createdContents),
            ]
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Validasi gagal',
            'errors'  => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'line'    => $e->getLine(),
            'file'    => $e->getFile()
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
