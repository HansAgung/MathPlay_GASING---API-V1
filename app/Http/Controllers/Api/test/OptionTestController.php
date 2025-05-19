<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OptionTestController extends Controller
{
    public function getOptionTest()
    {
        $questions = [];
        $answers = [];

        for ($i = 1; $i <= 10; $i++) {
            $isImage = $i % 2 === 0;

            // Question item
            $questions[] = [
                "question_quiz" => [
                    "type" => $isImage ? "image" : "text",
                    "content" => $isImage
                        ? "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png"
                        : "Berapakah hasil dari $i + $i?"
                ],
                "quest_desc" => $isImage
                    ? "Hitung objek pada gambar soal ke-$i."
                    : "Soal ini menguji kemampuan penjumlahan dasar soal ke-$i."
            ];

            // Answer options
            $answers[] = [
                "option_question" => [
                    [
                        "option_id" => "A",
                        "type" => "text",
                        "content" => strval(($i * 2) - 1)
                    ],
                    [
                        "option_id" => "B",
                        "type" => "text",
                        "content" => strval($i * 2)
                    ],
                    [
                        "option_id" => "C",
                        "type" => $isImage ? "image" : "text",
                        "content" => $isImage
                            ? "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png"  
                            : strval(($i * 2) + 1)
                    ],
                    [
                        "option_id" => "D",
                        "type" => "text",
                        "content" => strval(($i * 2) + 2)
                    ]
                ],
                "question_answer" => "B"
            ];
        }

        return response()->json([
            "optionTest" => [
                "id_quiz_option" => 1,
                "id_quest_lesson" => 1,
                "title_question" => "Quiz Penjumlahan Dasar",
                "set_time" => 10,
                "question" => $questions,
                "answer" => $answers
            ]
        ]);
    }
}
