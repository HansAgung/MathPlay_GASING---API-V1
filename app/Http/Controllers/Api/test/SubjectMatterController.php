<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubjectMatterController extends Controller
{
    public function getVideoPembelajaran()
    {
        return response()->json([
            "video_pembelajaran" => [
                [
                    "id_subject_matter" => 1,
                    "id_quest_lesson" => 10,
                    "tittle_subject_matter" => "Pengenalan Penjumlahan",
                    "subject_matter_desc" => "Materi ini membahas konsep dasar penjumlahan sebagai bagian dari pengenalan matematika untuk siswa kelas 1 SD. Penjelasan disusun secara sederhana agar mudah dipahami oleh anak-anak pada tahap awal pembelajaran. Tujuannya adalah membantu siswa memahami cara menambah dua bilangan secara bertahap dan menyenangkan.",
                    "content" => [
                        [
                            "video_url" => "https://www.youtube.com/watch?v=G_WaFtXdtt0&ab_channel=Key%27sdaily",
                            "title_material" => "Mengapa belajar penjumlahan itu sangat menyenangkan??",
                            "material_img_support" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png",
                            "description_material" => "Belajar penjumlahan sangat menyenangkan karena bisa dilakukan melalui permainan, gambar, dan cerita yang menarik bagi anak-anak. Proses belajar menjadi lebih seru saat siswa diajak menghitung benda-benda di sekitar mereka. Selain itu, penjumlahan membantu anak memahami dunia sehari-hari, seperti menghitung mainan atau buah. Dengan pendekatan yang menyenangkan, siswa jadi lebih semangat dan cepat memahami konsep dasar matematika ini."
                        ], 
                        [
                            "material_img_support" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746107862/ecbbzbb7atvdeessh4jm.png",
                            "title_material" => "Penjumlahan dalam Kehidupan Sehari-hari",
                            "description_material" => "Penjumlahan dalam Kehidupan Sehari-hari adalah konsep matematika dasar yang sangat sering digunakan tanpa kita sadari. Dalam aktivitas harian, kita melakukan penjumlahan saat menghitung jumlah uang belanja, menjumlahkan waktu yang dibutuhkan untuk menyelesaikan beberapa tugas, atau bahkan saat menghitung total anggota keluarga yang hadir dalam suatu acara. Penjumlahan membantu kita membuat keputusan cepat dan tepat, seperti saat memastikan apakah uang yang kita miliki cukup untuk membeli beberapa barang sekaligus.

Selain itu, penjumlahan juga memainkan peran penting dalam pekerjaan dan berbagai profesi. Misalnya, seorang juru masak harus menjumlahkan takaran bahan agar resep yang dibuat sesuai, atau seorang guru harus menjumlahkan nilai siswa untuk mengetahui hasil akhir pembelajaran. Kemampuan melakukan penjumlahan dengan baik akan mempermudah seseorang dalam mengelola kehidupan sehari-hari secara efisien dan logis, menjadikannya salah satu keterampilan dasar yang sangat penting untuk dikuasai sejak dini.
",
                        ],
                        [
                            "video_url" => "https://www.youtube.com/watch?v=G_WaFtXdtt0&ab_channel=Key%27sdaily",
                            "title_material" => "Kesimpulan!!",
                            "description_material" => "Materi penjumlahan dasar mengajarkan konsep menambahkan dua atau lebih bilangan untuk mendapatkan hasil akhir. Siswa diperkenalkan dengan simbol penjumlahan (+) dan sama dengan (=) untuk membantu memahami proses perhitungan. Pembelajaran dilakukan melalui berbagai metode, seperti menggunakan gambar, benda nyata, dan cerita kontekstual. Hal ini bertujuan agar siswa dapat memahami konsep dengan cara yang menyenangkan dan mudah dipahami. Penjumlahan merupakan dasar penting dalam matematika yang akan digunakan dalam materi-materi selanjutnya.",
                            "material_img_support" => "https://res.cloudinary.com/dqplvpz8x/image/upload/v1746676034/__5_z0bzmq.jpg"
                        ],
                    ],
                    "created_at" => Carbon::now()->toDateTimeString()
                ]
            ]
        ]);
    }
}
