<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\FlashcardGame;
use App\Models\FlashcardCard;
use App\Models\LearningUnit;
use App\Models\User;
use App\Models\UserUnitsHistory;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class FlashcardGameController extends Controller
{
    public function index()
    {
        $games = FlashcardGame::with('cards')->get();
        return response()->json(['data' => $games]);
    }
    
    public function store(Request $request, $id_learning_modules)
    {
        try {
            $request->merge(['id_learning_modules' => $id_learning_modules]);

            $validated = $request->validate([
                'id_learning_modules' => 'required|integer|exists:learning_modules,id_learning_modules',
                'patternCount' => 'required|integer',
                'matchCount' => 'required|integer',
                'set_time' => 'nullable|integer',
                'cards' => 'required|array|min:1',
                'cards.*.img_cards' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            DB::beginTransaction();

            // Hitung urutan unit berikutnya
            $currentCount = LearningUnit::where('id_learning_modules', $validated['id_learning_modules'])->count();
            $nextOrder = $currentCount + 1;

            // Buat Learning Unit
            $unit = LearningUnit::create([
                'id_learning_modules' => $validated['id_learning_modules'],
                'unit_learning_order' => $nextOrder,
            ]);

            // Buat Flashcard Game
            $game = FlashcardGame::create([
                'id_learning_units' => $unit->id_learning_units,
                'patternCount' => $validated['patternCount'],
                'matchCount' => $validated['matchCount'],
                'set_time' => $validated['set_time'],
                'cards' => count($validated['cards']),
                'type_assets' => "3",
            ]);

            // Simpan setiap kartu setelah upload ke Cloudinary
            foreach ($validated['cards'] as $index => $cardData) {
                $fileKey = "cards.{$index}.img_cards";

                if ($request->hasFile($fileKey)) {
                    $uploadedUrl = Cloudinary::upload(
                        $request->file($fileKey)->getRealPath(),
                        ['folder' => 'mathplay_gasing/flashcard_cards']
                    )->getSecurePath();

                    FlashcardCard::create([
                        'id_flashcard_game' => $game->id_flashcard_game,
                        'img_cards' => $uploadedUrl,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    throw new \Exception("Gambar untuk kartu ke-" . ($index + 1) . " tidak ditemukan.");
                }
            }

            $users = User::all();
            foreach ($users as $user) {
                UserUnitsHistory::create([
                    'id_users' => $user->id_users,
                    'id_learning_units' => $unit->id_learning_units,
                    'status' => $nextOrder === 1 ? 'onProgress' : 'toDo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Flashcard game created successfully',
                'data' => $game->load('cards'),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);

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
            'img_cards' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $card = FlashcardCard::findOrFail($id);

        // Upload gambar ke Cloudinary
        $uploadedUrl = Cloudinary::upload(
            $request->file('img_cards')->getRealPath(),
            ['folder' => 'mathplay_gasing/flashcard_cards']
        )->getSecurePath();

        // Update kolom img_cards dengan URL dari Cloudinary
        $card->update([
            'img_cards' => $uploadedUrl,
        ]);

        return response()->json([
            'message' => 'Flashcard card updated successfully',
            'data' => $card,
        ]);
    }

    public function destroy($id)
    {
        $game = FlashcardGame::findOrFail($id);

        $unitId = $game->id_learning_units;

        $game->cards()->delete();
        $game->delete();

        UserUnitsHistory::where('id_learning_units',$unitId)->delete();
        LearningUnit::where('id_learning_units', $unitId)->delete();

        return response()->json([
            'message' => 'Flashcard game and its cards deleted successfully'
        ], 200);
    }

    public function destroyCard($id)
    {
        $card = FlashcardCard::findOrFail($id);
        $card->delete();

        return response()->json(['message' => 'Flashcard card deleted successfully']);
    }
}
