<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InputTestController extends Controller
{
    public function getInputTest()
    {
        $questions = [];

        $imageUrls = [
            "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png",
            "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png",
            "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png"
        ];

        // Hanya buat 3 soal
        for ($i = 0; $i < 3; $i++) {
            $a = rand(1, 10);
            $b = rand(1, 10);
            $correctAnswer = $a + $b;

            // Acak antara soal text atau image
            $isText = rand(0, 1) === 1;

            if ($isText) {
                $questions[] = [
                    "type" => "text",
                    "content" => "Berapakah hasil dari $a + $b?",
                    "answer" => $correctAnswer
                ];
            } else {
                $questions[] = [
                    "type" => "image",
                    "question" => $imageUrls[array_rand($imageUrls)],
                    "answer" => $correctAnswer
                ];
            }
        }

        // Gambar pilihan jawaban (option_img) sesuai permintaan
        $optionImages = [
            [
                "img" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar2_eokuuj.jpg",
                "value" => 2
            ],
            [
                "img" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar3_meobk6.jpg",
                "value" => 3
            ],
            [
                "img" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar1_oi0znn.jpg",
                "value" => 5
            ],
            [
                "img" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1747773763/bangun_datar4_btomwv.jpg",
                "value" => 4
            ]
        ];

        return response()->json([
            "inputTest" => [
                "id_quiz_option" => 1,
                "id_quest_lesson" => 1,
                "title_question" => "Quiz Penjumlahan Dasar",
                "set_time" => 60,
                "option_img" => $optionImages,
                "question" => $questions
            ]
        ]);
    }
}
