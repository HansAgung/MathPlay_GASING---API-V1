<?php

namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
use App\Models\User;
use App\Models\UserModuleHistory;
use Illuminate\Http\Request;

class LearningModulesController extends Controller
{
    public function getLearningModules()
    {
        // $modules = LearningModule::with('subject')->get();
        $modules = LearningModule::all();

        return response()->json([
            'message' => 'Daftar modul berhasil diambil',
            'data' => $modules
        ], 200);
    }

    public function getModulesBySubjectId($id_learning_subjects)
    {
        $modules = LearningModule::where('id_learning_subjects', $id_learning_subjects)->get();

        if ($modules->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada modul untuk subject tersebut'
            ], 404);
        }

        return response()->json([
            'message' => 'Modul berdasarkan subject berhasil diambil',
            'data' => $modules
        ], 200);
    }

    public function addLearningModules(Request $request)
    {
        $request->validate([
            'id_learning_subjects' => 'required|exists:learning_subject,id_learning_subjects',
            'title_modules' => 'required|string|max:255',
            'description_modules' => 'required|string',
        ]);

        // Buat data modul baru
        $module = LearningModule::create([
            'id_learning_subjects' => $request->id_learning_subjects,
            'title_modules' => $request->title_modules,
            'description_modules' => $request->description_modules,
        ]);

        // Ambil semua user
        $users = User::all();

        // Tambahkan ke user_module_history
        foreach ($users as $user) {
            UserModuleHistory::create([
                'id_users' => $user->id_users,
                'id_learning_modules' => $module->id_learning_modules,
                'status' => $module->id_learning_modules == 1 ? 'onProgress' : 'toDo',
                'created_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Modul berhasil ditambahkan dan disinkronkan ke semua user!',
            'data' => $module
        ], 201);
    }

    public function editLearningModules(Request $request, $id)
    {
        $module = LearningModule::find($id);
        if (!$module) {
            return response()->json([
                'message' => 'Modul tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'id_learning_subjects' => 'sometimes|exists:learning_subject,id_learning_subjects',
            'title_modules' => 'sometimes|string|max:255',
            'description_modules' => 'sometimes|string',
        ]);

        if ($request->has('id_learning_subjects')) {
            $module->id_learning_subjects = $request->id_learning_subjects;
        }
        if ($request->has('title_modules')) {
            $module->title_modules = $request->title_modules;
        }
        if ($request->has('description_modules')) {
            $module->description_modules = $request->description_modules;
        }

        $module->save();

        return response()->json([
            'message' => 'Modul berhasil diperbarui',
            'data' => $module->fresh() 
        ], 200);
    }

    public function deleteLearningModules($id)
    {
        $module = LearningModule::find($id);
        if (!$module) {
            return response()->json([
                'message' => 'Modul tidak ditemukan'
            ], 404);
        }

        $module->delete();

        return response()->json([
            'message' => 'Modul berhasil dihapus'
        ], 200);
    }
}
