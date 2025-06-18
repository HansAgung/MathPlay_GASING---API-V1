<?php

namespace App\Http\Controllers\Api\v1\Learning\Asset_Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InputQuiz;
use App\Models\InputQuizQuestion;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class InputQuizQuestionController extends Controller
{
    public function index()
    {
        $questions = InputQuizQuestion::all();

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
    
    public function showQuestionsByID($id_input_quizezz)
    {
        try {
            // Validasi apakah quiz exists
            $quiz = InputQuiz::find($id_input_quizezz);
            if (!$quiz) {
                return response()->json([
                    'message' => 'Quiz tidak ditemukan.'
                ], 404);
            }

            // Ambil semua questions untuk quiz ini
            $questions = InputQuizQuestion::where('id_input_quizezz', $id_input_quizezz)
                ->orderBy('created_at', 'asc')
                ->get();

            // Hitung total questions
            $totalQuestions = $questions->count();

            return response()->json([
                'message' => 'Data soal berhasil diambil.',
                'data' => [
                    'quiz_info' => [
                        'id_input_quizezz' => $id_input_quizezz,
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

    public function store(Request $request, $id_input_quizezz)
    {
        try {
            $request->merge(['id_input_quizezz' => $id_input_quizezz]);
            
            // Validasi dasar
            $validated = $request->validate([
                'id_input_quizezz'       => 'required|exists:input_quizzes,id_input_quizezz',
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
            $data = $this->handleFlexibleInputs($request, $validated);
            
            $question = InputQuizQuestion::create($data);
            
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
            $question = InputQuizQuestion::findOrFail($id);
            
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
            $data = $this->handleFlexibleInputs($request, $validated);
            
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
        $question = InputQuizQuestion::find($id);
        if (!$question) {
            return response()->json(['message' => 'Soal tidak ditemukan.'], 404);
        }

        $question->delete();
        return response()->json(['message' => 'Soal berhasil dihapus.'], 200);
    }

    private function handleFileUploads($request, $data)
    {
        $fileFields = [
            'question_quiz' => 'question_quiz_file',
            'option_1' => 'option_1_file',
            'option_2' => 'option_2_file',
            'option_3' => 'option_3_file',
            'option_4' => 'option_4_file'
        ];

        foreach ($fileFields as $field => $fileField) {
            if ($request->hasFile($fileField)) {
                $folder = $field === 'question_quiz' ? 'quiz_questions' : 'options';
                $uploadedUrl = Cloudinary::upload($request->file($fileField)->getRealPath(), [
                    'folder' => "mathplay_gasing/{$folder}"
                ])->getSecurePath();
                
                $data[$field] = $uploadedUrl;
            }
        }

        return $data;
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

    private function handleFlexibleInputs($request, $data)
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