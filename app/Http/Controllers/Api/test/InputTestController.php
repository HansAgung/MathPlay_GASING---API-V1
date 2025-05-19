<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InputTestController extends Controller
{
    public function getInputTest()
    {
        $questions = [];

        // Contoh URL gambar soal
        $imageUrls = [
            "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png",
            "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png",
            "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png"
        ];

        for ($i = 0; $i < 10; $i++) {
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

        // Option image (pilihan jawaban berupa gambar + value)
        $optionValues = [2, 3, 5, 4]; // bisa diganti atau digenerate acak
        $optionImages = [];

        foreach ($optionValues as $val) {
            $optionImages[] = [
                "img" => "option_{$val}.png",
                "value" => $val
            ];
        }

        return response()->json([
            "inputTest" => [
                "id_quiz_option" => 1,
                "id_quest_lesson" => 1,
                "title_question" => "Quiz Penjumlahan Dasar",
                "set_time" => 10,
                "option_img" => $optionImages,
                "question" => $questions
            ]
        ]);
    }
}
