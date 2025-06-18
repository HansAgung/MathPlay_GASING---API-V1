<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserUnitsHistory;

class UserUnitsHistoryController extends Controller
{
    public function showModulesByID($id_users) 
    {
        $history = UserUnitsHistory::where('id_users', $id_users)
                    ->with('learningUnit')
                    ->get();

        if($history->isEmpty()) {
            return response()->json([
                'message'=>'Tidak ada unit pada module ini'
            ]);
        }

        return response()->json([
            'message' => 'Riwayat modul berhasil diambil.',
            'data' => $history
        ], 200);
    }

    public function showUnitbyUserAndModule($userId, $moduleId)
    {
        $modules = UserUnitsHistory::with(['learningUnit' => function ($query) use ($moduleId) 
            {
                $query->where('id_learning_units',$moduleId);
            }
        ])

        ->where('id_users', $userId)
        ->get()
        ->filter(function($history){
            return $history->learningUnit !== null;
        })
        ->values();

        if($modules->isEmpty()) {
            return response()->json([
                'message'=>'Tidak ada unit pada module ini'
            ]);
        }

        return response()->json([
            'message' => 'Riwayat modul berhasil diambil.',
            'data' => $modules
        ], 200);
    }
}
