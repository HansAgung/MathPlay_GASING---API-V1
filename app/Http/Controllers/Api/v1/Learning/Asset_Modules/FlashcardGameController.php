<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashcardGameController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_learning_units' => 'required|integer',
            'patternCount' => 'required|integer',
            'matchCount' => 'required|integer',
            'cards' => 'required|integer',
            'set_time' => 'required|integer',
        ]);

        return FlashcardGame::create($validated);
    }

    public function update(Request $request, $id)
    {
        $game = FlashcardGame::findOrFail($id);
        $game->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $game]);
    }

    public function destroy($id)
    {
        FlashcardGame::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
