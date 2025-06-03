<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashcardCardController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_flashcard_game' => 'required|integer',
            'img_cards' => 'required|string',
            'created_at' => 'nullable|date',
        ]);

        return FlashcardCard::create($validated);
    }

    public function update(Request $request, $id)
    {
        $card = FlashcardCard::findOrFail($id);
        $card->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $card]);
    }

    public function destroy($id)
    {
        FlashcardCard::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
}
