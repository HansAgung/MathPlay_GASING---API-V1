<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FlashCardController extends Controller
{
    public function getFlashCard()
    {
        $cards = [
            [
                "id" => 1,
                "imageUrl" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar2_eokuuj.jpg"
            ],
            [
                "id" => 2,
                "imageUrl" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar3_meobk6.jpg"
            ],
            [
                "id" => 3,
                "imageUrl" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar1_oi0znn.jpg"
            ]
        ];

        return response()->json([
            "flashcard_item" => [
                "lessonId" => 1,
                "patternCount" => 4,
                "matchCount" => 2,
                "cards" => $cards
            ]
        ]);
    }
}
