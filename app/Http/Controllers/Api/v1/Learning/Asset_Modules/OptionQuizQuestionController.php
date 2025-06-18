<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OptionQuiz;
use App\Models\OptionQuizQuestion;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class OptionQuizQuestionController extends Controller
{
    public function index()
    {
        $questions = OptionQuizQuestion::all();

        if ($questions->isEmpty()) {
            return response()->json([
                'message' => 'Belum ada data soal yang tersedia.'
            ], 404);
        }

        return response()->json([
            'message' => 'Data soal berhasil diambil.',
            'data' => $questions
        ], 200);
    }
    
    public function showQuestionsByID($id_option_quizezz)
    {
        try {
            // Validasi apakah quiz exists
            $quiz = OptionQuiz::find($id_option_quizezz);
            if (!$quiz) {
                return response()->json([
                    'message' => 'Quiz tidak ditemukan.'
                ], 404);
            }

            // Ambil semua questions untuk quiz ini
            $questions = OptionQuizQuestion::where('id_option_quizezz', $id_option_quizezz)
                ->orderBy('created_at', 'asc')
                ->get();

            // Hitung total questions
            $totalQuestions = $questions->count();

            return response()->json([
                'message' => 'Data soal berhasil diambil.',
                'data' => [
                    'quiz_info' => [
                        'id_input_quizezz' => $id_option_quizezz,
                        'quiz_title' => $quiz->title ?? 'Judul Quiz',
                        'total_questions' => $totalQuestions
                    ],
                    'questions' => $questions
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

       public function store(Request $request, $id_option_quizezz)
    {
        try {
            $request->merge(['id_option_quizezz' => $id_option_quizezz]);
            
            // Validasi dasar
            $validated = $request->validate([
                'id_option_quizezz'       => 'required|exists:option_quizzes,id_option_quizezz',
                'description_question'   => 'nullable|string',
                'question_answer'        => 'required|string|max:255',
            ]);

            // Validasi dinamis untuk question_quiz
            $this->validateFlexibleField($request, 'question_quiz', true); // required
            
            // Validasi dinamis untuk options
            $this->validateFlexibleField($request, 'option_1', true); // required
            $this->validateFlexibleField($request, 'option_2', true); // required
            $this->validateFlexibleField($request, 'option_3', true); // required
            $this->validateFlexibleField($request, 'option_4', true); // required

            // Proses data
            $data = $this->handleFlexibleOptions($request, $validated);
            
            $question = OptionQuizQuestion::create($data);
            
            return response()->json([
                'message' => 'Soal berhasil ditambahkan.',
                'data' => $question
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Alternatif method untuk update juga
    public function update(Request $request, $id)
    {
        try {
            $question = OptionQuizQuestion::findOrFail($id);
            
            // Validasi dasar
            $validated = $request->validate([
                'description_question'   => 'nullable|string',
                'question_answer'        => 'nullable|string|max:255',
            ]);

            // Validasi dinamis untuk field opsional saat update
            $this->validateFlexibleField($request, 'question_quiz', false);
            $this->validateFlexibleField($request, 'option_1', false);
            $this->validateFlexibleField($request, 'option_2', false);
            $this->validateFlexibleField($request, 'option_3', false);
            $this->validateFlexibleField($request, 'option_4', false);

            // Proses data
            $data = $this->handleFlexibleOptions($request, $validated);
            
            $question->update($data);
            
            return response()->json([
                'message' => 'Soal berhasil diperbarui.',
                'data' => $question->fresh()
            ], 200);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $question = OptionQuizQuestion::find($id);
        if (!$question) {
            return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
        }

        $question->delete();

        return response()->json(['message' => 'Soal berhasil dihapus.'], 200);
    }

    private function validateFlexibleField($request, $fieldName, $required = false)
    {
        $hasFile = $request->hasFile($fieldName);
        $hasText = $request->has($fieldName) && !$hasFile;
        
        if ($required && !$hasFile && !$hasText) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => "Field {$fieldName} wajib diisi (berupa teks atau file gambar)."
            ]);
        }
        
        if ($hasFile) {
            // Validasi sebagai file gambar
            $request->validate([
                $fieldName => 'image|mimes:jpg,jpeg,png|max:2048'
            ]);
        } elseif ($hasText) {
            // Validasi sebagai teks
            $request->validate([
                $fieldName => 'string|max:1000' // Sesuaikan max length sesuai kebutuhan
            ]);
        }
    }

    private function handleFlexibleOptions($request, $data)
    {
        $flexibleFields = [
            'question_quiz' => 'quiz_questions',
            'option_1' => 'options',
            'option_2' => 'options', 
            'option_3' => 'options',
            'option_4' => 'options'
        ];
        
        foreach ($flexibleFields as $field => $folder) {
            if ($request->hasFile($field)) {
                // Jika input berupa file, upload ke Cloudinary
                $uploadedUrl = Cloudinary::upload(
                    $request->file($field)->getRealPath(), 
                    [
                        'folder' => "mathplay_gasing/{$folder}"
                    ]
                )->getSecurePath();
                
                $data[$field] = $uploadedUrl;
            } elseif ($request->has($field)) {
                // Jika input berupa teks, simpan langsung
                $data[$field] = $request->input($field);
            }
        }
        
        return $data;
    }
}
