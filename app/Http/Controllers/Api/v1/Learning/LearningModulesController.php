<?php

namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
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

    public function addLearningModules(Request $request)
    {
        $request->validate([
            'id_learning_subjects' => 'required|exists:learning_subject,id_learning_subjects',
            'title_modules' => 'required|string|max:255',
            'description_modules' => 'required|string',
        ]);

        $module = LearningModule::create([
            'id_learning_subjects' => $request->id_learning_subjects,
            'title_modules' => $request->title_modules,
            'description_modules' => $request->description_modules,
        ]);

        return response()->json([
            'message' => 'Modul berhasil ditambahkan',
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
            'data' => $module->fresh() // untuk memastikan data yang dikembalikan adalah versi terbaru
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
