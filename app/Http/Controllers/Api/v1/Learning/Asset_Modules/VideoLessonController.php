<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use App\Models\VideoLesson;
use App\Models\VideoLessonContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VideoLessonController extends Controller
{
    public function index()
    {
        $lessons = VideoLesson::with('contents')->get();

        return response()->json([
            'message' => 'List of all video lessons',
            'data' => $lessons
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_learning_units' => 'required|integer|exists:learning_units,id_learning_units',
            'title_lessons' => 'required|string',
            'video_url_lessons' => 'nullable|string',
            'description_contents' => 'nullable|string',
            'contents' => 'nullable|array',
            'contents.*.title_material' => 'nullable|string',
            'contents.*.description_material' => 'nullable|string',
            'contents.*.video_url' => 'nullable|string',
            'contents.*.material_img_support' => 'nullable|string',
            'contents.*.order' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $lesson = VideoLesson::create($validated);

            if (!empty($validated['contents'])) {
                foreach ($validated['contents'] as $content) {
                    $lesson->contents()->create($content);
                }
            }

            DB::commit();
            return response()->json([
                'message' => 'Video lesson created successfully.',
                'data' => $lesson->load('contents'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create video lesson.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateVideoLesson(Request $request, $id)
    {
        $validated = $request->validate([
            'id_learning_units' => 'sometimes|required|integer',
            'title_lessons' => 'sometimes|required|string',
            'video_url_lessons' => 'sometimes|required|string',
            'description_contents' => 'nullable|string',
        ]);

        $lesson = VideoLesson::findOrFail($id);
        $lesson->update($validated);

        return response()->json([
            'message' => 'Video lesson updated successfully.',
            'data' => $lesson
        ], 200);
    }

    public function updateLessonContent(Request $request, $id)
    {
        $validated = $request->validate([
            'id_video_lessons' => 'sometimes|required|integer',
            'video_url' => 'nullable|string',
            'title_material' => 'nullable|string',
            'material_img_support' => 'nullable|string',
            'description_material' => 'nullable|string',
        ]);

        $content = VideoLessonContent::findOrFail($id);
        $content->update($validated);

        return response()->json([
            'message' => 'Lesson content updated successfully.',
            'data' => $content
        ], 200);
    }

    public function destroy($id)
    {
        $lesson = VideoLesson::findOrFail($id);

        try {
            $lesson->delete();
            return response()->json([
                'message' => 'Video lesson deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete video lesson.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
