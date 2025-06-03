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

    private function generateQuestWithModules(int $id, string $title, string $desc, string $image, int $adminId, string $status): array
    {
        $modules = [];
        $lessonId = 1;

        // Modul utama
        for ($i = 1; $i <= 4; $i++) {
            $modules[] = $this->generateModule($i, $lessonId, $title, $id);
            $lessonId += 5;
        }

        // Modul berlangganan
        $subscriptionModuleId = 99;
        $subscriptionLessons = [];
        for ($j = 0; $j < 5; $j++) {
            $subscriptionLessons[] = [
                "id_lesson_quest" => $lessonId++,
                "type_lesson_quest" => rand(0, 3),
                "title_lesson_quest" => "Materi Premium " . ($j + 1),
                "quest_lesson_desc" => "Konten eksklusif untuk pengguna berlangganan.",
                "status" => "incomplete",
                "energy_cost" => rand(30, 40)
            ];
        }

        $subscriptionModule = [
            "id_quest_module" => $subscriptionModuleId,
            "title_quest_module" => "Modul Berlangganan",
            "quest_module_desc" => "Modul khusus untuk pengguna yang telah berlangganan.",
            "module_status" => 0,
            "status" => "subscription",
            "lesson_content" => [
                "lesson_quest" => $subscriptionLessons
            ]
        ];

        $modules[] = $subscriptionModule;

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

    private function generateModule(int $moduleId, int $startLessonId, string $topic, int $questId): array
{
    $titles = $this->getLessonTitlesByTopic($topic);
    $lessons = [];

    // Tetapkan tipe soal 0,1,2,3 dan pastikan 2 di tengah (index ke-2)
    $types = [0, 1, 3];
    shuffle($types);
    array_splice($types, 2, 0, 2); // Sisipkan 2 di index ke-2 → [?, ?, 2, ?]

    for ($i = 0; $i < 4; $i++) {
        $lessonIndex = $startLessonId + $i - 1;

        $lessons[] = [
            "id_lesson_quest" => $startLessonId + $i,
            "type_lesson_quest" => $types[$i],
            "title_lesson_quest" => $titles[$lessonIndex] ?? "Materi Umum " . ($i + 1),
            "quest_lesson_desc" => "Deskripsi singkat materi " . ($i + 1),
            "status" => ($moduleId === 1) ? "complete" : "incomplete",
            "energy_cost" => rand(10, 25)
        ];
    }

    return [
        "id_quest_module" => $moduleId,
        "title_quest_module" => "Modul " . $moduleId,
        "quest_module_desc" => "Deskripsi modul " . $moduleId,
        "module_status" => 1,
        "status" => "available",
        "lesson_content" => [
            "lesson_quest" => $lessons
        ]
    ];
}



    private function getLessonTitlesByTopic($topic)
    {
        $titles = [
            "Penjumlahan Dasar" => [
                "Penjumlahan Satu Angka", "Penjumlahan dengan Bantuan Gambar", "Menjumlah 2 Angka", "Latihan Cerita", "Evaluasi Ringan",
                "Penjumlahan Angka Genap", "Penjumlahan Melalui Garis Bilangan", "Soal Cerita Penjumlahan", "Latihan Simulasi", "Uji Kemampuan",
                "Tambah Tanpa Menghafal", "Penjumlahan 3 Angka", "Latihan Kreatif", "Mengisi Kosong", "Latihan Penutup",
                "Tantangan Penjumlahan", "Review Interaktif", "Tes Singkat", "Soal Refleksi", "Latihan Skor Maksimum"
            ],
            "Pengurangan Mudah" => [
                "Pengurangan Dasar", "Kurangi dengan Gambar", "Pengurangan Dua Angka", "Soal Cerita", "Latihan Akhir",
                "Latihan Ganjil Genap", "Kurangi dengan Bantuan Alat", "Praktik Langsung", "Simulasi Masalah", "Review Quiz",
                "Pengurangan Tiga Angka", "Pengurangan Cerita", "Soal Pilihan", "Tebak Jawaban", "Evaluasi Gaya Baru",
                "Tes Menarik", "Refleksi Pengurangan", "Puzzle Matematika", "Latihan Harian", "Penutup Bab"
            ],
            "Perkalian Cepat" => [
                "Perkalian Satu Digit", "Trik Perkalian Mudah", "Perkalian Visual", "Cerita Perkalian", "Latihan Skor",
                "Perkalian Angka Sama", "Kuis Perkalian", "Perkalian dan Gambar", "Tantangan Kilat", "Mini Quiz",
                "Soal Unik", "Perkalian Kelompok", "Praktik Kelas", "Review", "Latihan Final",
                "Uji Perkalian", "Tebak Jawaban", "Simulasi", "Refleksi", "Penguatan Konsep"
            ],
            "Pembagian Dasar" => [
                "Pembagian Mudah", "Soal Cerita", "Pembagian Dua Angka", "Latihan Praktis", "Tes Singkat",
                "Tebak Hasil", "Evaluasi", "Pembagian dan Gambar", "Soal Unik", "Cerita Kreatif",
                "Pembagian Cepat", "Latihan Berpasangan", "Tes Review", "Puzzle", "Mini Game",
                "Soal Pilihan", "Pembagian Dalam Cerita", "Simulasi Interaktif", "Kuis", "Uji Otak"
            ],
            "Bilangan Ganjil & Genap" => [
                "Membedakan Ganjil & Genap", "Latihan Cepat", "Pilih Bilangan", "Soal Cerita", "Evaluasi",
                "Menebak Jenis Bilangan", "Klasifikasi", "Latihan Visual", "Mini Game", "Simulasi Soal",
                "Ganjil Genap dalam Cerita", "Latihan Refleksi", "Kuis Pendek", "Tantangan Gambar", "Evaluasi Penutup",
                "Belajar Interaktif", "Review Soal", "Latihan Tipe Baru", "Kuis Akhir", "Permainan Matematika"
            ],
            "Bangun Datar" => [
                "Kenali Persegi", "Kenali Segitiga", "Kenali Lingkaran", "Soal Cerita", "Latihan Visual",
                "Membedakan Bangun", "Gambar ke Nama", "Evaluasi", "Permainan Bentuk", "Uji Coba",
                "Review Cepat", "Kuis Bentuk", "Simulasi Interaktif", "Refleksi", "Tes Ringan",
                "Pilih Gambar", "Latihan Penutup", "Puzzle Bentuk", "Tes Kecil", "Permainan Edukatif"
            ]
        ];  

        return $titles[$topic] ?? [];
    }

    private function randomExcept(int $except, int $min, int $max): int
    {
        do {
            $rand = rand($min, $max);
        } while ($rand === $except);
        return $rand;
    }
}
