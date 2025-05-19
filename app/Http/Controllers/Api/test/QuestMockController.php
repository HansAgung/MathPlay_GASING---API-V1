<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestMockController extends Controller
{
    public function QuestMock()
    {
        return response()->json([
            "mockMateri" => [
                $this->generateQuestWithModules(1, "Penjumlahan Dasar", "Belajar menjumlahkan angka satu digit dengan mudah", "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png", 2, "onProgress"),
                $this->generateQuestWithModules(2, "Pengurangan Mudah", "Mengurangi angka kecil secara menyenangkan", "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png", 1, "toDo"),
                $this->generateQuestWithModules(3, "Perkalian Cepat", "Menguasai perkalian dasar dengan trik cepat", "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png", 2, "toDo"),
                $this->generateQuestWithModules(4, "Pembagian Dasar", "Belajar pembagian sederhana untuk pemula", "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png", 3, "toDo"),
                $this->generateQuestWithModules(5, "Bilangan Ganjil & Genap", "Mengenali bilangan ganjil dan genap secara visual", "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png", 1, "toDo"),
                $this->generateQuestWithModules(6, "Bangun Datar", "Memahami bentuk-bentuk dasar seperti persegi dan segitiga", "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png", 2, "toDo")
            ]
        ]);
    }

    private function generateQuestWithModules($id, $title, $desc, $image, $adminId, $status)
    {
        $modules = [];
        $lessonId = 1;
        for ($i = 1; $i <= 4; $i++) {
            $modules[] = $this->generateModule($i, $lessonId++);
        }

        return [
            "id_quest" => $id,
            "title_quest" => $title,
            "quest_desc" => $desc,
            "img_card_quest" => $image,
            "id_admins" => $adminId,
            "status" => $status,
            "module_content" => [
                "quest_module" => $modules
            ]
        ];
    }

    private function generateModule($moduleId, $lessonId)
    {
        $lessons = [];
        for ($i = 1; $i <= 5; $i++) {
            $lessons[] = $this->generateLesson($lessonId++, $i);
        }

        // Hanya module dengan ID 1 yang "onProgress", lainnya "toDo"
        $moduleStatusText = $moduleId === 1 ? "onProgress" : "toDo";

        return [
            "id_quest_module" => $moduleId,
            "title_quest_module" => "Modul $moduleId",
            "quest_module_desc" => "Deskripsi Modul $moduleId",
            "module_status" => $moduleId === 1 ? 1 : 0,
            "status" => $moduleStatusText,
            "lesson_content" => [
                "lesson_quest" => $lessons
            ]
        ];
    }

    private function generateLesson($lessonId, $soalId)
    {
        $type = rand(0, 3); // Acak type antara 0-3

        return [
            "id_lesson_quest" => $lessonId,
            "type_lesson_quest" => $type,
            "title_lesson_quest" => "Judul soal $soalId",
            "quest_lesson_desc" => "Keterangan Soal $soalId",
            "status" => rand(0, 1) === 1 ? "complete" : "incomplete"
        ];
    }
}
