<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FlashcardGame;
use App\Models\FlashcardCard;

class FlashcardGameController extends Controller
{
    public function index()
    {
        $games = FlashcardGame::with('cards')->get();
        return response()->json(['data' => $games]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_learning_units' => 'required|exists:learning_units,id_learning_units',
            'patternCount' => 'required|integer',
            'matchCount' => 'required|integer',
            'set_time' => 'nullable|integer',
            'cards' => 'required|array',
            'cards.*.img_cards' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Simpan flashcard game
            $game = FlashcardGame::create([
                'id_learning_units' => $validated['id_learning_units'],
                'patternCount' => $validated['patternCount'],
                'matchCount' => $validated['matchCount'],
                'set_time' => $validated['set_time'],
                'cards' => count($validated['cards']),
            ]);

            // Simpan kartu
            foreach ($validated['cards'] as $cardData) {
                FlashcardCard::create([
                    'id_flashcard_game' => $game->id_flashcard_game,
                    'img_cards' => $cardData['img_cards']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Flashcard game created successfully',
                'data' => $game->load('cards')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Failed to create flashcard game',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function updateFlashcardGame(Request $request, $id)
    {
        $validated = $request->validate([
            'patternCount' => 'integer',
            'matchCount' => 'integer',
            'set_time' => 'integer',
        ]);

        $game = FlashcardGame::findOrFail($id);
        $game->update($validated);

        return response()->json(['message' => 'Flashcard game updated successfully', 'data' => $game]);
    }

    public function updateFlashcardCard(Request $request, $id)
    {
        $validated = $request->validate([
            'img_cards' => 'required|string',
        ]);

        $card = FlashcardCard::findOrFail($id);
        $card->update($validated);

        return response()->json(['message' => 'Flashcard card updated successfully', 'data' => $card]);
    }

    public function destroy($id)
    {
        $game = FlashcardGame::findOrFail($id);
        $game->cards()->delete();
        $game->delete();

        return response()->json(['message' => 'Flashcard game and its cards deleted successfully']);
    }

    public function destroyCard($id)
    {
        $card = FlashcardCard::findOrFail($id);
        $card->delete();

        return response()->json(['message' => 'Flashcard card deleted successfully']);
    }
}
