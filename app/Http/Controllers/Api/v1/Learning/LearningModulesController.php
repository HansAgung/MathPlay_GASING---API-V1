<?php

namespace App\Http\Controllers\Api\v1\Learning;

use App\Http\Controllers\Controller;
use App\Models\LearningModule;
use Illuminate\Http\Request;

class LearningModulesController extends Controller
{
    public function getLearningModules()
    {
        $modules = LearningModule::with('subject')->get();

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
}

