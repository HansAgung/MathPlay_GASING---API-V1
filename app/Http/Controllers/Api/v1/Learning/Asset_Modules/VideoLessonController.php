<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoLessonController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_learning_units' => 'required|integer',
            'title_lessons' => 'required|string',
            'video_url_lessons' => 'required|string',
            'description_contents' => 'nullable|string',
        ]);

        return VideoLesson::create($validated);
    }

    public function update(Request $request, $id)
    {
        $lesson = VideoLesson::findOrFail($id);
        $lesson->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $lesson]);
    }

    public function destroy($id)
    {
        VideoLesson::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
