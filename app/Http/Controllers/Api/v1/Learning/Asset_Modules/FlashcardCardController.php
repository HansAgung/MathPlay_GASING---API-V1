<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\FlashcardCard;

class FlashcardCardController extends Controller
{
    public function index()
    {
        $cards = FlashcardCard::with('game')->get();
        return response()->json([
            'message' => 'Flashcard cards fetched successfully',
            'data' => $cards
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_flashcard_game' => 'required|integer|exists:flashcard_minigame,id_flashcard_game',
            'img_cards' => 'required|string',
            'created_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $card = FlashcardCard::create($validator->validated());

        return response()->json([
            'message' => 'Flashcard card created successfully',
            'data' => $card
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $card = FlashcardCard::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'img_cards' => 'sometimes|string',
            'created_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $card->update($validator->validated());

        return response()->json([
            'message' => 'Flashcard card updated successfully',
            'data' => $card
        ], 200);
    }

    public function destroy($id)
    {
        $card = FlashcardCard::find($id);

        if (!$card) {
            return response()->json(['message' => 'Flashcard card not found'], 404);
        }

        $card->delete();

        return response()->json(['message' => 'Flashcard card deleted successfully'], 200);
    }

    public function show($id)
    {
        $cards = FlashcardCard::where('id_flashcard_game', $id)->get();

        if ($cards->isEmpty()) {
            return response()->json([
                'message' => 'No flashcard cards found for this game ID'
            ], 404);
        }

        return response()->json([
            'message' => 'Flashcard cards retrieved successfully',
            'data' => $cards
        ], 200);
    }
}
